@if($online)
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase">
        <span class="relative flex h-2 w-2 mr-1.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
        </span>
        En ligne
    </span>
@else
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 uppercase">
        <span class="h-2 w-2 rounded-full bg-gray-400 mr-1.5"></span>
        Hors ligne
    </span>
@endif
@if($lastEvent)
    <div class="text-[10px] text-gray-400 mt-1">{{ $lastEvent->diffForHumans() }}</div>
@endif