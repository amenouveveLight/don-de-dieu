@extends('layouts.app')

@section('content')

<div class="pt-28 md:pt-28 w-full bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-8">
        
        <!-- En-tête de page -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Historique des activités</h1>
                <p class="text-sm text-gray-500">Consultez et filtrez tous les mouvements du parking.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- BOUTON SYNCHRO (Apparaît seulement s'il y a des données offline) -->
                <button id="sync-btn" onclick="window.synchroniserTout()" class="hidden bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg flex items-center space-x-2 animate-pulse transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>SYNCHRONISER (<span id="offline-count">0</span>)</span>
                </button>

                <div class="bg-white p-2 rounded-lg shadow-sm border border-gray-100 inline-flex items-center">
                    <span class="flex h-3 w-3 relative mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-widest">Mise à jour en direct</span>
                </div>
            </div>
        </div>

        <!-- Section Filtrage -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-5 mb-8">
            <form method="GET" action="{{ route('recent') }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <!-- Plaque -->
                    <div>
                        <label for="plaque" class="block text-xs font-bold text-gray-500 uppercase mb-2">Plaque</label>
                        <input type="text" name="plaque" id="plaque" value="{{ request('plaque') }}" placeholder="Rechercher..." class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 text-sm">
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-xs font-bold text-gray-500 uppercase mb-2">Type d'engin</label>
                        <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 text-sm font-semibold text-gray-700">
                            <option value="">Tous les types</option>
                            @foreach(['motorcycle' => 'Moto', 'car' => 'Voiture', 'tricycle' => 'Tricycle', 'nyonyovi' => 'Nyonyovi', 'minibus' => 'Minibus', 'bus' => 'Bus', 'truck' => 'Camion'] as $val => $label)
                                <option value="{{ $val }}" {{ request('type') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Période -->
                    <div>
                        <label for="periode" class="block text-xs font-bold text-gray-500 uppercase mb-2">Période</label>
                        <select name="periode" id="periode" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 text-sm font-semibold text-gray-700">
                            <option value="">Toute période</option>
                            <option value="jour" {{ request('periode') == 'jour' ? 'selected' : '' }}>Aujourd'hui</option>
                            <option value="semaine" {{ request('periode') == 'semaine' ? 'selected' : '' }}>Cette semaine</option>
                            <option value="mois" {{ request('periode') == 'mois' ? 'selected' : '' }}>Ce mois</option>
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <span>FILTRER</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tableau des activités -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Date & Heure</th>
                            <th class="px-4 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Véhicule</th>
                            <th class="px-4 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Propriétaire</th>
                            <th class="px-4 py-4 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-4 py-4 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-4 py-4 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="activite-body" class="bg-white divide-y divide-gray-100">
                        <!-- LES DONNÉES OFFLINE SERONT INJECTÉES ICI PAR JS -->

                        @forelse ($activites as $act)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-xs font-bold text-gray-900">{{ $act['date'] }}</div>
                                <div class="text-[10px] text-gray-400 uppercase">{{ $act['evenement'] }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-extrabold text-green-700 bg-green-50 px-2 py-1 rounded border border-green-100 uppercase">{{ $act['plaque'] }}</div>
                                    <span class="ml-2 text-[10px] text-gray-400 italic">({{ $act['type'] }})</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-xs text-gray-700 font-medium">{{ $act['name'] ?? 'Inconnu' }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <div class="text-xs font-bold text-gray-600">{{ isset($act['montant']) ? $act['montant'].' F' : '--' }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 uppercase shadow-sm">
                                    ☁️ Synchro
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-xs">
                                <a href="{{ route($act['source'] === 'entree' ? 'entres.show' : 'sorties.show', $act['id']) }}" class="text-blue-600 font-bold">Détails</a>
                            </td>
                        </tr>
                        @empty
                        <tr id="empty-row">
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">Aucune activité enregistrée.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($activites->hasPages())
            <div class="bg-gray-50 px-4 py-4 border-t border-gray-100 flex items-center justify-center">
                {{ $activites->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const tableBody = document.getElementById('activite-body');
        const syncBtn = document.getElementById('sync-btn');
        const offlineCount = document.getElementById('offline-count');
        const emptyRow = document.getElementById('empty-row');

        // 1. Récupération des données offline (Dexie)
        const entreesOffline = await db.entrees.where('synced', 0).toArray();
        const sortiesOffline = await db.sorties.where('synced', 0).toArray();
        const allOffline = [...entreesOffline, ...sortiesOffline].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

        if (allOffline.length > 0) {
            // Affichage du bouton de synchro
            syncBtn.classList.remove('hidden');
            offlineCount.innerText = allOffline.length;
            if(emptyRow) emptyRow.classList.add('hidden');

            // 2. Injection des lignes offline dans le tableau
            allOffline.forEach(item => {
                const isSortie = item.montant !== undefined;
                const row = document.createElement('tr');
                row.className = "bg-orange-50 hover:bg-orange-100 transition-colors border-l-4 border-orange-500";
                
                const dateObj = new Date(item.created_at);
                const dateStr = dateObj.toLocaleDateString('fr-FR');
                const timeStr = dateObj.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'});

                row.innerHTML = `
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-xs font-bold text-orange-900">${dateStr} ${timeStr}</div>
                        <div class="text-[10px] text-orange-500 uppercase font-bold">${isSortie ? 'Sortie' : 'Entrée'}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-extrabold text-orange-700 bg-orange-100 px-2 py-1 rounded border border-orange-200 uppercase">${item.plaque}</div>
                            <span class="ml-2 text-[10px] text-orange-400 italic">(${item.type})</span>
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-xs text-orange-700 font-medium">${item.name || item.owner_name || 'Inconnu'}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-center">
                        <div class="text-xs font-bold text-orange-600">${isSortie ? item.montant + ' F' : '--'}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-200 text-orange-800 uppercase shadow-sm">
                            📴 Local
                        </span>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-right text-xs">
                        <span class="text-orange-400 italic">En attente...</span>
                    </td>
                `;
                tableBody.prepend(row);
            });
        }
    });
</script>

@endsection