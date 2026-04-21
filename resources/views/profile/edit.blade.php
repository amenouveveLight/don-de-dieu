@extends('layouts.app')

@section('content')
<div class="pt-24 md:pt-32 w-full bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        
        <!-- En-tête du profil -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8 relative z-10 flex flex-col md:flex-row items-center gap-6">
            <div class="relative">
                <div class="h-24 w-24 rounded-full bg-green-100 flex items-center justify-center text-green-700 text-3xl font-extrabold border-4 border-white shadow-sm">
                    {{ substr(Auth::user()->firstname, 0, 1) }}{{ substr(Auth::user()->lastname, 0, 1) }}
                </div>
                <div class="absolute bottom-0 right-0 h-6 w-6 bg-green-500 border-2 border-white rounded-full"></div>
            </div>
            
            <div class="text-center md:text-left flex-grow">
                <h1 class="text-2xl font-bold text-gray-800">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h1>
                <p class="text-sm text-gray-500 font-medium">{{ Auth::user()->email }}</p>
                <div class="mt-2">
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-full uppercase border border-blue-100 tracking-wider">
                        Rôle : {{ Auth::user()->role ?? 'Utilisateur' }}
                    </span>
                </div>
            </div>

            <div class="text-xs text-gray-400 font-bold uppercase tracking-tighter italic">
                Membre depuis {{ Auth::user()->created_at->format('M Y') }}
            </div>
        </div>

        <!-- Alertes de statut -->
        @if (session('status') === 'profile-updated' || session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm flex items-center animate-fadeIn">
                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="font-bold text-xs uppercase tracking-widest">Profil mis à jour avec succès</span>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8">
            
            <!-- SECTION 1 : INFORMATIONS PERSONNELLES -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative z-10">
                <div class="bg-white border-b border-gray-50 p-5">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-widest flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informations personnelles
                    </h3>
                </div>
                
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Prénom & Nom</label>
                                <input type="text" name="name" value="{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 font-semibold text-gray-700 transition"
                                    placeholder="Nom complet">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Adresse Email</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-green-500 font-semibold text-gray-700 transition"
                                    placeholder="Email">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-green-700 shadow-md transition transform hover:-translate-y-0.5 active:scale-95">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SECTION 2 : SÉCURITÉ DU COMPTE -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden relative z-10">
                <div class="bg-white border-b border-gray-50 p-5">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-widest flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Sécurité du compte
                    </h3>
                </div>
                
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="max-w-md space-y-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Mot de passe actuel</label>
                                <input type="password" name="current_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 text-sm text-gray-700" transition placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nouveau mot de passe</label>
                                <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 text-sm text-gray-700 transition" placeholder="Minimum 8 caractères">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 text-sm  text-gray-700 transition"  transition placeholder="Répéter le mot de passe">
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="bg-gray-800 text-white px-8 py-3 rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-black shadow-md transition transform hover:-translate-y-0.5 active:scale-95">
                                Modifier le mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SECTION 3 : DANGER ZONE -->
            <div class="bg-red-50 rounded-xl border border-red-100 p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <h3 class="text-sm font-bold text-red-700 uppercase tracking-widest mb-1">Zone de danger</h3>
                    <p class="text-xs text-red-500">Une fois votre compte supprimé, toutes vos données seront définitivement perdues.</p>
                </div>
                
                <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Attention : Cette action est irréversible. Supprimer définitivement votre compte ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="whitespace-nowrap px-6 py-3 border-2 border-red-600 text-red-600 rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-red-600 hover:text-white transition">
                      Supprimer mon compte
                    </button>
                </form>
            </div>

        </div>

        <div class="mt-12 text-center">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.3em]">Gestion de compte sécurisée - Parking Pro</p>
        </div>
    </div>
</div>

<style>
    .animate-fadeIn { animation: fadeIn 0.5s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection