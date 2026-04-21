@extends('layouts.app')

@section('content')

<div class="pt-24 md:pt-32 w-full bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        
        <!-- Bouton Retour -->
        <div class="mb-6">
            <a href="{{ route('utilisateurs') }}" class="inline-flex items-center text-xs font-bold text-gray-500 hover:text-green-600 transition uppercase tracking-widest">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la liste
            </a>
        </div>

        <!-- Carte Principale (Style Dashboard) -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative z-10">
            
            <!-- En-tête de la carte -->
            <div class="bg-white border-b border-gray-100 p-5 sm:p-6 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Gestion des utilisateurs</h2>
                        <p class="text-xs sm:text-sm text-gray-400 font-medium">Créer un nouveau compte pour le personnel</p>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-10">
                <!-- Message de Succès -->
                @if (session('success'))
                    <div class="mb-8 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm flex justify-between items-center">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="font-bold text-xs uppercase tracking-wider">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-800 font-bold">✕</button>
                    </div>
                @endif

                <form action="{{ route('utilisateurs.store') }}" method="POST" class="space-y-10">
                    @csrf

                    <!-- Section 1 : Infos Personnelles -->
                    <div class="animate-fadeIn">
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-6 flex items-center">
                            <span class="w-8 h-px bg-gray-200 mr-3"></span> Informations personnelles
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Prénom *</label>
                                <input type="text" name="firstname" value="{{ old('firstname') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:bg-white transition-all text-sm font-semibold text-gray-700">
                                @error('firstname') <p class="text-[10px] text-red-600 mt-1 font-bold uppercase tracking-wide">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Nom *</label>
                                <input type="text" name="lastname" value="{{ old('lastname') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:bg-white transition-all text-sm font-semibold text-gray-700">
                                @error('lastname') <p class="text-[10px] text-red-600 mt-1 font-bold uppercase tracking-wide">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2 : Connexion -->
                    <div>
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-6 flex items-center">
                            <span class="w-8 h-px bg-gray-200 mr-3"></span> Sécurité & Connexion
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Adresse Email *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:bg-white transition-all text-sm font-semibold text-gray-700">
                                @error('email') <p class="text-[10px] text-red-600 mt-1 font-bold uppercase tracking-wide">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Mot de passe *</label>
                                <input type="password" name="password" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 focus:bg-white transition-all text-sm font-semibold text-gray-700">
                                @error('password') <p class="text-[10px] text-red-600 mt-1 font-bold uppercase tracking-wide">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 3 : Rôle -->
                    <div>
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-6 flex items-center">
                            <span class="w-8 h-px bg-gray-200 mr-3"></span> Attribution du Rôle
                        </h3>

                        <div class="max-w-md">
                            <select name="role" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-green-500 text-sm font-bold text-gray-700 cursor-pointer">
                                <option value="" disabled selected>— Sélectionner un rôle —</option>
                                <option value="admin">Administrateur</option>
                                <option value="agent">Agent</option>
                                <option value="user">Gérant</option>
                            </select>
                            @error('role') <p class="text-[10px] text-red-600 mt-1 font-bold uppercase tracking-wide">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Actions finales -->
                    <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-8 border-t border-gray-100">
                        <a href="{{ route('utilisateurs') }}" 
                            class="w-full sm:w-auto px-8 py-3 bg-gray-100 text-gray-500 font-bold rounded-lg hover:bg-gray-200 transition-all text-xs uppercase tracking-widest text-center">
                            Annuler
                        </a>

                        <button type="submit" 
                            class="w-full sm:w-auto px-10 py-4 bg-green-600 text-white font-bold rounded-lg shadow-md hover:bg-green-700 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center space-x-2 text-xs uppercase tracking-widest">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Créer l’utilisateur</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <div class="mt-8 text-center">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Sécurité du personnel - Parking Pro</p>
        </div>
    </div>
</div>

<style>
    .animate-fadeIn { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

@endsection