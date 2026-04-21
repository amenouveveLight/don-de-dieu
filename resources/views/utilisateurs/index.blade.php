@extends('layouts.app')

@section('content')

<div class="pt-28 md:pt-28 w-full bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">

        <!-- En-tête de page -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-100 p-2 rounded-lg">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Gestion des utilisateurs</h1>
                    <p class="text-sm text-gray-500 mt-1">Gérez les accès et les rôles de votre équipe de parking.</p>
                </div>
            </div>
            
            <!-- Bouton Ajouter (Visible sur PC) -->
            <a href="{{ route('utilisateurs.create') }}" class="hidden md:flex items-center px-5 py-2.5 bg-green-600 text-white font-bold rounded-lg shadow-md hover:bg-green-700 transition-all transform hover:-translate-y-0.5 active:scale-95 text-xs uppercase tracking-widest">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Ajouter un utilisateur
            </a>
        </div>

        <!-- Section Contrôles (Recherche & Filtres) -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-4 mb-6 relative z-20">
            <form method="GET" action="{{ route('utilisateurs') }}" id="searchForm" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                
                <!-- Recherche -->
                <div class="md:col-span-7 lg:col-span-8">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-5 w-5 group-focus-within:text-green-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom ou email..." oninput="document.getElementById('searchForm').submit()"
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:bg-white transition-all text-sm font-medium">
                    </div>
                </div>

                <!-- Filtre Rôle -->
                <div class="md:col-span-3 lg:col-span-2">
                    <select name="role" onchange="document.getElementById('searchForm').submit()"
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm font-bold text-gray-700 focus:ring-2 focus:ring-green-500 cursor-pointer">
                        <option value="" {{ request('role') == '' ? 'selected' : '' }}>Tous les rôles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                        <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Gérant</option>
                    </select>
                </div>

                <!-- Bouton Ajouter (Mobile Only) -->
                <div class="md:hidden">
                    <a href="{{ route('utilisateurs.create') }}" class="w-full flex items-center justify-center px-5 py-3 bg-green-600 text-white font-bold rounded-lg shadow-md">
                        AJOUTER UN UTILISATEUR
                    </a>
                </div>
            </form>
        </div>

        <!-- Tableau des Utilisateurs (Style Dashboard) -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">Rôle</th>
                            <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">Dernière connexion</th>
                            <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full border-2 border-gray-100 object-cover shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode($user->firstname . ' ' . $user->lastname) }}&background=random&color=fff" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $user->firstname }} {{ $user->lastname }}</div>
                                        <div class="text-xs text-gray-500 font-medium">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs">
                                @php
                                    $roleClasses = [
                                        'admin' => 'bg-red-50 text-red-700 border-red-100',
                                        'agent' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                        'user'  => 'bg-gray-50 text-gray-700 border-gray-100',
                                        'gerant' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    ];
                                    $roleLabels = ['admin' => 'Administrateur', 'agent' => 'Agent', 'user' => 'Utilisateur', 'gerant' => 'Gérant'];
                                @endphp
                                <span class="px-3 py-1 rounded-full border font-bold uppercase tracking-tighter {{ $roleClasses[$user->role] ?? 'bg-gray-50 text-gray-700' }}">
                                    {{ $roleLabels[$user->role] ?? ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($user->status ?? true)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 uppercase">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
                                        Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 uppercase">
                                        <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
                                        Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs text-gray-500 font-medium">
                                {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-bold">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('utilisateurs.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors uppercase">Modifier</a>
                                    <form action="{{ route('utilisateurs.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirmer la suppression ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors uppercase">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic font-medium">
                                Aucun utilisateur trouvé.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Stylisée -->
            @if ($users->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <div class="text-xs text-gray-500 font-bold uppercase tracking-widest">
                    Total: {{ $users->total() }} membres
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
            @endif
        </div>

        <div class="mt-8 text-center">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Sécurité & Administration - Parking Pro</p>
        </div>
    </div>
</div>

@endsection