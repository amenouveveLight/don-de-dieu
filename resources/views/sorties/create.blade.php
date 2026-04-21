@extends('layouts.app')

@section('content')

<div class="pt-20 md:pt-28">
    <!-- Ici ton contenu principal -->
    <main class="mt-2 md:mt-5">
        
        <!-- max-w-2xl limite la largeur sur PC/Tablette. mx-auto le centre au milieu de l'écran. -->
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div id="exit-form-container" class="bg-white shadow-lg rounded-xl p-5 sm:p-8 relative z-10">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-6 text-center sm:text-left">
                    Enregistrer une sortie
                </h2>

                <!-- Messages Flash -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 sm:py-4 rounded-lg relative mb-5 text-sm sm:text-base">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 sm:py-4 rounded-lg relative mb-5 text-sm sm:text-base">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Formulaire de recherche par plaque -->
                <form action="{{ route('sorties.create') }}" method="GET" class="mb-6 sm:mb-8">
                    <label for="plaque" class="block text-sm font-medium text-gray-700 mb-1">Plaque du véhicule</label>
                    <input type="text" name="plaque" id="plaque" placeholder="Ex : AB-123-CD"
                           value="{{ old('plaque', $plaque ?? '') }}"
                           onchange="this.form.submit()"
                           required
                           class="w-full px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg shadow-sm text-base bg-gray-50 focus:ring-2 focus:ring-green-600 focus:border-green-600 text-gray-700">
                    @error('plaque')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </form>

                @if(!empty($typesDisponibles))
                    <!-- Formulaire d’enregistrement de sortie (avec espacement space-y) -->
                    <form method="POST" action="{{ route('sorties.store') }}" class="space-y-4 sm:space-y-5">
                        @csrf
                        <input type="hidden" name="plaque" value="{{ old('plaque', $plaque ?? '') }}">

                        <!-- Type de véhicule -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type de véhicule</label>
                            <select name="type" id="type" required onchange="updateMontant()"
                                    class="w-full px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg shadow-sm text-base bg-gray-50 focus:ring-2 focus:ring-green-600 focus:border-green-600 text-gray-700">
                                <option value="">-- Sélectionnez --</option>
                                @foreach ($types as $val => $infos)
                                    @if(in_array($val, $typesDisponibles))
                                        <option value="{{ $val }}" {{ (old('type', $dernierType ?? '') === $val) ? 'selected' : '' }}>
                                            {{ $infos['label'] }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('type')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Montant affiché (lecture seule) -->
                        <div>
                            <label for="montant" class="block text-sm font-medium text-gray-700 mb-1">Montant (FCFA)</label>
                            <input type="text" name="montant" id="montant" value="{{ $montant ?? '—' }}" readonly
                                   class="w-full px-4 py-2.5 sm:py-3 border border-gray-300 bg-gray-100 text-gray-800 rounded-lg text-base cursor-not-allowed">
                        </div>

                        <!-- Méthode de paiement -->
                        <div>
                            <label for="paiement" class="block text-sm font-medium text-gray-700 mb-1">Méthode de paiement</label>
                            <select name="paiement" id="paiement" required
                                    class="w-full px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg shadow-sm bg-gray-50 focus:ring-2 focus:ring-green-600 focus:border-green-600 text-base text-gray-700">
                                <option value="">-- Choisir --</option>
                                @foreach(['cash' => 'Espèces', 'card' => 'Carte', 'app' => 'Application'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('paiement') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('paiement')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Paiement effectué -->
                        <div class="flex items-center pt-1">
                            <!-- Case agrandie pour être plus facilement cliquable sur mobile (h-5 w-5) -->
                            <input type="checkbox" name="paiement_ok" id="paiement_ok"
                                   class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded cursor-pointer"
                                   {{ old('paiement_ok') ? 'checked' : '' }}>
                            <label for="paiement_ok" class="ml-3 text-sm sm:text-base font-medium text-gray-700 cursor-pointer">
                                Paiement effectué
                            </label>
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="pt-3">
                            <button type="submit"
                                    class="w-full bg-green-600 text-white font-semibold py-3 sm:py-3.5 rounded-lg hover:bg-green-700 transition-colors duration-200 text-sm sm:text-base">
                                Enregistrer la sortie
                            </button>
                        </div>
                    </form>

                @elseif(isset($plaque))
                    <!-- Affichage du message uniquement si une plaque a été cherchée mais aucun type dispo -->
                    <div class="mt-6 p-4 bg-red-50 rounded-lg border border-red-200">
                        <p class="text-red-600 font-semibold text-center sm:text-left text-sm sm:text-base">
                            Toutes les sorties ont déjà été enregistrées pour cette plaque.
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </main>
</div>

@endsection