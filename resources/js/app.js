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

// --- TICKET ENTRÉE ---
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

// --- TICKET SORTIE ---
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

// --- VALIDATION ENTRÉE ---
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
            data.id = res.data.id; // On récupère l'ID réel du serveur
            console.log("✅ Enregistré sur le serveur");
        } catch (e) {
            await db.entrees.add(data);
            console.warn("⚠️ Serveur injoignable, sauvegardé localement");
        }
    } else {
        await db.entrees.add(data);
        console.log("📴 Mode Hors-ligne : Sauvegardé localement");
    }

    // Lancement de l'impression
    await window.imprimerTicketEntree(data);

    // Refresh si on est sur la page d'entrée pour vider le formulaire
    if(window.location.pathname.includes('entres')) {
        setTimeout(() => window.location.reload(), 1000);
    }
};

// --- VALIDATION SORTIE ---
window.validerSortie = async function(entree, modePaiement) {
    // 1. Calcul du montant
    const tarifObj = await db.tarifs.get(entree.type.toLowerCase());
    const prixJournalier = tarifObj ? tarifObj.tarif : 0;

    const dateEntree = new Date(entree.created_at);
    const dateSortie = new Date();
    const diffTime = Math.abs(dateSortie - dateEntree);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1;
    const montantTotal = diffDays * prixJournalier;

    // 2. Préparation des données
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

    // 3. Sauvegarde
    if (navigator.onLine) {
        try {
            const res = await axios.post('/api/sorties', sortieData);
            sortieData.id = res.data.id;
        } catch (e) {
            await db.sorties.add(sortieData);
        }
    } else {
        await db.sorties.add(sortieData);
    }

    // 4. Impression
    await window.imprimerTicketSortie(sortieData, entree, diffDays, montantTotal);

    // Refresh pour nettoyer
    setTimeout(() => window.location.reload(), 1000);
};

/**
 * 6. SYNCHRONISATION ET CHARGEMENT
 */

window.synchroniserTout = async function() {
    if (!navigator.onLine) return;

    const e = await db.entrees.where('synced', 0).toArray();
    const s = await db.sorties.where('synced', 0).toArray();

    if (e.length === 0 && s.length === 0) return;

    try {
        const res = await axios.post('/api/sync-all', { entrees: e, sorties: s });
        if (res.status === 200) {
            await db.entrees.where('synced', 0).modify({ synced: 1 });
            await db.sorties.where('synced', 0).modify({ synced: 1 });
            alert("🚀 Synchronisation réussie !");
            window.location.reload(); 
        }
    } catch (err) {
        console.error("❌ Échec synchro", err);
    }
};

window.refreshAppData = async function() {
    if (!navigator.onLine) return;
    try {
        const resTarifs = await axios.get('/api/tarifs');
        if(resTarifs.data.length > 0) {
            await db.tarifs.clear();
            await db.tarifs.bulkAdd(resTarifs.data);
        }
        const resPresents = await axios.get('/api/presents');
        if(resPresents.data.length > 0) {
            await db.entrees.bulkPut(resPresents.data.map(item => ({...item, synced: 1})));
        }
        console.log("🔄 Données de référence à jour");
    } catch (e) { console.error(e); }
};

/**
 * 7. ÉVÉNEMENTS
 */
window.addEventListener('online', window.synchroniserTout);
window.addEventListener('load', window.refreshAppData);
