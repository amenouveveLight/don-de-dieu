@extends('layouts.app')

@section('content')
<div class="pt-28 md:pt-28 w-full bg-gray-50 min-h-screen">
<div class="max-w-6xl mx-auto px-2 sm:px-6 lg:px-8 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                Historique — {{ $plaque ?? $uuid }}
            </h1>
            <p class="text-sm text-gray-500">
                {{ $model_type }} · {{ $uuid }}
            </p>
        </div>

        <a href="{{ route('audit.index') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg text-sm">
            ← Retour au journal
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Champ</th>
                        @foreach($events as $ev)
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">
                                {{ $ev['label'] }}
                                <div class="font-normal normal-case text-gray-400 mt-1">
                                    {{ $ev['date'] }}<br>{{ $ev['user'] }}
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($fields as $field)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-700 whitespace-nowrap">{{ $field }}</td>
                            @foreach($events as $ev)
                                @php
                                    $value = $ev['values'][$field] ?? null;
                                    $wasChanged = in_array($field, $ev['changed']);
                                @endphp
                                <td class="px-4 py-3 whitespace-nowrap {{ $wasChanged ? 'bg-yellow-50 text-yellow-800 font-bold' : 'text-gray-400' }}">
                                    {{ $value === null || $value === '' ? '—' : $value }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>
@endsection