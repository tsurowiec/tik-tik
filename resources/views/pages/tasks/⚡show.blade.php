<?php

use App\Models\Task;
use Livewire\Component;

new class extends Component {
    public Task $task;

    public function complete(): void
    {
        $this->task->complete();
        $this->task->refresh();
    }

    public function revert(): void
    {
        $this->task->revert();
        $this->task->refresh();
    }

    public function destroy()
    {
        $this->task->delete();

        return $this->redirectRoute('tasks', navigate: true);
    }
}; ?>

@php
    $fields = [
        'Description'    => $task->description,
        'Link'           => $task->link,
        'Due date'       => $task->due_date?->format('Y-m-d'),
        'Completed date' => $task->completed_date?->format('Y-m-d'),
        'Repeat'         => $task->repeatPhrase() ?: null,
    ];
@endphp

<div class="space-y-4">
    @if ($task->countdown)
        <flux:card>
            <div class="flex items-end justify-center gap-3">
                <flux:icon :name="$task->icon ?: 'cake'" class="size-7 text-{{ $task->countdownColor() }}-600 dark:text-{{ $task->countdownColor() }}-400" />
                <flux:text class="text-xl font-bold" :color="$task->countdownColor()">
                    {{ $task->shortTitle() }}
                </flux:text>
            </div>
            <flux:text class="text-xl mt-2 text-center text-zinc-900 dark:text-zinc-400">
                {{ $task->countdownPhrase() }}
            </flux:text>
        </flux:card>
    @else
        <flux:card>
            <div class="flex items-center gap-3">
                @if ($task->completed_date)
                    <flux:checkbox wire:key="show-completed-{{ $task->id }}" checked wire:click="revert" x-on:click="playSound(false)" />
                @else
                    <flux:checkbox wire:key="show-pending-{{ $task->id }}" wire:click="complete" x-on:click="playSound(true)" />
                @endif
                <flux:text class="text-xl font-bold text-zinc-700 dark:text-zinc-300 {{ $task->completed_date ? 'line-through opacity-60' : '' }}">
                    {{ $task->shortTitle() }}
                </flux:text>
            </div>
        </flux:card>
    @endif

    <div class="grid gap-3">
        @foreach ($fields as $label => $value)
            @if (!is_null($value) && $value !== '')
                <flux:card>
                    <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">
                        {{ $label }}
                    </flux:text>
                    <div class="mt-1 text-zinc-900 dark:text-zinc-100">
                        @if ($label === 'Link')
                            <a href="{{ $value }}" target="_blank" rel="noopener" class="underline break-all">{{ $value }}</a>
                        @elseif ($label === 'Description')
                            <p class="whitespace-pre-wrap">{{ $value }}</p>
                        @else
                            {{ $value }}
                        @endif
                    </div>
                </flux:card>
            @endif
        @endforeach
    </div>

    <div class="flex justify-between pt-2">
        <flux:button :href="route('tasks')" wire:navigate icon="arrow-left" />
        <div class="flex gap-2">
            <flux:button :href="route('tasks.edit', $task)" wire:navigate variant="primary" icon="pencil" />
            <flux:button
                wire:click="destroy"
                wire:confirm="Delete this task?"
                variant="danger"
                icon="trash"
            />
        </div>
    </div>
</div>
