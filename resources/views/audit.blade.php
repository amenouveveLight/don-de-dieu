@extends('layouts.app')

@section('content')

<div class="pt-28 md:pt-28 w-full bg-gray-50 min-h-screen">

```
<div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-8">

    {{-- =========================
         EN-TÊTE
    ========================== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">

        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                Journal d'audit
            </h1>

            <p class="text-sm text-gray-500">
                Consultez et filtrez toutes les actions effectuées sur le système.
            </p>
        </div>

        {{-- Indicateur --}}
        <div class="bg-white p-2 rounded-lg shadow-sm border border-gray-100 inline-flex items-center">

            <span class="flex h-3 w-3 relative mr-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>

            <span class="text-xs font-bold text-gray-600 uppercase tracking-widest">
                Journal actif
            </span>

        </div>

    </div>


    {{-- =========================
         SECTION FILTRES
    ========================== --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-5 mb-8">

        <form method="GET" action="{{ route('audit.index') }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">

                {{-- Période --}}
                <div>

                    <label
                        for="periode"
                        class="block text-xs font-bold text-gray-500 uppercase mb-2"
                    >
                        Période
                    </label>

                    <select
                        name="periode"
                        id="periode"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 text-sm font-semibold text-gray-700"
                    >

                        <option value="jour" @selected($periode == 'jour')>
                            Jour
                        </option>

                        <option value="mois" @selected($periode == 'mois')>
                            Mois
                        </option>

                        <option value="annee" @selected($periode == 'annee')>
                            Année
                        </option>

                    </select>

                </div>


                {{-- Date --}}
                <div>

                    <label
                        for="date"
                        class="block text-xs font-bold text-gray-500 uppercase mb-2"
                    >
                        Date
                    </label>

                    <input
                        type="date"
                        name="date"
                        id="date"
                        value="{{ $date }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 text-sm"
                    >

                </div>


                {{-- Plaque --}}
                <div>

                    <label
                        for="plaque"
                        class="block text-xs font-bold text-gray-500 uppercase mb-2"
                    >
                        Plaque
                    </label>

                    <input
                        type="text"
                        name="plaque"
                        id="plaque"
                        value="{{ request('plaque') }}"
                        placeholder="Rechercher..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 text-sm"
                    >

                </div>


                {{-- Utilisateur --}}
                <div>

                    <label
                        for="user_name"
                        class="block text-xs font-bold text-gray-500 uppercase mb-2"
                    >
                        Utilisateur
                    </label>

                    <input
                        type="text"
                        name="user_name"
                        id="user_name"
                        value="{{ request('user_name') }}"
                        placeholder="Rechercher..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 text-sm"
                    >

                </div>


                {{-- Action --}}
                <div>

                    <label
                        for="action"
                        class="block text-xs font-bold text-gray-500 uppercase mb-2"
                    >
                        Action
                    </label>

                    <select
                        name="action"
                        id="action"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 text-sm font-semibold text-gray-700"
                    >

                        <option value="">
                            Toutes les actions
                        </option>

                        <option
                            value="created"
                            @selected(request('action') === 'created')
                        >
                            Création
                        </option>

                        <option
                            value="updated"
                            @selected(request('action') === 'updated')
                        >
                            Modification
                        </option>

                        <option
                            value="deleted"
                            @selected(request('action') === 'deleted')
                        >
                            Suppression
                        </option>

                        <option
                            value="login"
                            @selected(request('action') === 'login')
                        >
                            Connexion
                        </option>

                        <option
                            value="logout"
                            @selected(request('action') === 'logout')
                        >
                            Déconnexion
                        </option>

                    </select>

                </div>

            </div>


            {{-- Boutons --}}
            <div class="flex flex-col sm:flex-row gap-3 mt-5">

                <button
                    type="submit"
                    class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-6 rounded-lg transition-all flex items-center justify-center"
                >
                    FILTRER
                </button>

                <a
                    href="{{ route('audit.index') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded-lg transition-all text-center"
                >
                    RÉINITIALISER
                </a>

            </div>

        </form>

    </div>


    {{-- =========================
         INFORMATIONS PÉRIODE
    ========================== --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">

        <div class="text-sm text-gray-500">

            Période :

            <strong class="text-gray-700">
                {{ $start->format('d/m/Y') }}
            </strong>

            au

            <strong class="text-gray-700">
                {{ $end->format('d/m/Y') }}
            </strong>

        </div>

        <div
            class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold"
        >
            {{ $logs->total() }} action(s)
        </div>

    </div>


    {{-- =========================
         TABLEAU
    ========================== --}}
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-4 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                            Date & Heure
                        </th>

                        <th class="px-4 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                            Utilisateur
                        </th>

                        <th class="px-4 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                            Action
                        </th>

                        <th class="px-4 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                            Type
                        </th>

                        <th class="px-4 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                            Plaque
                        </th>

                        <th class="px-4 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                            Détails
                        </th>

                    </tr>

                </thead>


                <tbody class="bg-white divide-y divide-gray-100">

                    @forelse($logs as $log)

                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- Date --}}
                            <td class="px-4 py-4 whitespace-nowrap">

                                <div class="text-xs font-bold text-gray-900">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </div>

                            </td>


                            {{-- Utilisateur --}}
                            <td class="px-4 py-4 whitespace-nowrap">

                                <div class="text-xs text-gray-700 font-medium">
                                    {{ $log->user_name }}
                                </div>

                                <div class="text-[10px] text-gray-400 uppercase">
                                    {{ $log->user_role ?? 'Rôle inconnu' }}
                                </div>

                            </td>


                            {{-- Action --}}
                            <td class="px-4 py-4 whitespace-nowrap">

                                @switch($log->action)

                                    @case('created')

                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 uppercase">
                                            Création
                                        </span>

                                        @break


                                    @case('updated')

                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 uppercase">
                                            Modification
                                        </span>

                                        @break


                                    @case('deleted')

                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800 uppercase">
                                            Suppression
                                        </span>

                                        @break


                                    @case('login')

                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800 uppercase">
                                            Connexion
                                        </span>

                                        @break


                                    @case('logout')

                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-200 text-gray-800 uppercase">
                                            Déconnexion
                                        </span>

                                        @break


                                    @default

                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700 uppercase">
                                            {{ $log->action }}
                                        </span>

                                @endswitch

                            </td>


                            {{-- Type --}}
                            <td class="px-4 py-4 whitespace-nowrap">

                                <span class="text-[10px] font-semibold text-gray-500">
                                    {{ $log->model_type }}
                                </span>

                            </td>


                            {{-- Plaque --}}
                            <td class="px-4 py-4 whitespace-nowrap">

                                @if($log->plaque)

                                    <div class="inline-block text-sm font-extrabold text-green-700 bg-green-50 px-2 py-1 rounded border border-green-100 uppercase">
                                        {{ $log->plaque }}
                                    </div>

                                @else

                                    <span class="text-xs text-gray-400">
                                        --
                                    </span>

                                @endif

                            </td>
                            {{-- Détails --}}
<td class="px-4 py-4">
    @if($log->model_uuid)
        <a
            href="{{ route('audit.history', $log->model_uuid) }}"
            target="_blank"
            class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition-all"
        >
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            Voir l'historique
        </a>
    @else
        <span class="text-xs text-gray-400 italic">Aucun détail</span>
    @endif
</td>


        

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-10 text-center text-gray-500 italic"
                            >
                                Aucune action enregistrée sur cette période.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =========================
             PAGINATION
        ========================== --}}
        @if($logs->hasPages())

            <div class="bg-gray-50 px-4 py-4 border-t border-gray-100 flex items-center justify-center">

                {{ $logs->appends(request()->query())->links() }}

            </div>

        @endif

    </div>

</div>


</div>

{{-- =========================
JAVASCRIPT
========================== --}}

<script>
async function toggleAuditHistory(logId, uuid) {
    const container = document.getElementById(`audit-details-${logId}`);
    const icon = document.getElementById(`audit-icon-${logId}`);
    const text = document.getElementById(`audit-text-${logId}`);

    const isHidden = container.classList.contains('hidden');

    if (isHidden && container.innerHTML === '') {
        text.textContent = 'Chargement...';

        try {
            const res = await fetch(`/audit/history/${uuid}`);
            const data = await res.json();
            container.innerHTML = buildHistoryTable(data);
        } catch (e) {
            container.innerHTML = '<p class="text-xs text-red-500">Erreur de chargement.</p>';
        }
    }

    container.classList.toggle('hidden');
    icon.classList.toggle('rotate-180', isHidden);
    text.textContent = isHidden ? "Masquer l'historique" : "Voir l'historique";
}

function buildHistoryTable(data) {
    if (!data.fields.length || !data.events.length) {
        return '<p class="text-xs text-gray-400 italic">Aucun historique.</p>';
    }

    let html = '<table class="min-w-full text-xs border border-gray-200 rounded-lg overflow-hidden">';

    // En-tête : une colonne par évènement
    html += '<thead class="bg-gray-50"><tr><th class="px-3 py-2 text-left font-bold text-gray-500 uppercase">Champ</th>';
    data.events.forEach(ev => {
        html += `<th class="px-3 py-2 text-left font-bold text-gray-500 uppercase">
                    ${ev.label}<br><span class="font-normal normal-case text-gray-400">${ev.date}<br>${ev.user}</span>
                 </th>`;
    });
    html += '</tr></thead><tbody class="divide-y divide-gray-100">';

    // Une ligne par champ
    data.fields.forEach(field => {
        html += `<tr><td class="px-3 py-2 font-semibold text-gray-700">${field}</td>`;
        data.events.forEach(ev => {
            const wasChanged = ev.changed.includes(field);
            const value = ev.values[field];
            const display = (value === null || value === undefined || value === '')
                ? '<span class="text-gray-300">—</span>'
                : String(value);

            const cellClass = wasChanged
                ? 'px-3 py-2 bg-yellow-50 text-yellow-800 font-bold'
                : 'px-3 py-2 text-gray-400';

            html += `<td class="${cellClass}">${display}</td>`;
        });
        html += '</tr>';
    });

    html += '</tbody></table>';
    return html;
}
</script>

@endsection
