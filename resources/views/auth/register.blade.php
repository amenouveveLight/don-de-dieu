@extends('layouts.app')

@section('content')
<div class="pt-24 md:pt-28 w-full bg-gray-50 min-h-screen flex flex-col justify-center pb-12 px-4 sm:px-6 lg:px-8">
    
    <!-- Largeur ajustée (max-w-2xl) pour accommoder les 2 colonnes sur grand écran -->
    <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
        
        <!-- Carte Principale (Style Dashboard) -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative z-10 transform transition-all">
            
            <!-- En-tête de la carte -->
            <div class="bg-white border-b border-gray-100 p-6 sm:p-8 text-center">
                <div class="mx-auto bg-green-100 p-3 rounded-full w-16 h-16 flex items-center justify-center mb-4 border border-green-200">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Créer un compte professionnel</h2>
                <p class="text-[10px] sm:text-xs text-gray-400 font-bold uppercase tracking-wider mt-2">Accès réservé aux administrateurs, gérants et agents</p>
            </div>

            <!-- Affichage des erreurs de validation avec un beau design -->
            @if ($errors->any())
                <div class="mx-6 sm:mx-8 mt-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-xs font-bold text-red-800 uppercase tracking-wider mb-1">Erreurs lors de l'inscription</h3>
                            <ul class="list-disc pl-5 text-xs text-red-700 space-y-1 font-medium">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Contenu du formulaire -->
            <div class="p-6 sm:p-8">
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Ligne Prénom et Nom (Grid 2 colonnes sur Tablette/PC) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Champ Prénom -->
                        <div>
                            <label for="firstname" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Prénom</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900 text-sm transition-colors bg-gray-50 focus:bg-white outline-none"
                                    placeholder="Ex: Kodzo">
                            </div>
                        </div>

                        <!-- Champ Nom -->
                        <div>
                            <label for="lastname" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Nom</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                </div>
                                <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900 text-sm transition-colors bg-gray-50 focus:bg-white outline-none"
                                    placeholder="Ex: YAYA">
                            </div>
                        </div>
                    </div>

                    <!-- Ligne Email et Rôle (Grid 2 colonnes sur Tablette/PC) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Champ Email -->
                        <div>
                            <label for="email" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Adresse Email</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900 text-sm transition-colors bg-gray-50 focus:bg-white outline-none"
                                    placeholder="votre@email.com">
                            </div>
                        </div>

                        <!-- Champ Rôle -->
                        <div>
                            <label for="role" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Rôle / Fonction</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <select id="role" name="role" required
                                    class="block w-full pl-10 pr-10 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900 text-sm transition-colors bg-gray-50 focus:bg-white outline-none appearance-none">
                                    <option value="" disabled selected>Sélectionnez un rôle</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                    <option value="gerant" {{ old('role') == 'gerant' ? 'selected' : '' }}>Gérant</option>
                                    <option value="agent" {{ old('role') == 'agent' ? 'selected' : '' }}>Agent de parking</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 20 20" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l3-3 3 3m0 6l-3 3-3-3" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ligne Mots de passe (Grid 2 colonnes sur Tablette/PC) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Champ Mot de passe -->
                        <div>
                            <label for="password" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Mot de passe</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input type="password" id="password" name="password" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900 text-sm transition-colors bg-gray-50 focus:bg-white outline-none"
                                    placeholder="••••••••">
                            </div>
                        </div>

                        <!-- Champ Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Confirmer le mot de passe</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900 text-sm transition-colors bg-gray-50 focus:bg-white outline-none"
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <!-- Conditions d'utilisation -->
                    <div class="flex items-start pt-2">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required
                                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded cursor-pointer transition-colors">
                        </div>
                        <div class="ml-3 text-xs">
                            <label for="terms" class="font-bold text-gray-600 cursor-pointer">
                                J'accepte les <a href="#" class="text-green-600 hover:text-green-800 transition-colors uppercase tracking-wider underline">conditions d'utilisation</a>
                            </label>
                        </div>
                    </div>

                    <!-- Bouton Créer Compte -->
                    <div class="pt-6 border-t border-gray-100 mt-6">
                        <button type="submit"
                            class="w-full flex justify-center items-center space-x-2 bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-4 rounded-lg shadow-md transition-all transform hover:-translate-y-1 active:scale-95 uppercase text-xs tracking-widest">
                            <span>Créer le compte</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Lien vers connexion -->
                <div class="mt-8 text-center bg-gray-50 -mx-6 sm:-mx-8 -mb-6 sm:-mb-8 p-6 sm:p-8 rounded-b-xl border-t border-gray-100">
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                        Déjà inscrit ? 
                        <a href="{{ route('login') }}" class="text-green-600 hover:text-green-800 transition-colors ml-1">
                            Se connecter ici
                        </a>
                    </p>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection