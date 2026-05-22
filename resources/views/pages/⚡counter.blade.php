<?php

use Livewire\Component;

new class extends Component
{
    public array $counters;

    public function mount(): void
    {
        $this->counters = [rand(0, 20), rand(0, 200), rand(0, 2000), rand(0, 20000)];
    }
}
?>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <livewire:counter-card name="Counter 1" :count="$counters[0]"/>
    <livewire:counter-card name="Counter 2" :count="$counters[1]"/>
    <livewire:counter-card name="Counter 3" :count="$counters[2]"/>
    <livewire:counter-card name="Counter 4" :count="$counters[3]"/>
</div>
