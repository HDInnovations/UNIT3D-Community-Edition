<div class="flex float-right">
    <button id="first" wire:click="goto(1)" class="w-10 outline-none px-2 border rounded-l-lg m-0 {{ $page === 1 ? 'bg-gray-600 text-white font-bold' :'' }}" >1</button>

    @if($page - 4 > 1)
        <button id="dots1" class="w-10 px-2 m-0 -ml-px border outline-none">&hellip;</button>
    @endif

    @if($page - 3 > 1)
        <button id="minus3" class="w-10 px-2 m-0 -ml-px border outline-none" wire:click="goto({{ $page - 3 }})">{{ $page - 3 }}</button>
    @endif
    @if($page - 2 > 1)
        <button id="minus2" class="w-10 px-2 m-0 -ml-px border outline-none" wire:click="goto({{ $page - 2 }})">{{ $page - 2 }}</button>
    @endif
    @if($page - 1 > 1)
        <button id="minus1" class="w-10 px-2 m-0 -ml-px border outline-none" wire:click="goto({{ $page - 1 }})">{{ $page - 1 }}</button>
    @endif

    @if($page !== 1 && $page !== $total )
        <button id="current" class="w-10 px-2 m-0 -ml-px font-bold text-white bg-gray-600 border outline-none">{{ $page }}</button>
    @endif

    @if($page + 1 < $total )
        <button id="plus1" class="w-10 px-2 m-0 -ml-px border outline-none" wire:click="goto({{ $page + 1 }})">{{ $page + 1 }}</button>
    @endif
    @if($page + 2 < $total )
        <button id="plus2" class="w-10 px-2 m-0 -ml-px border outline-none" wire:click="goto({{ $page + 2 }})">{{ $page + 2 }}</button>
    @endif
    @if($page + 3 < $total )
        <button id="plus3" class="w-10 px-2 m-0 -ml-px border outline-none" wire:click="goto({{ $page + 3 }})">{{ $page + 3 }}</button>
    @endif

    @if($page + 4 < $total )
        <button id="dots2" class="w-10 px-2 m-0 -ml-px border outline-none">&hellip;</button></button>
    @endif

    @if($total > 1)
        <button id="last" class="rounded-r-lg w-10 outline-none px-2 border -ml-px m-0 {{ $page === $total ? 'text-white bg-gray-600 font-bold':'' }}" wire:click="goto({{ $total }})">{{ $total }}</button>
    @endif
</div>