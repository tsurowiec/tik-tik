<?php

use App\Models\Task;
use Illuminate\Support\Arr;
use Livewire\Component;

new class extends Component {
    public string $title = '';
    public ?string $description = null;
    public ?string $link = null;
    public ?string $due_date = null;
    public ?string $original_due_date = null;
    public string $type = 'task';
    public ?string $icon = null;

    public function save()
    {
        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'link' => ['nullable', 'url', 'max:2048'],
            'due_date' => ['nullable', 'date'],
            'original_due_date' => ['nullable', 'date'],
            'type' => ['required', 'in:task,countdown'],
            'icon' => ['nullable', 'string', 'in:' . implode(',', Task::icons())],
        ]);

        $countdown = $this->type === 'countdown';

        $task = new Task(Arr::only($data, ['title', 'description', 'link', 'due_date']));
        $task->user_id = Auth::id();
        $task->countdown = $countdown;
        $task->icon = $countdown ? $this->icon : null;
        $task->original_due_date = $countdown ? ($this->original_due_date) : $this->due_date;
        $task->save();

        return $this->redirectRoute('tasks', navigate: true);
    }
}; ?>

<form wire:submit="save" class="space-y-4">
    <flux:radio.group wire:model.live="type" variant="segmented">
        <flux:radio value="task" label="Task"/>
        <flux:radio value="countdown" label="Countdown"/>
    </flux:radio.group>

    <flux:input label="Title" wire:model="title" required/>
    <div
        wire:ignore
        x-data="{
            init() {
                flatpickr(this.$refs.input, {
                    dateFormat: 'Y-m-d',
                    allowInput: true,
                    defaultDate: @js($due_date),
                    onChange: (_dates, str) => $wire.set('due_date', str),
                    onClose: (_dates, str) => $wire.set('due_date', str),
                });
            },
        }"
    >
        <flux:input label="Due date" placeholder="YYYY-MM-DD" x-ref="input"/>
    </div>
    <flux:input type="url" label="Link" wire:model="link" placeholder="https://…"/>
    <flux:textarea label="Description" wire:model="description" rows="4"/>

    @if ($type === 'countdown')
        <div
            wire:ignore
            x-data="{
                init() {
                    flatpickr(this.$refs.input, {
                        dateFormat: 'Y-m-d',
                        allowInput: true,
                        defaultDate: @js($original_due_date),
                        onChange: (_dates, str) => $wire.set('original_due_date', str),
                        onClose: (_dates, str) => $wire.set('original_due_date', str),
                    });
                },
            }"
        >
            <flux:input label="Base date" placeholder="YYYY-MM-DD" x-ref="input"/>
        </div>

        <flux:select label="Icon" wire:model="icon" placeholder="Choose an icon…">
            @foreach (\App\Models\Task::icons() as $iconName)
                <flux:select.option value="{{ $iconName }}">{{ $iconName }}</flux:select.option>
            @endforeach
        </flux:select>
    @endif

    <div class="flex justify-between pt-2">
        <flux:button :href="route('tasks')" wire:navigate>Cancel</flux:button>
        <flux:button type="submit" variant="primary">Create</flux:button>
    </div>
</form>
