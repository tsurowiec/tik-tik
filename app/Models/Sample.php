<?php

namespace App\Models;

use App\Enum\SampleType;
use Database\Factories\SampleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Table(name: 'samples', key: 'barcode', keyType: 'string', incrementing: false)]
#[Fillable(['barcode', 'type', 'collected_at'])]
class Sample extends Model
{
    /** @use HasFactory<SampleFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'collected_at' => 'immutable_datetime',
            'type' => SampleType::class,
        ];
    }
}
