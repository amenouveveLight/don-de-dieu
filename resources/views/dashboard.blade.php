@extends('layouts.app')

@section('content')
<div class="pt-28 min-h-screen bg-gray-50">
    <div id="dashboard" class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-8">
        
        <!-- EN-TÊTE DU DASHBOARD -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8 border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-600 p-3 rounded-lg shadow-lg">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-800">Tableau de bord</h1>
                        <p class="text-sm text-gray-500 font-medium">Vue d'ensemble de l'activité du parking</p>
                    </div>
                </div>
                
                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center">
                    <button type="submit" class="flex items-center space-x-2 bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg transition-all border border-gray-200">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="text-sm font-bold">Actualiser</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- CARTES DE STATISTIQUES (GRID) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Record Journalier -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Record journalier</p>
                        <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $topDailyEntry->total ?? '0' }}</h3>
                    </div>
                    <div class="p-2 bg-indigo-50 rounded-lg">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-[10px] text-gray-500 mt-3 font-semibold">
                    @if($topDailyEntry) Le {{ \Carbon\Carbon::parse($topDailyEntry->date)->format('d/m/Y') }} @else Aucune donnée @endif
                </p>
            </div>

            <!-- Places Disponibles -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Places libres</p>
                        <h3 class="text-2xl font-black text-green-600 mt-1">{{ $places_disponibles }}</h3>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-center mt-3">
                    <span class="text-[10px] font-bold {{ strpos($evolution_1h, '+') !== false ? 'text-green-500' : 'text-red-500' }}">{{ $evolution_1h }}</span>
                    <span class="text-[10px] text-gray-400 ml-1 font-semibold text-gray-500">depuis 1h</span>
                </div>
            </div>

            <!-- Places Occupées -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Occupées</p>
                        <h3 class="text-2xl font-black text-red-600 mt-1">{{ $places_occupees }}</h3>
                    </div>
                    <div class="p-2 bg-red-50 rounded-lg">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-center mt-3">
                    <span class="text-[10px] font-bold {{ $evolution_occupees_1h >= 0 ? 'text-red-500' : 'text-green-500' }}">
                        {{ $evolution_occupees_1h >= 0 ? '+' : '' }}{{ $evolution_occupees_1h }}
                    </span>
                    <span class="text-[10px] text-gray-400 ml-1 font-semibold text-gray-500">depuis 1h</span>
                </div>
            </div>

            <!-- Taux d'occupation -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Taux Occupation</p>
                        <h3 class="text-2xl font-black text-yellow-600 mt-1">{{ $taux_occupation }}%</h3>
                    </div>
                    <div class="p-2 bg-yellow-50 rounded-lg">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                </div>
                <div class="w-full bg-gray-100 h-1.5 rounded-full mt-4">
                    <div class="bg-yellow-500 h-1.5 rounded-full" style="width: {{ $taux_occupation }}%"></div>
                </div>
            </div>
        </div>

        <!-- SECTION ONGLETS ET DONNÉES -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            
            <!-- Navigation des Onglets (Style Tabs Moderne) -->
            @php $currentPeriod = request('period', 'jour'); @endphp
            <div class="bg-gray-50/50 border-b border-gray-200 px-4 pt-4">
                <div class="flex flex-wrap -mb-px">
                    <a href="?period=jour" class="mr-4 pb-4 px-2 text-sm font-bold border-b-2 transition-all {{ $currentPeriod == 'jour' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">JOURNALIER</a>
                    <a href="?period=semaine" class="mr-4 pb-4 px-2 text-sm font-bold border-b-2 transition-all {{ $currentPeriod == 'semaine' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">HEBDOMADAIRE</a>
                    <a href="?period=mois" class="mr-4 pb-4 px-2 text-sm font-bold border-b-2 transition-all {{ $currentPeriod == 'mois' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">MENSUEL</a>
                    <a href="?period=année" class="mr-4 pb-4 px-2 text-sm font-bold border-b-2 transition-all {{ $currentPeriod == 'année' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">ANNUEL</a>
                </div>
            </div>

            <div class="p-4 sm:p-8">
                <!-- Filtres contextuels -->
                <div class="mb-8 bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col md:flex-row items-end gap-4">
                        <input type="hidden" name="period" value="{{ $currentPeriod }}">
                        
                        <div class="w-full md:w-auto flex-1">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Sélectionner la période</label>
                            @if($currentPeriod == 'jour')
                                <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none">
                            @elseif($currentPeriod == 'semaine')
                                <input type="week" name="date" value="{{ request('date', date('Y').'-W'.date('W')) }}" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none">
                            @elseif($currentPeriod == 'mois')
                                <input type="month" name="date" value="{{ request('date', date('Y-m')) }}" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none">
                            @else
                                <input type="number" name="date" min="2024" max="2100" value="{{ request('date', date('Y')) }}" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-2.5 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-green-500 outline-none">
                            @endif
                        </div>

                        <div class="flex w-full md:w-auto gap-2">
                            <button type="submit" class="flex-1 md:flex-none bg-gray-800 hover:bg-black text-white font-bold py-2.5 px-6 rounded-lg transition-all text-sm uppercase">Appliquer</button>
                            
                            @php
                                $exportMap = ['jour' => 'export.jour', 'semaine' => 'export.semaine', 'mois' => 'export.mois', 'année' => 'export.annee'];
                                $routeExport = $exportMap[$currentPeriod] ?? 'export.jour';
                            @endphp
                            <a href="{{ route($routeExport, ['date' => request('date', date('Y-m-d'))]) }}" class="flex-1 md:flex-none bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-lg transition-all text-sm text-center uppercase">Exporter PDF</a>
                        </div>
                    </form>
                </div>

                <!-- Tableau de données (Engine Stats) -->
                @php
                    $tableData = match($currentPeriod) {
                        'semaine' => $weeklyTableData,
                        'mois' => $monthlyTableData,
                        'année' => $yearlyTableData,
                        default => $dailyTableData,
                    };
                    $totalE = match($currentPeriod) { 'semaine' => $weeklyTotalEntrees, 'mois' => $monthlyTotalEntrees, 'année' => $yearlyTotalEntrees, default => $dailyTotalEntrees };
                    $totalS = match($currentPeriod) { 'semaine' => $weeklyTotalSorties, 'mois' => $monthlyTotalSorties, 'année' => $yearlyTotalSorties, default => $dailyTotalSorties };
                    $totalR = match($currentPeriod) { 'semaine' => $weeklyRevenusTotaux, 'mois' => $monthlyRevenusTotaux, 'année' => $yearlyRevenusTotaux, default => $dailyRevenusTotaux };
                @endphp

                <div class="overflow-x-auto rounded-xl border border-gray-100 mb-8">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Type Engin</th>
                                <th class="px-6 py-4 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Entrées</th>
                                <th class="px-6 py-4 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Sorties</th>
                                <th class="px-6 py-4 text-center text-[10px] font-black text-gray-500 uppercase tracking-widest">Tarif</th>
                                <th class="px-6 py-4 text-right text-[10px] font-black text-gray-500 uppercase tracking-widest">Revenus</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($tableData as $stat)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">{{ $stat['label'] }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">{{ $stat['entrees'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold">{{ $stat['sorties'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-xs font-bold text-gray-500">{{ number_format($stat['tarif'], 0, ',', ' ') }}</td>
                                <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-black text-gray-900">{{ number_format($stat['revenu'], 0, ',', ' ') }} <span class="text-[10px]">FCFA</span></td>
                            </tr>
                            @endforeach
                            <tr class="bg-gray-800">
                                <td class="px-6 py-4 text-sm font-black text-white uppercase">TOTAL</td>
                                <td class="px-6 py-4 text-center text-sm font-black text-white">{{ $totalE }}</td>
                                <td class="px-6 py-4 text-center text-sm font-black text-white">{{ $totalS }}</td>
                                <td class="px-6 py-4 text-center text-sm font-black text-white">-</td>
                                <td class="px-6 py-4 text-right text-sm font-black text-green-400">{{ number_format($totalR, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- GRAPHIQUES SVG (Adaptés selon période) -->
                @php
                    $pieData = match($currentPeriod) { 'semaine' => $weeklyPieSegments, 'mois' => $monthlyPieSegments, 'année' => $yearlyPieSegments, default => $dailyPieSegments };
                    $barData = match($currentPeriod) { 'semaine' => $weeklyRevenusSegments, 'mois' => $monthlyRevenusSegments, 'année' => $yearlyRevenusSegments, default => $dailyRevenusSegments };
                    $maxRev = match($currentPeriod) { 'semaine' => $weeklyRevenuMax, 'mois' => $monthlyRevenuMax, 'année' => $yearlyRevenuMax, default => $dailyRevenuMax };
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Graphique Circulaire -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h5 class="text-xs font-black text-gray-400 uppercase mb-6 text-center tracking-widest">Répartition des entrées</h5>
                        <div class="flex flex-col items-center">
                            <svg viewBox="0 0 320 200" class="w-full max-w-[300px] h-64">
                                <circle cx="100" cy="100" r="80" fill="none" stroke="#f3f4f6" stroke-width="25" />
                                @foreach ($pieData as $s)
                                    <circle cx="100" cy="100" r="80" fill="none" stroke="{{ $s['color'] }}" stroke-width="25" stroke-dasharray="{{ $s['dasharray'] }}" stroke-dashoffset="{{ $s['dashoffset'] }}" stroke-linecap="butt" />
                                @endforeach
                                @php $yL = 30; @endphp
                                @foreach ($pieData as $s)
                                    <rect x="200" y="{{ $yL }}" width="10" height="10" fill="{{ $s['color'] }}" rx="2" />
                                    <text x="215" y="{{ $yL + 9 }}" font-size="10" font-weight="bold" fill="#6b7280">{{ $s['label'] }} ({{ $s['percent'] }}%)</text>
                                    @php $yL += 25; @endphp
                                @endforeach
                            </svg>
                        </div>
                    </div>

                   <!-- Graphique à barres : Analyse des revenus -->
<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <!-- Titre du graphique -->
    <h5 class="text-xs font-black text-gray-400 uppercase mb-10 text-center tracking-widest">
        Analyse des revenus
    </h5>

    <!-- Zone du graphique -->
    <div class="relative h-64 flex items-end justify-around px-2 border-b border-gray-100">
        @foreach ($barData as $bar)
            @php 
                $h = $maxRev > 0 ? round(($bar['revenu'] / $maxRev) * 100) : 0; 
                $barColor = $bar['color'] ?? '#10b981'; 
            @endphp
            
            <div class="flex flex-col items-center w-full group relative">
                <!-- La Barre -->
                <!-- max-w-[32px] empêche les barres d'être trop larges sur grand écran -->
                <div class="w-full max-w-[32px] sm:max-w-[45px] rounded-t-lg transition-all duration-500 relative group-hover:brightness-95 cursor-pointer shadow-sm" 
                     style="height: {{ max($h, 4) }}%; background-color: {{ $barColor }};">
                    
                    <!-- Tooltip (Bulle d'info au survol) -->
                    <div class="absolute -top-12 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[10px] px-2.5 py-1.5 rounded-md opacity-0 group-hover:opacity-100 transition-all duration-300 whitespace-nowrap z-30 font-black shadow-xl scale-95 group-hover:scale-100 pointer-events-none">
                        {{ number_format($bar['revenu'], 0, '', ' ') }} 
                        <span class="text-[8px] text-gray-400">FCFA</span>
                        <!-- Petite flèche en bas de la bulle -->
                        <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-900 rotate-45"></div>
                    </div>
                </div>
                
                <!-- Label de l'engin (ex: MOTO, VOITURE) -->
                <!-- Positionné de manière absolue pour ne pas décaler les barres -->
                <div class="absolute top-2 w-max">
                    <span class="inline-block text-[10px] font-black text-gray-500 rotate-45 origin-left uppercase tracking-tighter mt-1">
                        {{ $bar['label'] }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Espace de sécurité pour les labels inclinés (évite qu'ils soient coupés) -->
    <div class="h-16"></div>
</div>      </div>

            </div>
        </div>

        <div class="mt-12 text-center">
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.4em]">Parking Pro v2.0 - Dashboard d'Analyse</p>
        </div>
    </div>
</div>

<style>
    /* Amélioration de la fluidité sur mobile */
    @media (max-width: 640px) {
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection