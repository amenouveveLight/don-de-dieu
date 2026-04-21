<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Solution Digital - Dashboard</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts : Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
    <!-- Configuration Tailwind CSS intégrée -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans:['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* Vos anciens styles conservés */
        .o-pattern {
            background-color: #16a34a;
            background-image: radial-gradient(circle at 25px 25px, rgba(255, 255, 255, 0.2) 2%, transparent 0%), 
                             radial-gradient(circle at 75px 75px, rgba(255, 255, 255, 0.2) 2%, transparent 0%);
            background-size: 100px 100px;
        }
        .parking-spot { transition: all 0.3s ease; }
        .parking-spot:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .dashboard-card { transition: all 0.2s ease; }
        .dashboard-card:hover { transform: translateY(-3px); }
        .status-available { background-color: #22c55e; }
        .status-occupied { background-color: #ef4444; }
        .status-reserved { background-color: #f59e0b; }
        .status-maintenance { background-color: #6b7280; }
        .form-container { transition: all 0.3s ease; }
        .form-container:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
        
        /* Styles des onglets (Tabs) */
        .tab-link {
            color: #16a34a; 
            border-color: transparent;
            transition: 0.3s;
        }
        .tab-link:hover { border-color: #16a34a; }
        .tab-content { display: none; }
        .tab-content:target { display: block; }
        :target ~ .tabs a[href="#tab-daily"], :target ~ .tabs a[href="#tab-weekly"],
        :target ~ .tabs a[href="#tab-monthly"], :target ~ .tabs a[href="#tab-yearly"] { border-color: transparent; }
        :target ~ .tabs a[href="#tab-daily"]:target, :target ~ .tabs a[href="#tab-weekly"]:target,
        :target ~ .tabs a[href="#tab-monthly"]:target, :target ~ .tabs a[href="#tab-yearly"]:target { border-color: #16a34a; }
        body:not(:has(:target)) #tab-daily { display: block; }
        body:not(:has(:target)) .tabs a[href="#tab-daily"] { border-color: #16a34a; }
        
        /* Menu Mobile animations */
        #menu-toggle:checked ~ #mobile-menu { opacity: 1; visibility: visible; transform: scale(1); }
        #menu-toggle:checked ~ label .hamburger-icon { display: none; }
        #menu-toggle:checked ~ label .close-icon { display: block; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- Navigation Header -->
    <nav class="bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100 fixed top-0 left-0 w-full z-50 transition-all">
        <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-3 max-w-7xl mx-auto">
            
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-2">
                <a href="{{ url('/') }}" class="flex items-center space-x-2">
                    <img class="h-12 sm:h-14 w-auto transform transition hover:scale-105" src="{{ asset('images/T.png') }}" alt="Solution Digital">
                </a>
            </div>

            <!-- Bouton Hamburger (Mobile/Tablette) -->
            <div class="md:hidden flex items-center">
                <input type="checkbox" id="menu-toggle" class="hidden" />
                <label for="menu-toggle" class="cursor-pointer z-[60] relative p-2 bg-gray-50 rounded-lg border border-gray-100 text-gray-600 hover:text-green-600 hover:bg-green-50 transition-colors">
                    <!-- Icone Hamburger -->
                    <svg class="w-6 h-6 hamburger-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Icone Croix (Fermer) -->
                    <svg class="w-6 h-6 close-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </label>

                <!-- Fullscreen Overlay Mobile Menu (CORRIGÉ ICI : justify-start, pt-28, space-y-5) -->
                <div id="mobile-menu" class="fixed inset-0 bg-white z-[55] flex flex-col items-center justify-start pt-28 space-y-5 opacity-0 invisible scale-95 transition-all duration-300 ease-in-out pb-20 overflow-y-auto">
                    
                    @auth
                        <!-- Rôle Agent -->
                        @if(Auth::user()->isAgent())
                            <a href="{{ url('/entres') }}" class="text-xl font-bold text-gray-800 hover:text-green-600 uppercase tracking-widest">Entrées</a>
                            <a href="{{ url('/sorties') }}" class="text-xl font-bold text-gray-800 hover:text-green-600 uppercase tracking-widest">Sorties</a>
                            <a href="{{ url('/recent') }}" class="text-xl font-bold text-gray-800 hover:text-green-600 uppercase tracking-widest">Activités</a>
                            <a href="{{ url('/dashboard') }}" class="text-xl font-bold text-gray-800 hover:text-green-600 uppercase tracking-widest">Rapports</a>
                        @endif

                        <!-- Rôle Gérant -->
                        @if(Auth::user()->isGerant())
                            <a href="{{ url('/entres') }}" class="text-xl font-bold text-gray-800 hover:text-green-600 uppercase tracking-widest">Entrées</a>
                            <a href="{{ url('/sorties') }}" class="text-xl font-bold text-gray-800 hover:text-green-600 uppercase tracking-widest">Sorties</a>
                            <a href="{{ url('/recent') }}" class="text-xl font-bold text-gray-800 hover:text-green-600 uppercase tracking-widest">Activités</a>
                            <a href="{{ url('/tarifs') }}" class="text-xl font-bold text-gray-800 hover:text-green-600 uppercase tracking-widest">Tarifs</a>
                            <a href="{{ url('/dashboard') }}" class="text-xl font-bold text-gray-800 hover:text-green-600 uppercase tracking-widest">Rapports</a>
                        @endif

                        <!-- Rôle Admin -->
                        @if(Auth::user()->isAdmin())
                            <a href="{{ url('/utilisateurs') }}" class="text-xl font-bold text-gray-800 hover:text-green-600 uppercase tracking-widest">Utilisateurs</a>
                            <a href="{{ url('/tarifs') }}" class="text-xl font-bold text-gray-800 hover:text-green-600 uppercase tracking-widest">Tarifs</a>
                            <a href="{{ url('/recent') }}" class="text-xl font-bold text-gray-800 hover:text-green-600 uppercase tracking-widest">Activités</a>
                            <a href="{{ url('/dashboard') }}" class="text-xl font-bold text-gray-800 hover:text-green-600 uppercase tracking-widest">Rapports</a>
                            <a href="{{ url('/statsagent') }}" class="text-xl font-bold text-gray-800 hover:text-green-600 uppercase tracking-widest">Stats Agent</a>
                        @endif

                        <!-- Options de profil mobile -->
                        <div class="pt-6 mt-2 border-t border-gray-100 w-full max-w-xs flex flex-col items-center gap-4">
                            <div class="bg-green-50 text-green-700 px-6 py-2 rounded-full font-bold text-sm uppercase tracking-widest border border-green-100 mb-2">
                                {{ Auth::user()->firstname }}
                            </div>
                            <a href="{{ route('profile.edit') }}" class="text-sm font-bold text-gray-500 hover:text-gray-900 uppercase tracking-wider">Mon Profil</a>
                            <form method="POST" action="{{ route('logout') }}" class="w-full text-center">
                                @csrf
                                <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700 uppercase tracking-wider">Déconnexion</button>
                            </form>
                        </div>
                    @endauth

                    @guest
                        <div class="flex flex-col w-full max-w-xs gap-4 px-6 pt-4">
                            <a href="{{ route('login') }}" class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-4 rounded-lg uppercase tracking-widest text-sm transition-colors">
                                Connexion
                            </a>
                            <a href="{{ route('register') }}" class="w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-lg uppercase tracking-widest text-sm shadow-md transition-all">
                                S'inscrire
                            </a>
                        </div>
                    @endguest
                </div>
            </div>

            <!-- Menu Desktop (Liens Centrés) -->
            <div class="hidden md:flex flex-1 justify-center space-x-8">
                @auth
                    <!-- Agent -->
                    @if(Auth::user()->isAgent())
                        <a href="{{ url('/entres') }}" class="text-[11px] font-bold text-gray-500 hover:text-green-600 uppercase tracking-widest transition-colors py-2 border-b-2 border-transparent hover:border-green-600">Entrées</a>
                        <a href="{{ url('/sorties') }}" class="text-[11px] font-bold text-gray-500 hover:text-green-600 uppercase tracking-widest transition-colors py-2 border-b-2 border-transparent hover:border-green-600">Sorties</a>
                        <a href="{{ url('/recent') }}" class="text-[11px] font-bold text-gray-500 hover:text-green-600 uppercase tracking-widest transition-colors py-2 border-b-2 border-transparent hover:border-green-600">Activités</a>
                        <a href="{{ url('/dashboard') }}" class="text-[11px] font-bold text-gray-500 hover:text-green-600 uppercase tracking-widest transition-colors py-2 border-b-2 border-transparent hover:border-green-600">Rapports</a>
                    @endif

                    <!-- Gérant -->
                    @if(Auth::user()->isGerant())
                        <a href="{{ url('/entres') }}" class="text-[11px] font-bold text-gray-500 hover:text-green-600 uppercase tracking-widest transition-colors py-2 border-b-2 border-transparent hover:border-green-600">Entrées</a>
                        <a href="{{ url('/sorties') }}" class="text-[11px] font-bold text-gray-500 hover:text-green-600 uppercase tracking-widest transition-colors py-2 border-b-2 border-transparent hover:border-green-600">Sorties</a>
                        <a href="{{ url('/recent') }}" class="text-[11px] font-bold text-gray-500 hover:text-green-600 uppercase tracking-widest transition-colors py-2 border-b-2 border-transparent hover:border-green-600">Activités</a>
                        <a href="{{ url('/tarifs') }}" class="text-[11px] font-bold text-gray-500 hover:text-green-600 uppercase tracking-widest transition-colors py-2 border-b-2 border-transparent hover:border-green-600">Tarifs</a>
                        <a href="{{ url('/dashboard') }}" class="text-[11px] font-bold text-gray-500 hover:text-green-600 uppercase tracking-widest transition-colors py-2 border-b-2 border-transparent hover:border-green-600">Rapports</a>
                    @endif

                    <!-- Admin -->
                    @if(Auth::user()->isAdmin())
                        <a href="{{ url('/utilisateurs') }}" class="text-[11px] font-bold text-gray-500 hover:text-green-600 uppercase tracking-widest transition-colors py-2 border-b-2 border-transparent hover:border-green-600">Utilisateurs</a>
                        <a href="{{ url('/tarifs') }}" class="text-[11px] font-bold text-gray-500 hover:text-green-600 uppercase tracking-widest transition-colors py-2 border-b-2 border-transparent hover:border-green-600">Tarifs</a>
                        <a href="{{ url('/recent') }}" class="text-[11px] font-bold text-gray-500 hover:text-green-600 uppercase tracking-widest transition-colors py-2 border-b-2 border-transparent hover:border-green-600">Activités</a>
                        <a href="{{ url('/dashboard') }}" class="text-[11px] font-bold text-gray-500 hover:text-green-600 uppercase tracking-widest transition-colors py-2 border-b-2 border-transparent hover:border-green-600">Rapports</a>
                        <a href="{{ url('/statsagent') }}" class="text-[11px] font-bold text-gray-500 hover:text-green-600 uppercase tracking-widest transition-colors py-2 border-b-2 border-transparent hover:border-green-600">Stats Agent</a>
                    @endif
                @endauth
            </div>

            <!-- Boutons de Droite (Auth Desktop) -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <div class="relative group">
                        <!-- Bouton Profil (Déroulant) -->
                        <button class="flex items-center space-x-2 bg-gray-50 border border-gray-200 px-4 py-2 rounded-lg text-gray-700 hover:text-green-600 hover:bg-green-50 hover:border-green-200 transition-all cursor-pointer">
                            <div class="bg-green-100 text-green-600 p-1 rounded-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider">{{ Auth::user()->firstname }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        
                        <!-- Menu Déroulant Profil -->
                        <div class="absolute right-0 mt-2 w-56 bg-white border border-gray-100 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-1 transform transition-all duration-200 z-50 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Connecté en tant que</p>
                                <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <ul class="py-2">
                                <li>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 px-4 py-3 text-[11px] font-bold text-gray-600 hover:text-green-600 hover:bg-green-50 uppercase tracking-widest transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        <span>Mon Profil</span>
                                    </a>
                                </li>
                                <li class="border-t border-gray-100 mt-1 pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-3 text-[11px] font-bold text-red-500 hover:text-red-700 hover:bg-red-50 uppercase tracking-widest transition-colors flex items-center space-x-2">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                            <span>Déconnexion</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-[11px] font-bold text-gray-500 hover:text-gray-800 uppercase tracking-widest transition-colors bg-white border border-gray-200 hover:bg-gray-50 px-4 py-2.5 rounded-lg shadow-sm">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="text-[11px] font-bold text-white bg-green-600 hover:bg-green-700 uppercase tracking-widest transition-all px-5 py-2.5 rounded-lg shadow-md hover:-translate-y-0.5 active:scale-95">
                        S'inscrire
                    </a>
                @endguest
            </div>

        </div>
    </nav>

    
