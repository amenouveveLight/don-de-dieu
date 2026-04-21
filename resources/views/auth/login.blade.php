@extends('layouts.app')

@section('content')
<div class="pt-24 md:pt-28 w-full bg-gray-50 min-h-screen flex flex-col justify-center pb-12 px-4 sm:px-6 lg:px-8">
    
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        
        <!-- Carte Principale (Style Dashboard) -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative z-10 transform transition-all">
            
            <!-- En-tête de la carte -->
            <div class="bg-white border-b border-gray-100 p-6 sm:p-8 text-center">
                <div class="mx-auto bg-green-100 p-3 rounded-full w-16 h-16 flex items-center justify-center mb-4 border border-green-200">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Espace Sécurisé</h2>
                <p class="text-[10px] sm:text-xs text-gray-400 font-bold uppercase tracking-wider mt-2">Connectez-vous à votre compte</p>
            </div>

            <!-- Contenu du formulaire -->
            <div class="p-6 sm:p-8">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Champ Email -->
                    <div>
                        <label for="email" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Adresse Email</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900 text-sm transition-colors bg-gray-50 focus:bg-white outline-none"
                                placeholder="votre@email.com">
                        </div>
                    </div>

                    <!-- Champ Mot de passe -->
                    <div>
                        <label for="password" class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-2">Mot de passe</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-gray-900 text-sm transition-colors bg-gray-50 focus:bg-white outline-none"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Options (Se souvenir / Oublié) -->
                    <div class="flex items-center justify-between pt-2">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox"
                                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded cursor-pointer transition-colors">
                            <label for="remember" class="ml-2 block text-xs font-bold text-gray-600 cursor-pointer">
                                Se souvenir de moi
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-green-600 hover:text-green-800 transition-colors uppercase tracking-wider">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>

                    <!-- Bouton Connexion -->
                    <div class="pt-4 border-t border-gray-100">
                        <button type="submit"
                            class="w-full flex justify-center items-center space-x-2 bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-4 rounded-lg shadow-md transition-all transform hover:-translate-y-1 active:scale-95 uppercase text-xs tracking-widest">
                            <span>Se connecter</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Note footer -->
        <div class="mt-8 text-center">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Accès réservé au personnel autorisé</p>
        </div>

    </div>
</div>
@endsection