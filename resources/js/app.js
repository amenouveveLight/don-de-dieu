import './bootstrap';
import { registerSW } from 'virtual:pwa-register';
import Dexie from 'dexie';
import QRCode from 'qrcode';

/**
 * 1. GESTION DES ASSETS ET PWA
 */
import.meta.glob(['../images/**', '../fonts/**']);
registerSW({ immediate: true });

/**
 * 2. CONFIGURATION DE LA BASE DE DONNÉES LOCALE (DEXIE)
 */
const db = new Dexie('ParkingLocalDB');
db.version(1).stores({
    entrees: '++id, plaque, type, name, phone, created_at, synced',
    sorties: '++id, plaque, type, montant, paiement, created_at, synced',
    tarifs: 'type, tarif',
});
window.db = db;

/**
 * 3. SYNCHRONISATION DES INFOS DE SESSION
 */
if (window.userId) {
    localStorage.setItem('agent_id', window.userId);
}

/**
 * 4. FONCTIONS D'IMPRESSION (OFFLINE-READY)
 */

window.imprimerTicketEntree = async function(data) {
    const ticketEl = document.getElementById('ticket-entree-print');
    const sortieEl = document.getElementById('ticket-sortie-print');
    if (!ticketEl) return;

    ticketEl.style.display = 'block';
    if (sortieEl) sortieEl.style.display = 'none';

    document.getElementById('e-id').innerText = data.id ? '#' + data.id : '#OFFLINE';
    document.getElementById('e-plaque').innerText = data.plaque.toUpperCase();
    document.getElementById('e-type').innerText = data.type;
    document.getElementById('e-name').innerText = data.name || 'Inconnu';
    document.getElementById('e-phone').innerText = data.phone || '-';
    document.getElementById('e-date').innerText = new Date(data.created_at).toLocaleString();

    const qrContent = `ENTREE|${data.plaque}|${data.created_at}`;
    await QRCode.toCanvas(document.getElementById('e-qrcode'), qrContent, { width: 140, margin: 1 });

    window.print();
};

window.imprimerTicketSortie = async function(sortie, entree, jours, total) {
    const ticketEl = document.getElementById('ticket-entree-print');
    const sortieEl = document.getElementById('ticket-sortie-print');
    if (!sortieEl) return;

    if (ticketEl) ticketEl.style.display = 'none';
    sortieEl.style.display = 'block';

    document.getElementById('s-id').innerText = sortie.id ? '#' + sortie.id : '#OFFLINE';
    document.getElementById('s-plaque').innerText = sortie.plaque.toUpperCase();
    document.getElementById('s-name').innerText = sortie.owner_name || 'Inconnu';
    document.getElementById('s-date-e').innerText = entree ? new Date(entree.created_at).toLocaleString() : 'Inconnue';
    document.getElementById('s-date-s').innerText = new Date(sortie.created_at).toLocaleString();
    document.getElementById('s-duree').innerText = jours + " Jour(s)";
    document.getElementById('s-pay-mode').innerText = sortie.paiement;
    document.getElementById('s-total').innerText = total.toLocaleString();

    const qrContent = `SORTIE|${sortie.plaque}|TOTAL:${total}F`;
    await QRCode.toCanvas(document.getElementById('s-qrcode'), qrContent, { width: 140, margin: 1 });

    window.print();
};

/**
 * 5. LOGIQUE DE SAUVEGARDE ET VALIDATION
 */

window.validerEntree = async function(formData) {
    const data = {
        user_id: localStorage.getItem('agent_id'),
        plaque: formData.plaque.toUpperCase(),
        type: formData.type,
        name: formData.name || 'Inconnu',
        phone: formData.phone || '-',
        created_at: new Date().toISOString(),
        synced: 0
    };

    if (navigator.onLine) {
        try {
            const res = await axios.post('/api/entres', data);
            data.id = res.data.id;
            console.log("✅ Enregistré sur le serveur");
        } catch (e) {
            if (e.response && e.response.status === 409) {
                // Conflit réel même en ligne : on prévient tout de suite, pas de stockage local
                alert("⚠️ " + e.response.data.message);
                return;
            }
            await db.entrees.add(data);
            console.warn("⚠️ Serveur injoignable, sauvegardé localement");
        }
    } else {
        await db.entrees.add(data);
        console.log("📴 Mode Hors-ligne : Sauvegardé localement");
    }

    await window.imprimerTicketEntree(data);

    if (window.location.pathname.includes('entres')) {
        setTimeout(() => window.location.reload(), 1000);
    }
};

