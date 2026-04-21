@extends('layouts.app')

@section('content')

@php
    // Valeurs par défaut si les variables ne sont pas définies
    $agentsData = $agentsData ?? [];
    $date = $date ?? now()->format('Y-m-d');
    $periode = $periode ?? 'jour';
@endphp

<div class="pt-28 md:pt-28 w-full bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-8">
        
        <!-- En-tête de page -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4 text-center sm:text-left">
            <div class="flex items-center justify-center sm:justify-start space-x-3">
                <div class="bg-blue-100 p-2 rounded-lg">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Statistiques par agent</h1>
                    <p class="text-sm text-gray-500">Suivi des performances et de la collecte par utilisateur.</p>
                </div>
            </div>
            
            <div class="bg-white p-2 rounded-lg shadow-sm border border-gray-100 inline-flex items-center justify-center">
                <span class="text-xs font-bold text-gray-600 uppercase tracking-widest px-2">Rapport : {{ ucfirst($periode) }}</span>
            </div>
        </div>

        <!-- Section Filtrage (Style Dashboard) -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-5 mb-8">
            <form method="GET" action="{{ url()->current() }}">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                    
                    <!-- Sélection de la Date -->
                    <div>
                        <label for="date" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Date de référence</label>
                        <div class="relative">
                            <input type="date" name="date" id="date" value="{{ $date }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 text-sm font-semibold text-gray-700">
                        </div>
                    </div>

                    <!-- Sélection de la Période -->
                    <div>
                        <label for="periode" class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Période d'analyse</label>
                        <select name="periode" id="periode" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 text-sm font-semibold text-gray-700 cursor-pointer">
                            <option value="jour" @if($periode=='jour') selected @endif>Aujourd'hui (Jour)</option>
                            <option value="semaine" @if($periode=='semaine') selected @endif>Cette Semaine</option>
                            <option value="mois" @if($periode=='mois') selected @endif>Ce Mois</option>
                            <option value="année" @if($periode=='année') selected @endif>Cette Année</option>
                        </select>
                    </div>

                    <!-- Bouton de validation -->
                    <div>
                        <button type="submit" class="w-full bg-gray-800 hover:bg-black text-white font-bold py-2.5 px-4 rounded-lg transition-all flex items-center justify-center space-x-2 shadow-sm">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>FILTRER LES DONNÉES</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tableau des statistiques (Style Dashboard) -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Nom de l'Agent</th>
                            <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total Entrées</th>
                            <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">Total Sorties</th>
                            <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider text-green-700">Recette Totale</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($agentsData as $data)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Agent -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs mr-3">
                                        {{ substr($data['agent'], 0, 1) }}
                                    </div>
                                    <div class="text-sm font-bold text-gray-900">{{ $data['agent'] }}</div>
                                </div>
                            </td>
                            <!-- Entrées -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600 font-medium">
                                <span class="bg-gray-100 px-3 py-1 rounded-full">{{ $data['total_entrees'] }}</span>
                            </td>
                            <!-- Sorties -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600 font-medium">
                                <span class="bg-gray-100 px-3 py-1 rounded-full">{{ $data['total_sorties'] }}</span>
                            </td>
                            <!-- Montant -->
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm font-extrabold text-green-700">
                                    {{ number_format($data['montant_total'], 0, ',', ' ') }}
                                    <span class="text-[10px] ml-0.5">FCFA</span>
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                                <svg class="mx-auto h-12 w-12 text-gray-200 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Aucune donnée disponible pour cette période.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Note de pied de page -->
        <div class="mt-8 text-center">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Rapport de performance - Parking Pro</p>
        </div>
    </div>
</div>

@endsection