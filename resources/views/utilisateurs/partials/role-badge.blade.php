@php
    $roleClasses = [
        'admin'  => 'bg-red-50 text-red-700 border-red-100',
        'agent'  => 'bg-indigo-50 text-indigo-700 border-indigo-100',
        'user'   => 'bg-gray-50 text-gray-700 border-gray-100',
        'gerant' => 'bg-amber-50 text-amber-700 border-amber-100',
    ];
    $roleLabels = [
        'admin' => 'Administrateur', 'agent' => 'Agent',
        'user' => 'Utilisateur', 'gerant' => 'Gérant',
    ];
@endphp
<span class="px-3 py-1 rounded-full border font-bold uppercase tracking-tighter text-xs {{ $roleClasses[$role] ?? 'bg-gray-50 text-gray-700' }}">
    {{ $roleLabels[$role] ?? ucfirst($role) }}
</span>