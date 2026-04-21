@extends('layouts.app')

@section('content')

<div class="pt-28 md:pt-28 w-full bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        
        <!-- En-tête de la page -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div class="flex items-center space-x-3">
                <div class="bg-yellow-100 p-2 rounded-lg">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Gestion des tarifs</h1>
                    <p class="text-sm text-gray-500 mt-1">Configurez les prix unitaires par type de véhicule pour la facturation.</p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm flex items-center">
                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-bold uppercase text-xs tracking-wider">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Formulaire de tarifs (Style Dashboard) -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative z-10">
            <div class="p-6 sm:p-10">
                <form method="POST" action="{{ route('tarifs.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @php
                            $types = config('vehicle_types');
                        @endphp

                        @foreach ($types as $key => $label)
                            <div class="group">
                                <label for="{{ $key }}-price" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 group-hover:text-green-600 transition-colors">
                                    {{ $label }}
                                </label>
                                <div class="relative rounded-lg shadow-sm">
                                    <!-- Symbole Devise -->
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-gray-400 font-bold text-xs uppercase">FCFA</span>
                                    </div>
                                    
                                    <input
                                        type="number"
                                        name="tarifs[{{ $key }}]"
                                        id="{{ $key }}-price"
                                        class="block w-full pl-16 pr-16 py-4 border border-gray-300 rounded-xl bg-gray-50 text-gray-800 font-extrabold text-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                        placeholder="0"
                                        value="{{ old('tarifs.' . $key, $tarifs[$key] ?? '') }}"
                                        required
                                    >
                                    
                                    <!-- Unité de temps -->
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <span class="text-gray-400 font-bold text-[10px] uppercase">/ Jour</span>
                                    </div>
                                </div>
                                @error('tarifs.' . $key)
                                    <p class="mt-2 text-[10px] text-red-600 font-bold uppercase tracking-wide">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    <!-- Actions de bas de page -->
                    <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-4">
                        <button type="reset" class="w-full sm:w-auto px-8 py-3 bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-gray-200 transition-all text-xs uppercase tracking-widest">
                            Réinitialiser
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-green-600 text-white font-bold rounded-lg shadow-md hover:bg-green-700 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center space-x-2 text-xs uppercase tracking-widest">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Enregistrer les tarifs</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-8 text-center">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Configuration Financière - Parking Pro</p>
        </div>
    </div>
</div>

@endsection