@extends('layouts.app')

@section('content')
<div class="pt-24 md:pt-32 w-full bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        
        <!-- Bouton Retour -->
        <div class="mb-6 print:hidden">
            <a href="{{ route('recent') }}" class="inline-flex items-center text-xs font-bold text-gray-500 hover:text-red-600 transition uppercase tracking-widest">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à l'historique
            </a>
        </div>

        <!-- Carte Principale (Style Dashboard) -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative z-10">
            
            <!-- En-tête de la carte -->
            <div class="bg-white border-b border-gray-100 p-5 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-red-100 p-2 rounded-lg">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Détails de la sortie</h2>
                            <p class="text-[10px] sm:text-xs text-gray-400 font-bold uppercase tracking-wider">Référence Facture #{{ $sortie->id }}</p>
                        </div>
                    </div>
                    <!-- Badge Statut Paiement -->
                    @if($sortie->paiement_ok)
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded-full uppercase border border-green-200">Payé</span>
                    @else
                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-full uppercase border border-amber-200">En attente</span>
                    @endif
                </div>
            </div>

            <!-- Contenu des informations -->
            <div class="p-6 sm:p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- Colonne 1 : Véhicule & Client -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Informations Véhicule</h3>
                            <div class="flex items-center space-x-3">
                                <div class="text-sm font-extrabold text-red-700 bg-red-50 px-3 py-1.5 rounded border border-red-100 uppercase tracking-wider">
                                    {{ $sortie->plaque }}
                                </div>
                                <span class="text-sm font-bold text-gray-700">({{ ucfirst($sortie->type) }})</span>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Propriétaire</h3>
                            <p class="text-sm font-bold text-gray-800">{{ $sortie->owner_name ?? 'Client Divers' }}</p>
                            <p class="text-xs text-gray-500">{{ $sortie->owner_phone ?? 'Aucun téléphone' }}</p>
                        </div>

                        <!-- 🆕 Agent Responsable (SÉCURISÉ COMME L'ENTRÉE) -->
                        <div>
                            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Agent Responsable</h3>
                            <div class="flex items-center space-x-2">
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $sortie->user?->firstname ?? 'Système' }} {{ $sortie->user?->lastname ?? '(Ancienne Sortie)' }}
                                </p>
                                @if(isset($sortie->user) && $sortie->user->role)
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[9px] font-bold rounded uppercase">
                                        {{ $sortie->user->role }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Colonne 2 : Temps & Facturation -->
                    <div class="space-y-6">
                        
                    <!-- 🆕 Agents Responsables -->
<div class="bg-gray-50 p-4 rounded-xl border border-gray-100 space-y-4">
    
    <!-- Agent de Sortie (Celui qui a fait ce ticket) -->
    <div>
        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 flex items-center">
            <svg class="w-3 h-3 mr-1 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Heure de Sortie
        </h3>
        <div class="flex items-center space-x-2">
            <p class="text-sm font-bold text-gray-800">
                {{ $sortie->created_at->isoFormat('DD/MM/YYYY [à] HH:mm') }}
            </p>
            
        </div>
    </div>
</div>
                    

                        <!-- Bloc Montant -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Montant Total Perçu</h3>
                            <div class="flex items-baseline space-x-1">
                                <span class="text-3xl font-extrabold text-green-700">{{ number_format($sortie->montant, 0, ',', ' ') }}</span>
                                <span class="text-sm font-bold text-green-600">FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liens d'édition et suppression (Avant Impression) -->
                <div class="mt-10 mb-4 flex items-center justify-start space-x-6 border-t border-gray-50 pt-6 print:hidden">
                    <a href="{{ route('sorties.edit', $sortie->id) }}" class="text-blue-600 hover:text-blue-800 flex items-center text-xs font-bold uppercase tracking-widest transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Modifier les infos
                    </a>

                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'gerant')
                        <form action="{{ route('sorties.destroy', $sortie->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer définitivement cette sortie ?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 flex items-center text-xs font-bold uppercase tracking-widest transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Actions Principales (Boutons) -->
                <div class="mt-4 flex flex-col sm:flex-row gap-4 print:hidden">
                    <!-- Bouton Imprimer Reçu -->
                    <button onclick="window.print()" class="flex-1 bg-gray-800 hover:bg-black text-white font-bold py-4 rounded-lg shadow-md transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center space-x-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <span class="text-xs uppercase tracking-widest">Imprimer le reçu</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Note footer -->
        <div class="mt-8 text-center print:hidden">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Transaction sécurisée par Parking Pro</p>
        </div>
    </div>
</div>

<style>
    @media print {
        .pt-24, .mb-6, .mt-10, .mt-4, .text-center, .print\:hidden { display: none !important; }
        .shadow-lg { box-shadow: none !important; border: 1px solid #eee !important; }
        .bg-gray-50 { background: white !important; }
        .max-w-3xl { max-width: 100% !important; margin: 0 !important; }
    }
</style>
@endsection