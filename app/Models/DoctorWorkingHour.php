<?php

namespace App\Models;

use App\Enums\WorkingDay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorWorkingHour extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'doctor_id',
        'day',
        'start_time',
        'end_time',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'day' => WorkingDay::class,
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'is_visible' => 'boolean',
        ];
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
