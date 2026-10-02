<?php

namespace App\Models;

use App\Enums\WorkingDay;
use App\Services\DoctorService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorWorkingHour extends Model
{
    use SoftDeletes;

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

    protected static function booted(): void
    {
        static::saved(fn() => app(DoctorService::class)->flush());
        static::deleted(fn() => app(DoctorService::class)->flush());
        static::restored(fn() => app(DoctorService::class)->flush());
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