window.validerSortie = async function(entree, modePaiement) {
    const tarifObj = await db.tarifs.get(entree.type.toLowerCase());
    const prixJournalier = tarifObj ? tarifObj.tarif : 0;

    const dateEntree = new Date(entree.created_at);
    const dateSortie = new Date();
    const diffTime = Math.abs(dateSortie - dateEntree);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1;
    const montantTotal = diffDays * prixJournalier;

    const sortieData = {
        user_id: localStorage.getItem('agent_id'),
        plaque: entree.plaque.toUpperCase(),
        type: entree.type,
        owner_name: entree.name || 'Inconnu',
        owner_phone: entree.phone || '-',
        montant: montantTotal,
        paiement: modePaiement,
        paiement_ok: 1,
        created_at: dateSortie.toISOString(),
        synced: 0
    };

    if (navigator.onLine) {
        try {
            const res = await axios.post('/api/sorties', sortieData);
            sortieData.id = res.data.id;
        } catch (e) {
            if (e.response && e.response.status === 409) {
                alert("⚠️ " + e.response.data.message);
                return;
            }
            await db.sorties.add(sortieData);
        }
    } else {
        await db.sorties.add(sortieData);
    }

    await window.imprimerTicketSortie(sortieData, entree, diffDays, montantTotal);

    setTimeout(() => window.location.reload(), 1000);
};

/**
 * 6. SYNCHRONISATION UNITAIRE AVEC DÉTECTION DE CONFLIT
 */

window.synchroniserTout = async function() {
    if (!navigator.onLine) return;

    const entrees = await db.entrees.where('synced').equals(0).toArray();
    const sorties = await db.sorties.where('synced').equals(0).toArray();

    if (entrees.length === 0 && sorties.length === 0) return;

    let conflits = [];

    for (const e of entrees) {
        try {
            const res = await axios.post('/api/entres', e);
            await db.entrees.update(e.id, { synced: 1, server_id: res.data.id });
        } catch (err) {
            if (err.response && err.response.status === 409) {
                conflits.push(`Entrée ${e.plaque} : ${err.response.data.message}`);
                await db.entrees.update(e.id, { synced: -1 });
            }
            // erreur réseau en cours de route : on laisse synced=0, on réessaiera au prochain passage
        }
    }

    for (const s of sorties) {
        try {
            const res = await axios.post('/api/sorties', s);
            await db.sorties.update(s.id, { synced: 1, server_id: res.data.id });
        } catch (err) {
            if (err.response && err.response.status === 409) {
                conflits.push(`Sortie ${s.plaque} : ${err.response.data.message}`);
                await db.sorties.update(s.id, { synced: -1 });
            }
        }
    }

    if (conflits.length > 0) {
        alert("⚠️ Conflits détectés lors de la synchro :\n\n" + conflits.join('\n') + "\n\nCes enregistrements doivent être vérifiés manuellement.");
    } else {
        console.log("🚀 Synchronisation réussie !");
        window.location.reload();
    }
};

window.refreshAppData = async function() {
    if (!navigator.onLine) return;
    try {
        const resTarifs = await axios.get('/api/tarifs');
        if (resTarifs.data.length > 0) {
            await db.tarifs.clear();
            await db.tarifs.bulkAdd(resTarifs.data);
        }
        const resPresents = await axios.get('/api/presents');
        if (resPresents.data.length > 0) {
            await db.entrees.bulkPut(resPresents.data.map(item => ({ ...item, synced: 1 })));
        }
        console.log("🔄 Données de référence à jour");
    } catch (e) { console.error(e); }
};

/**
 * 7. ÉVÉNEMENTS
 */
window.addEventListener('online', window.synchroniserTout);
window.addEventListener('load', window.refreshAppData);