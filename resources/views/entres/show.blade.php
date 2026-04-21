@extends('layouts.app')

@section('content')
<div class="pt-24 md:pt-28 w-full bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        
        <!-- Bouton Retour -->
        <div class="mb-6">
            <a href="{{ route('recent') }}" class="inline-flex items-center text-xs font-bold text-gray-500 hover:text-green-600 transition uppercase tracking-widest">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à l'historique
            </a>
        </div>

        <!-- Carte Principale (Esprit Détails Sortie) -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative z-10">
            
            <!-- En-tête de la carte -->
            <div class="bg-white border-b border-gray-100 p-5 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-green-100 p-2 rounded-lg">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Détails de l'entrée</h2>
                            <p class="text-[10px] sm:text-xs text-gray-400 font-bold uppercase tracking-wider">Référence Ticket #{{ $entree->id }}</p>
                        </div>
                    </div>
                    <!-- Badge Statut Parking -->
                    @if(!$hasSortie)
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded-full uppercase border border-green-200">En Stationnement</span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[10px] font-bold rounded-full uppercase border border-gray-200">Véhicule Sorti</span>
                    @endif
                </div>
            </div>

            <!-- Contenu des informations (Grille 2 colonnes) -->
            <div class="p-6 sm:p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- Colonne 1 : Véhicule, Propriétaire & AGENT -->
                    <div class="space-y-6">
                        <!-- Véhicule -->
                        <div>
                            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Informations Véhicule</h3>
                            <div class="flex items-center space-x-3">
                                <div class="text-sm font-extrabold text-green-700 bg-green-50 px-3 py-1.5 rounded border border-green-100 uppercase tracking-wider">
                                    {{ $entree->plaque }}
                                </div>
                                <span class="text-sm font-bold text-gray-700">({{ ucfirst($entree->type) }})</span>
                            </div>
                        </div>

                        <!-- Propriétaire -->
                        <div>
                            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Propriétaire</h3>
                            <p class="text-sm font-bold text-gray-800">{{ $entree->name ?? 'Client Divers' }}</p>
                            <p class="text-xs text-gray-500">{{ $entree->phone ?? 'Aucun téléphone' }}</p>
                        </div>

                        
                           <!-- 🆕 Agent Responsable (SÉCURISÉ) -->
                        <div>
                            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Agent Responsable</h3>
                            <div class="flex items-center space-x-2">
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $entree->user?->firstname ?? 'Système' }} {{ $entree->user?->lastname ?? '(Ancien Ticket)' }}
                                </p>
                                @if(isset($entree->user) && $entree->user->role)
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[9px] font-bold rounded uppercase">
                                        {{ $entree->user->role }}
                                    </span>
                                @endif
                            </div>
                        </div>

                   
                    
                    </div>

                    
                    <!-- Colonne 2 : Horaires & Statut -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Horaires d'arrivée</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs border-b border-gray-50 pb-2">
                                    <span class="text-gray-500">Date d'entrée :</span>
                                    <span class="font-bold text-gray-800">{{ $entree->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between text-xs border-b border-gray-50 pb-2">
                                    <span class="text-gray-500">Heure d'arrivée :</span>
                                    <span class="font-bold text-gray-800">{{ $entree->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Bloc Récapitulatif Rapide -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Statut de modification</h3>
                            <p class="text-xs font-semibold {{ !$hasSortie ? 'text-blue-600' : 'text-amber-600' }}">
                                @if(!$hasSortie)
                                    <svg class="w-3 h-3 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" /></svg>Ouvert à l'édition
                                @else
                                    <svg class="w-3 h-3 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>Dossier Verrouillé
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row gap-4">
                    
                    {{-- Bouton Éditer --}}
                    @if (!$hasSortie)
                        <a href="{{ route('entres.edit', $entree->id) }}"
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-lg shadow-md transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="text-xs uppercase tracking-widest">Modifier les infos</span>
                        </a>
                    @else
                        <button class="flex-1 bg-gray-100 text-gray-400 font-bold py-4 rounded-lg cursor-not-allowed flex items-center justify-center space-x-2 border border-gray-200" disabled>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span class="text-xs uppercase tracking-widest">Édition Verrouillée</span>
                        </button>
                    @endif

                    {{-- Bouton Supprimer --}}
                    @if (!$hasSortie)
                        <form action="{{ route('entres.destroy', $entree->id) }}" method="POST" class="flex-1"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette entrée ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-white border border-red-200 text-red-600 font-bold py-4 rounded-lg hover:bg-red-50 transition-all text-xs uppercase tracking-widest flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Supprimer l'entrée</span>
                            </button>
                        </form>
                    @else
                         <button class="flex-1 border border-gray-200 text-gray-300 font-bold py-4 rounded-lg cursor-not-allowed flex items-center justify-center text-xs uppercase tracking-widest" disabled>
                            Suppression Verrouillée
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Consultation sécurisée - Parking Pro</p>
        </div>
    </div>
</div>
@endsection