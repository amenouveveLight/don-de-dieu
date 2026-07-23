@extends('layouts.app')

@section('content')
<div class="pt-16 md:pt-28 w-full bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative z-10">
            <div class="bg-white border-b border-gray-100 p-5 sm:p-6">
                <div class="flex items-center space-x-3">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Enregistrement d'entrée</h2>
                </div>
            </div>

            <div class="p-5 sm:p-8">
                <!-- Indicateur hors-ligne -->
                <div id="offline-notice" class="hidden mb-6 p-4 bg-orange-50 border-l-4 border-orange-500 text-orange-700 flex items-center">
                    <span class="font-bold">📴 Vous êtes hors-ligne : l'entrée sera enregistrée localement et synchronisée plus tard.</span>
                </div>

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 flex items-center">
                        <span class="font-bold">{{ session('success') }} (Impression en cours...)</span>
                    </div>
                @endif

                <form id="entry-form" method="POST" action="{{ route('entres.store') }}" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Plaque -->
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-2 tracking-wider">Plaque d'immatriculation</label>
                            <input type="text" id="plaque" name="plaque" required class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 font-bold uppercase text-gray-800" placeholder="TG-1234-AB">
                            <p id="plaque-status" class="text-xs mt-2 text-red-600 font-bold hidden">Ce véhicule est déjà présent !</p>
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-2 tracking-wider">Type de véhicule</label>
                            <select id="type" name="type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 font-bold text-gray-800 cursor-pointer">
                                <option value="" disabled selected>Choisir...</option>
                                <option value="motorcycle"> Moto</option>
                                <option value="car"> Voiture</option>
                                <option value="tricycle">Tricycle</option>
                                <option value="nyonyovi"> Nyonyovis</option>
                                <option value="minibus"> Minibus</option>
                                <option value="bus"> Bus</option>
                                <option value="truck"> Camion</option>
                            </select>
                        </div>

                        <!-- Nom -->
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-2 tracking-wider">Nom du propriétaire</label>
                            <input type="text" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-gray-800" placeholder="Nom complet">
                        </div>

                        <!-- Tél -->
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-2 tracking-wider">Téléphone</label>
                            <input type="tel" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-gray-800" placeholder="90000000">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" id="submit-btn" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-lg shadow-md transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center space-x-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="uppercase">Enregistrer et Imprimer</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- IFRAME INVISIBLE POUR L'IMPRESSION (mode en ligne) -->
<iframe id="print_frame" name="print_frame" style="position:absolute; top:-9999px; left:-9999px; border:none;"></iframe>

<!-- TEMPLATE TICKET IMPRIMABLE (mode hors-ligne, utilisé par imprimerTicketEntree() dans app.js) -->
<!-- ⚠️ Si ce bloc existe déjà dans layouts/app.blade.php, retire-le d'ici pour éviter un doublon d'ID -->
<div id="ticket-entree-print" style="display:none;">
    <div style="width: 280px; font-family: monospace; padding: 10px;">
        <h3 style="text-align:center;">TICKET D'ENTRÉE</h3>
        <p>N° : <span id="e-id"></span></p>
        <p>Plaque : <span id="e-plaque"></span></p>
        <p>Type : <span id="e-type"></span></p>
        <p>Nom : <span id="e-name"></span></p>
        <p>Tél : <span id="e-phone"></span></p>
        <p>Date : <span id="e-date"></span></p>
        <div style="text-align:center; margin-top:10px;">
            <canvas id="e-qrcode"></canvas>
        </div>
    </div>
</div>

<script>
    // GESTION DE L'IMPRESSION AUTOMATIQUE (mode en ligne, réponse serveur classique)
    @if(session('ticket_url'))
        window.onload = function() {
            const frame = document.getElementById('print_frame');
            frame.src = "{{ session('ticket_url') }}";
            
            frame.onload = function() {
                setTimeout(function() {
                    frame.contentWindow.focus();
                    frame.contentWindow.print();
                }, 500);
            };
        };
    @endif

    // GESTION OFFLINE : interception du formulaire si pas de connexion
    document.getElementById('entry-form').addEventListener('submit', async function(e) {
        // En ligne : on laisse le formulaire suivre son cours normal (POST classique vers le serveur)
        if (navigator.onLine) return;

        e.preventDefault();

        const form = e.target;
        const formData = {
            plaque: form.plaque.value,
            type: form.type.value,
            name: form.name.value,
            phone: form.phone.value,
        };

        await window.validerEntree(formData);
        form.reset();
    });

    // Affiche/masque le bandeau "hors-ligne" selon l'état de connexion
    function updateOfflineNotice() {
        const notice = document.getElementById('offline-notice');
        if (notice) notice.classList.toggle('hidden', navigator.onLine);
    }
    window.addEventListener('online', updateOfflineNotice);
    window.addEventListener('offline', updateOfflineNotice);
    document.addEventListener('DOMContentLoaded', updateOfflineNotice);

    // Logique AJAX Plaque (déjà présente dans votre code) ...
</script>
@endsection