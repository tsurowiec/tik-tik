@props(['tabs', 'active'])

<div class="flex justify-between border-b border-zinc-200 dark:border-zinc-700">
    @foreach($tabs as $tab)
        <button
            wire:click="setTab('{{ $tab['key'] }}')"
            class="flex items-center gap-1 px-3 py-2.5 text-sm font-medium transition-colors
                {{ $active === $tab['key']
                    ? 'border-b-2 border-accent text-zinc-900 dark:text-white'
                    : 'text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200' }}"
        >
            <flux:icon :name="$tab['icon']" class="size-7" />
            <span class="hidden sm:inline">{{ $tab['label'] }}</span>
            @if ($tab['overdue'] ?? false)
                <flux:badge rounded size="sm" color="red">{{ $tab['count'] }}</flux:badge>
            @elseif ($active === $tab['key'])
                <flux:badge rounded size="sm" class="bg-accent! text-accent-foreground!">{{ $tab['count'] }}</flux:badge>
            @else
                <flux:badge rounded size="sm">{{ $tab['count'] }}</flux:badge>
            @endif
        </button>
    @endforeach
</div>
