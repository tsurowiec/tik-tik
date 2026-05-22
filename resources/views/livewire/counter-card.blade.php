<?php

use Illuminate\Support\Facades\Session;
use Livewire\Component;

new class extends Component {
    public string $name;
    public int $count = 0;

//    public function mount(string $name): void
//    {
//        $this->count = Session::get("counter.{$name}", 0);
//    }

    public function inc(): void
    {
        $this->count++;
//        Session::put("counter.{$this->name}", $this->count);
    }

    public function zero(): void
    {
        $this->count = 0;
//        Session::put("counter.{$this->name}", $this->count);
    }
};

?>

<flux:card class="space-y-6">
    <flux:heading level="2">{{ $name }}</flux:heading>
    <div class="text-center">
        <flux:text class="text-5xl font-bold">{{ $count }}</flux:text>
    </div>
    <div class="flex gap-2 justify-center">
        <flux:button wire:click="inc">Click</flux:button>
        <flux:button wire:click="zero">Reset</flux:button>
    </div>
</flux:card>
