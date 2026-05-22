<?php

use App\Models\Task;
use Livewire\Component;

new class extends Component {
    public string $activeTab = 'next10days';

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function completeTask(int $taskId): void
    {
        /** @var Task $task */
        $task = Auth::user()->tasks()->findOrFail($taskId);
        $task->complete();
    }

    public function revertTask(int $taskId): void
    {
        /** @var Task $task */
        $task = Auth::user()->tasks()->findOrFail($taskId);
        $task->revert();
    }

    public function with(): array
    {
        $user = Auth::user();
        $today = now()->startOfDay();
        $in10Days = now()->addDays(9)->endOfDay();

        $next10DaysItems = $user->tasks()
            ->whereNull('completed_date')
            ->where('due_date', '>=', $today)
            ->where('due_date', '<=', $in10Days)
            ->orderBy('due_date')
            ->get();

        $next10DaysGrouped = $next10DaysItems->groupBy(function ($task) use ($today) {
            if ($task->due_date->isSameDay($today)) return 'Today';
            if ($task->due_date->isSameDay($today->copy()->addDay())) return 'Tomorrow';
            if ($task->due_date->isSameDay($today->copy()->addDays(2))) return $task->due_date->format('l');
            if ($task->due_date->isSameDay($today->copy()->addDays(3))) return $task->due_date->format('l');
            if ($task->due_date->isSameDay($today->copy()->addDays(4))) return $task->due_date->format('l');
            if ($task->due_date->isSameDay($today->copy()->addDays(5))) return $task->due_date->format('l');
            if ($task->due_date->isSameDay($today->copy()->addDays(6))) return $task->due_date->format('l');
            return $task->due_date->format('j M');
        });

        $overdueTasks = $user->tasks()
            ->whereNull('completed_date')
            ->where('due_date', '<', $today)
            ->orderBy('due_date')
            ->get();

        $laterTasks = $user->tasks()
            ->whereNull('completed_date')
            ->where('due_date', '>', $in10Days)
            ->orderBy('due_date')
            ->get();

        $somedayTasks = $user->tasks()
            ->whereNull('completed_date')
            ->whereNull('due_date')
            ->orderBy('id')
            ->get();

        $completedTasks = $user->tasks()
            ->where('countdown', false)
            ->whereDate('completed_date', $today)
            ->orderBy('due_date')
            ->get();

        $countdowns = $user->tasks()
            ->where('countdown', true)
            ->where('due_date', '>=', $today)
            ->whereNotNull('complete_date')
            ->orderBy('due_date')
            ->take(30)
            ->get();

        $tabs = [
            ['key' => 'next10days', 'label' => 'Next 10 Days', 'icon' => 'calendar-days', 'count' => $next10DaysItems->count() + $overdueTasks->count(), 'overdue' => (bool) $overdueTasks->count()],
            ['key' => 'later', 'label' => 'Later', 'icon' => 'arrow-right-circle', 'count' => $laterTasks->count()],
            ['key' => 'someday', 'label' => 'Someday', 'icon' => 'sparkles', 'count' => $somedayTasks->count()],
            ['key' => 'countdowns', 'label' => 'Countdowns', 'icon' => 'flag', 'count' => $countdowns->count()],
        ];

        return [
            'tabs' => $tabs,
            'overdueTasks' => $overdueTasks,
            'next10DaysTasks' => $next10DaysItems,
            'next10DaysGrouped' => $next10DaysGrouped,
            'laterTasks' => $laterTasks,
            'somedayTasks' => $somedayTasks,
            'completedTasks' => $completedTasks,
            'countdowns' => $countdowns,
        ];
    }
}; ?>

<div class="space-y-4">
    <div class="flex justify-end">
        <flux:button :href="route('tasks.create')" wire:navigate variant="primary" icon="plus" class="bg-accent! text-accent-foreground!">New</flux:button>
    </div>

    <x-tabs :tabs="$tabs" :active="$activeTab"/>

    @if($activeTab === 'next10days')
        <div class="space-y-1">
            @if($overdueTasks->isNotEmpty())
                <div>
                    <x-section-label variant="danger">{{ __('Overdue') }}</x-section-label>
                    @foreach($overdueTasks as $task)
                        <x-task-row :task="$task"/>
                    @endforeach
                </div>
            @endif

            @foreach($next10DaysGrouped as $label => $tasks)
                <div>
                    <x-section-label>{{ $label }}</x-section-label>
                    @foreach($tasks as $task)
                        <x-task-row :task="$task" description="true"/>
                    @endforeach
                </div>
            @endforeach

            @if(!$completedTasks->isEmpty())
                <div>
                    <x-section-label>{{ __('Completed') }}</x-section-label>
                    @foreach($completedTasks as $task)

                        <x-task-row :task="$task"/>
                    @endforeach
                </div>
            @endif
        </div>
    @elseif($activeTab === 'later')
        <div class="space-y-2">
            <x-section-label>{{ __('Later') }}</x-section-label>
            @foreach($laterTasks as $task)
                <x-task-row :task="$task"/>
            @endforeach
        </div>
    @elseif($activeTab === 'someday')
        <div class="space-y-2">
            <x-section-label>{{ __('Someday') }}</x-section-label>
            @foreach($somedayTasks as $task)
                <x-task-row :task="$task"/>
            @endforeach
        </div>
    @elseif($activeTab === 'countdowns')
        <div class="space-y-2">
            <x-section-label>{{ __('Countdowns') }}</x-section-label>
            @foreach($countdowns as $task)
                <x-task-row :task="$task"/>
            @endforeach
        </div>
    @endif
</div>

