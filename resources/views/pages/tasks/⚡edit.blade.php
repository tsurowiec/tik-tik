<?php

use App\Models\Task;
use Livewire\Component;

new class extends Component {
    public Task $task;

    public string $title = '';
    public ?string $description = null;
    public ?string $link = null;
    public ?string $due_date = null;
    public ?string $original_due_date = null;
    public ?string $icon = null;

    public function mount(Task $task): void
    {
        $this->task = $task;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->link = $task->link;
        $this->due_date = $task->due_date?->toDateString();
        $this->original_due_date = $task->original_due_date?->toDateString();
        $this->icon = $task->icon;
    }

    public function save()
    {
        $data = $this->validate([
            'title'             => ['required', 'string', 'max:255'],
            'description'       => ['nullable', 'string'],
            'link'              => ['nullable', 'url', 'max:2048'],
            'due_date'          => ['nullable', 'date'],
            'original_due_date' => ['nullable', 'date'],
            'icon'              => ['nullable', 'string', 'in:'.implode(',', Task::icons())],
        ]);

        $this->task->fill(\Illuminate\Support\Arr::except($data, ['icon', 'original_due_date']));
        if ($this->task->countdown) {
            $this->task->icon = $this->icon;
            $this->task->original_due_date = $data['original_due_date'];
        }
        $this->task->save();

        return $this->redirectRoute('tasks.show', $this->task, navigate: true);
    }
}; ?>

<form wire:submit="save" class="space-y-4">
    <flux:input label="Title" wire:model="title" required />
    <flux:input type="url" label="Link" wire:model="link" placeholder="https://…" />
    <flux:textarea label="Description" wire:model="description" rows="4" />
    <div
        wire:ignore
        x-data="{
            init() {
                const fp = flatpickr(this.$refs.input, {
                    dateFormat: 'Y-m-d',
                    allowInput: true,
                    defaultDate: @js($due_date),
                    onChange: (_dates, str) => $wire.set('due_date', str),
                    onClose: (_dates, str) => $wire.set('due_date', str),
                });
                this.$refs.input.addEventListener('blur', () => {
                    if (this.$refs.input.value.trim() === '') {
                        fp.clear();
                        $wire.set('due_date', null);
                    }
                });
            },
        }"
    >
        <flux:input label="Due date" placeholder="YYYY-MM-DD" x-ref="input" />
    </div>

    @if ($task->countdown)
        <div
            wire:ignore
            x-data="{
                init() {
                    const fp = flatpickr(this.$refs.input, {
                        dateFormat: 'Y-m-d',
                        allowInput: true,
                        defaultDate: @js($original_due_date),
                        onChange: (_dates, str) => $wire.set('original_due_date', str),
                        onClose: (_dates, str) => $wire.set('original_due_date', str),
                    });
                    this.$refs.input.addEventListener('blur', () => {
                        if (this.$refs.input.value.trim() === '') {
                            fp.clear();
                            $wire.set('original_due_date', null);
                        }
                    });
                },
            }"
        >
            <flux:input label="Base date" placeholder="YYYY-MM-DD" x-ref="input" />
        </div>

        <flux:select label="Icon" wire:model="icon" placeholder="Choose an icon…">
            @foreach (Task::icons() as $iconName)
                <flux:select.option value="{{ $iconName }}">{{ $iconName }}</flux:select.option>
            @endforeach
        </flux:select>
    @endif

    <div class="flex justify-between pt-2">
        <flux:button :href="route('tasks.show', $task)" wire:navigate>Cancel</flux:button>
        <flux:button type="submit" variant="primary">Save</flux:button>
    </div>
</form>
