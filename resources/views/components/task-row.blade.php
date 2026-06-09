@php use Carbon\CarbonImmutable; @endphp
@props(['task', 'description' => false])

@php
    $overdue = !$task->completed_date && $task->due_date && $task->due_date->toDateString() < now()->toDateString();

    $formatDate = function (?CarbonImmutable $date): string {
        if (null===$date) return 'No due date defined';

        $diffDays = (int) now()->startOfDay()->diffInDays($date);

        return match(true) {
            $diffDays === 0  => 'Today',
            $diffDays === 1  => 'Tomorrow',
            $diffDays === -1 => 'Yesterday',
            $diffDays > 1 && $diffDays < 7   => "In {$diffDays} days",
            $diffDays < -1 && $diffDays > -7 => abs($diffDays) . ' days ago',
            default => $date->format('j F') . ($date->year !== now()->year ? ' ' . $date->year : ''),
        };
    };
@endphp

@if ($task->countdown)
    <div wire:key="pending-{{ $task->id }}"
         class="group flex items-center justify-between gap-3 p-2 rounded-lg transition-colors hover:bg-accent/6">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <flux:icon :name="$task->icon ?: 'cake'" class="size-7 text-{{ $task->countdownColor() }}-600 dark:text-{{ $task->countdownColor() }}-400" />
            <div class="flex justify-between w-full gap-4">
                <flux:text class="font-bold" :color="$task->countdownColor()">
                    <a href="{{ route('tasks.show', $task) }}" wire:navigate class="no-underline text-inherit">{{ $task->shortTitle() }}</a>
                </flux:text>
                <div class="flex items-center gap-4">
                    <flux:text class="text-zinc-400 dark:text-zinc-500">
                        <a href="{{ route('tasks.show', $task) }}" wire:navigate class="no-underline text-inherit">{{ $task->due_date->format('j F') }}{{ $task->due_date->year !== now()->year ? ' ' . $task->due_date->year : '' }}</a>
                    </flux:text>
                    <flux:text class="text-zinc-900 dark:text-zinc-400">
                        <a href="{{ route('tasks.show', $task) }}" wire:navigate class="no-underline text-inherit">{{ $task->countdownPhrase() }}</a>
                    </flux:text>
                </div>
            </div>
        </div>
    </div>
@elseif ($task->completed_date)
    <div wire:key="completed-{{ $task->id }}" class="flex items-center gap-3 px-2 py-1 rounded-lg opacity-50 hover:bg-accent/6">
        <flux:checkbox checked wire:click="revertTask({{ $task->id }})" x-on:click="playSound(false)"/>
        <div class="flex-1 min-w-0">
            <flux:text class="line-through">
                <a href="{{ route('tasks.show', $task) }}" wire:navigate class="no-underline text-inherit">{{ $task->shortTitle() }}</a>
            </flux:text>
            @if ($task->completed_date)
                <div class="flex items-center gap-2 w-full">
                    <flux:text size="sm" class="text-zinc-400 dark:text-zinc-500">{{ $formatDate($task->completed_date) }}</flux:text>
                    <span class="ml-auto flex items-center gap-1">
                        @if ($task->link)
                            <flux:icon name="link" class="size-3 text-zinc-400 dark:text-zinc-500" />
                        @endif
                        @if ($task->description)
                            <flux:icon name="bars-3-bottom-left" class="size-3 text-zinc-400 dark:text-zinc-500" />
                        @endif
                    </span>
                </div>
            @endif
        </div>
    </div>
@else
    <div wire:key="pending-{{ $task->id }}"
         class="group flex items-center justify-between gap-3 px-2 py-1 rounded-lg transition-colors hover:bg-accent/6">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <flux:checkbox wire:click="completeTask({{ $task->id }})" x-on:click="playSound(true)"/>
            <div class="flex-1 min-w-0 min-h-8.75">
                <flux:text class="{{ $overdue ? 'text-red-600 dark:text-red-400' : 'text-zinc-900 dark:text-zinc-400' }}">
                    <a href="{{ route('tasks.show', $task) }}" wire:navigate class="no-underline text-inherit">{{ $task->shortTitle() }}</a>
                </flux:text>
                <div class="flex items-center gap-2 w-full">
                    @if ($description && $task->description)
                        <flux:text size="sm" class="{{ $overdue ? 'text-red-400 dark:text-red-500' : 'text-zinc-400 dark:text-zinc-500' }}">
                            {{ $task->description }}
                        </flux:text>
                    @else ($task->due_date)
                        <flux:text size="sm" class="{{ $overdue ? 'text-red-400 dark:text-red-500' : 'text-zinc-400 dark:text-zinc-500' }}">
                            {{ $formatDate($task->due_date) }}@if ($task->recurring()), then {{ $task->repeatPhrase() }} @endif
                        </flux:text>
                    @endif
                    <span class="ml-auto flex items-center gap-1">
                        @if ($task->recurring())
                            <flux:text size="sm" class="text-zinc-400 dark:text-zinc-500">{{ $task->iteration }}/∞</flux:text>
                            <flux:icon name="arrow-path" class="size-3 text-zinc-400 dark:text-zinc-500" />
                        @endif
                        @if ($task->link)
                            <flux:icon name="link" class="size-3 text-zinc-400 dark:text-zinc-500" />
                        @endif
                        @if ($task->description)
                            <flux:icon name="bars-3-bottom-left" class="size-3 text-zinc-400 dark:text-zinc-500" />
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
@endif
