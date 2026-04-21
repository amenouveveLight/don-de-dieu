@extends('layouts.app')

@section('content')

<div class="pt-20 md:pt-28 bg-gray-50 min-h-screen">
    
    <!-- 1. HERO SECTION -->
    <section class="relative bg-green-700 text-white overflow-hidden">
        <!-- Motif de fond discret -->
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)" />
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-6 md:px-10 py-16 md:py-24 grid md:grid-cols-2 gap-12 items-center relative z-10"> 
            <!-- Gauche: Contenu -->
            <div class="animate-fadeIn">
                <span class="inline-block px-3 py-1 rounded-full bg-green-600 text-green-100 text-xs font-bold uppercase tracking-widest mb-4">
                    Système de Gestion Intelligent
                </span>
                <h1 class="text-4xl md:text-6xl font-extrabold leading-tight tracking-tight">
                    Bienvenue sur <span class="text-green-300">Solution Digital</span>
                </h1>
                <p class="mt-6 text-lg md:text-xl text-green-100 leading-relaxed max-w-xl">
                    « Gérez le parking de votre marché en toute simplicité. »
                    Une solution claire et efficace pour organiser, sécuriser et rentabiliser vos espaces.
                </p>

                <!-- Boutons d'action rapide -->
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-white text-green-700 font-bold rounded-xl shadow-lg hover:bg-green-50 transition transform hover:-translate-y-1 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        Tableau de Bord
                    </a>
                    <a href="#features" class="px-8 py-4 bg-green-600 text-white font-bold rounded-xl border border-green-500 hover:bg-green-500 transition">
                        En savoir plus
                    </a>
                </div>

                <!-- Social Proof -->
                <div class="mt-12 flex items-center">
                    <div class="flex -space-x-3 mr-4">
                        <img class="h-10 w-10 rounded-full border-2 border-green-700 bg-gray-200" src="https://api.dicebear.com/7.x/personas/svg?seed=Felix" alt="User 1">
                        <img class="h-10 w-10 rounded-full border-2 border-green-700 bg-gray-200" src="https://api.dicebear.com/7.x/personas/svg?seed=Anya" alt="User 2">
                        <img class="h-10 w-10 rounded-full border-2 border-green-700 bg-gray-200" src="https://api.dicebear.com/7.x/personas/svg?seed=Marc" alt="User 3">
                    </div>
                    <p class="text-green-100 text-sm">
                        Déjà adopté par <span class="font-bold text-white">+50 gestionnaires de parking</span>
                    </p>
                </div>
            </div>

            <!-- Droite: Illustration -->
            <div class="hidden md:block">
                <div class="relative">
                    <div class="absolute -inset-4 bg-green-400 opacity-20 blur-2xl rounded-full"></div>
                    <svg class="relative w-full max-w-md mx-auto drop-shadow-2xl" viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg">
                        <rect x="50" y="50" width="300" height="200" fill="#f3f4f6" rx="12" />
                        <g fill="#d1d5db">
                            <rect x="70" y="70" width="80" height="40" rx="6" />
                            <rect x="160" y="70" width="80" height="40" rx="6" />
                            <rect x="250" y="70" width="80" height="40" rx="6" />
                            <rect x="70" y="130" width="80" height="40" rx="6" />
                            <rect x="250" y="130" width="80" height="40" rx="6" />
                        </g>
                        <rect x="160" y="130" width="80" height="40" fill="#22c55e" rx="6" />
                        <rect x="250" y="190" width="80" height="40" fill="#22c55e" rx="6" />
                        <circle cx="30" cy="30" r="20" fill="#22c55e" />
                        <text x="30" y="37" font-size="20" text-anchor="middle" fill="white" font-weight="bold">P</text>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. QUICK ACTIONS SECTION (L'essentiel pour l'agent) -->
    <section class="max-w-7xl mx-auto px-6 py-12 -mt-10 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Action Entrée -->
            <a href="{{ route('entres.create') }}" class="group bg-white p-8 rounded-2xl shadow-xl border border-gray-100 hover:border-green-500 transition-all transform hover:-translate-y-2">
                <div class="w-14 h-14 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-green-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Nouvelle Entrée</h3>
                <p class="text-gray-500 text-sm italic">Enregistrez un véhicule et imprimez le ticket en un clic.</p>
            </a>

            <!-- Action Sortie -->
            <a href="{{ route('sorties.create') }}" class="group bg-white p-8 rounded-2xl shadow-xl border border-gray-100 hover:border-red-500 transition-all transform hover:-translate-y-2">
                <div class="w-14 h-14 bg-red-100 text-red-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-red-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Valider une Sortie</h3>
                <p class="text-gray-500 text-sm italic">Calculez automatiquement le tarif et libérez la place.</p>
            </a>

            <!-- Action Historique -->
            <a href="{{ route('recent') }}" class="group bg-white p-8 rounded-2xl shadow-xl border border-gray-100 hover:border-blue-500 transition-all transform hover:-translate-y-2">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Historique</h3>
                <p class="text-gray-500 text-sm italic">Consultez les mouvements récents et les transactions.</p>
            </a>
        </div>
    </section>

    <!-- 3. FEATURES / VALUES (Pourquoi nous ?) -->
    <section id="features" class="max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800 uppercase tracking-widest text-[12px] mb-4">Pourquoi choisir Solution Digital ?</h2>
            <p class="text-3xl font-extrabold text-gray-900">Optimisez votre gestion financière</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-green-600 font-bold text-4xl mb-2">0%</div>
                <p class="text-gray-900 font-bold">Erreur de calcul</p>
                <p class="text-gray-500 text-xs">Tarification automatique par type d'engin.</p>
            </div>
            <div class="text-center">
                <div class="text-green-600 font-bold text-4xl mb-2">100%</div>
                <p class="text-gray-900 font-bold">Sécurisé</p>
                <p class="text-gray-500 text-xs">Traçabilité complète de chaque transaction.</p>
            </div>
            <div class="text-center">
                <div class="text-green-600 font-bold text-4xl mb-2">24h/7</div>
                <p class="text-gray-900 font-bold">Disponibilité</p>
                <p class="text-gray-500 text-xs">Accédez à vos statistiques partout, tout le temps.</p>
            </div>
            <div class="text-center">
                <div class="text-green-600 font-bold text-4xl mb-2">PDF</div>
                <p class="text-gray-900 font-bold">Rapports Clairs</p>
                <p class="text-gray-500 text-xs">Exportez vos bilans journaliers ou mensuels.</p>
            </div>
        </div>
    </section>

    <!-- 4. FOOTER SIMPLE -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="flex items-center justify-center space-x-2 mb-6">
                <div class="bg-green-600 p-2 rounded-lg font-bold">P</div>
                <span class="text-xl font-bold tracking-tighter">Solution Digital</span>
            </div>
            <p class="text-gray-400 text-sm mb-6">La référence technologique pour la gestion de parking de proximité.</p>
            <div class="text-gray-500 text-[10px] font-bold uppercase tracking-[0.3em]">
                &copy; {{ date('Y') }} Parking Pro - Tous droits réservés.
            </div>
        </div>
    </footer>
</div>

<style>
    .animate-fadeIn { animation: fadeIn 0.8s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

@endsection