<?php

namespace App\Models;

use Database\Factories\TaskFactory;
use DateInterval;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

#[Fillable(['title', 'description', 'link', 'due_date'])]
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    protected $casts = [
        'due_date' => 'date',
        'original_due_date' => 'date',
        'completed_date' => 'date',
        'countdown' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Insert ?ui=2 after /u/0/ in Gmail links so they open in the basic HTML view.
     */
    protected function link(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value): ?string => $value === null
                ? null
                : preg_replace(
                    '~^(https://mail\.google\.com/mail/u/\d+/)(?!\?ui=2)(#)~',
                    '$1?ui=2$2',
                    $value
                ),
        );
    }

    public function prev(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    public function next(): HasOne
    {
        return $this->hasOne(Task::class, 'parent_task_id');
    }

    public function hasParent(): bool
    {
        return $this->parent_task_id !== null;
    }

    public function hasNext(): bool
    {
        return $this->next()->exists();
    }

    public function complete(): ?Task
    {
        $next = $this->replicate();
        $this->completed_date = now();
        $this->save();

        if ($this->recurring() && ! $this->hasNext()) {
            $expression = $this->parseEveryExpression();
            $interval = new DateInterval(substr($expression['expression'], 1));
            if ($this->countdown) {
                $next->due_date = $this->due_date->add($interval);
                $next->original_due_date = $this->original_due_date;
            } else {
                $next->original_due_date = $next->due_date = $this->original_due_date->add($interval);
            }
            $next->iteration = $this->iteration + 1;
            $next->parent_task_id = $this->id;
            $next->save();

            return $next;
        }

        return null;
    }

    public function revert(): void
    {
        $this->completed_date = null;
        $this->save();
    }

    public function shortTitle(): string
    {
        $expression = $this->parseEveryExpression();
        $title = $expression === null
            ? $this->title
            : trim(str_replace($expression['expression'], '', $this->title));

        if ($this->countdown && $this->original_due_date && $this->due_date) {
            $years = $this->original_due_date->diffInYears($this->due_date);
            $title .= " ({$years})";
        }

        return $title;
    }

    public function recurring(): bool
    {
        return is_array($expression = $this->parseEveryExpression()) && $expression['expression'];
    }

    private function parseEveryExpression(): ?array
    {
        if (! preg_match('/@P(\d+)([DWMY])\b/i', $this->title, $matches)) {
            return null;
        }

        $interval = isset($matches[1]) && $matches[1] !== ''
            ? (int) $matches[1]
            : 1;

        $unitMap = [
            'D' => 'day',
            'W' => 'week',
            'M' => 'month',
            'Y' => 'year',
        ];

        $unitCode = strtoupper($matches[2]);

        return [
            'expression' => $matches[0],
            'interval' => $interval,
            'unit' => $unitMap[$unitCode],
        ];
    }

    public function countdownPhrase(): string
    {
        $diff = (int) floor(Carbon::now()->startOfDay()->diffInDays($this->due_date));

        return match ($diff) {
            0 => 'Today',
            1 => 'Tomorrow',
            default => sprintf('in %d days', $diff),
        };
    }

    public function countdownColor(): string
    {
        $colors = [
            'red',
            'orange',
            'amber',
            'yellow',
            'lime',
            'green',
            'emerald',
            'teal',
            'cyan',
            'sky',
            'blue',
            'indigo',
            'violet',
            'purple',
            'fuchsia',
            'rose',
        ];

        return $colors[hexdec(substr(md5($this->title), 0, 1))];
    }

    public static function icons(): array
    {
        return [
            'academic-cap',
            'archive-box',
            'banknotes',
            'bell',
            'briefcase',
            'cake',
            'calendar',
            'camera',
            'face-smile',
            'film',
            'flag',
            'gift',
            'globe-europe-africa',
            'key',
            'light-bulb',
            'sparkles',
            'star',
            'sun',
            'ticket',
            'trophy',
            'user-group',
            'wrench',
        ];
    }

    public function repeatPhrase(): string
    {
        $expression = $this->parseEveryExpression();

        if ($expression === null) {
            return '';
        }

        return sprintf(
            'every %s%s',
            $expression['interval'] === 1 ? ' ' : $this->ordinal($expression['interval']).' ', $expression['unit']
        );
    }

    private function ordinal(int $number): string
    {
        $suffixes = ['th', 'st', 'nd', 'rd'];
        if (($number % 100) >= 11 && ($number % 100) <= 13) {
            return $number.'th';
        }

        return $number.($suffixes[$number % 10] ?? 'th');
    }
}
