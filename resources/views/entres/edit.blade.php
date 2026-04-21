@extends('layouts.app')

@section('content')
<div class="pt-28 md:pt28 w-full bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        
        <!-- Bouton Retour -->
        <div class="mb-6">
            <a href="{{ route('entres.show', $entree->id) }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-green-600 transition uppercase tracking-widest">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Annuler et retour
            </a>
        </div>

        <!-- Carte de Modification (Style Dashboard) -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative z-10">
            
            <!-- En-tête -->
            <div class="bg-white border-b border-gray-100 p-5 sm:p-6 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Modifier l'entrée</h2>
                        <p class="text-xs sm:text-sm text-gray-400 font-medium">Mise à jour des informations pour le véhicule <span class="text-blue-600 font-bold uppercase">{{ $entree->plaque }}</span></p>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-10">
                <!-- Affichage des erreurs -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg">
                        <ul class="text-xs sm:text-sm font-bold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('entres.update', $entree->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Plaque (Généralement on ne change pas la plaque, mais si besoin elle est ici) -->
                        <div class="md:col-span-1">
                            <label for="plaque" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Plaque d'immatriculation</label>
                            <input type="text" id="plaque" name="plaque" value="{{ old('plaque', $entree->plaque) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 font-bold uppercase text-gray-800"
                                required>
                        </div>

                        <!-- Type de véhicule -->
                        <div class="md:col-span-1">
                            <label for="type" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Type de véhicule</label>
                            <select id="type" name="type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 font-bold text-gray-800 cursor-pointer"
                                required>
                                @foreach(['motorcycle' => 'Moto', 'car' => 'Voiture', 'tricycle' => 'Tricycle', 'nyonyovi' => 'Nyonyovi', 'minibus' => 'Minibus', 'bus' => 'Bus', 'truck' => 'Camion'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('type', $entree->type) == $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nom Propriétaire -->
                        <div class="md:col-span-1">
                            <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nom du propriétaire</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $entree->name) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 text-gray-800"
                                placeholder="Nom complet">
                        </div>

                        <!-- Téléphone -->
                        <div class="md:col-span-1">
                            <label for="phone" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Numéro de téléphone</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $entree->phone) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 text-gray-800"
                                placeholder="Ex: 90000000">
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="pt-8 flex flex-col sm:flex-row gap-4">
                        <button type="submit" 
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-lg shadow-md transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center space-x-2 text-sm sm:text-base">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>ENREGISTRER LES MODIFICATIONS</span>
                        </button>
                        
                        <a href="{{ route('entres.show', $entree->id) }}" 
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-4 rounded-lg transition-all flex items-center justify-center text-sm sm:text-base">
                            ANNULER
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-8 text-center">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Édition sécurisée - Parking Pro</p>
        </div>
    </div>
</div>
@endsection