<?php

use App\Models\Task;
use Livewire\Component;

new class extends Component {
    public Task $task;

    public function with(): array
    {
        return ['task' => $this->task];
    }
}; ?>

<div>
    {{-- countdown detail --}}
</div>
