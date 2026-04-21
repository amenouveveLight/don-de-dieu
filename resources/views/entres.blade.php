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
                <!-- Alertes Success/Error ici ... -->
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

<!-- IFRAME INVISIBLE POUR L'IMPRESSION -->
<iframe id="print_frame" name="print_frame" style="position:absolute; top:-9999px; left:-9999px; border:none;"></iframe>

<script>
    // GESTION DE L'IMPRESSION AUTOMATIQUE
    @if(session('ticket_url'))
        window.onload = function() {
            const frame = document.getElementById('print_frame');
            frame.src = "{{ session('ticket_url') }}";
            
            frame.onload = function() {
                setTimeout(function() {
                    frame.contentWindow.focus();
                    frame.contentWindow.print();
                }, 500); // Petit délai pour s'assurer que le contenu est rendu
            };
        };
    @endif

    // Logique AJAX Plaque (déjà présente dans votre code) ...
</script>
@endsection