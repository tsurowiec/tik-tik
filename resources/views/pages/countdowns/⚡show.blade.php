<?php

use App\Models\Task;
use Livewire\Component;

new class extends Component {
    public Task $task;

    public function with(): array
    {
        return ['task' => $this->task];
    }

    public function destroy()
    {
        $this->task->delete();

        return $this->redirectRoute('tasks', navigate: true);
    }
}; ?>

<div class="space-y-4">
    {{-- countdown detail --}}

    <div class="flex justify-end pt-2">
        <flux:button
            wire:click="destroy"
            wire:confirm="Delete this countdown?"
            variant="danger"
            icon="trash"
        />
    </div>
</div>
