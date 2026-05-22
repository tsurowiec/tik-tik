<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:advance-countdowns', description: 'Create next iterations for passed recurring countdowns')]
class AdvanceCountdowns extends Command
{
    public function handle(): void
    {
        $countdowns = Task::where('countdown', true)
            ->where('due_date', '<', today())
            ->whereNull('completed_date')
            ->whereDoesntHave('next')
            ->get();

        $advanced = 0;
        $completed = 0;

        foreach ($countdowns as $countdown) {
            $next = $countdown->complete();
            if ($next) {
                $advanced++;
                $this->line("Advanced: {$countdown->shortTitle()} to {$next->due_date->toDateString()}");
            } else {
                $completed++;
                $this->line("Completed: {$countdown->shortTitle()}");
            }
        }

        $this->info("Done. Advanced {$advanced}, completed {$completed}.");
    }
}
