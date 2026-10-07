<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
   use HasAuditLog;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'doctor_id',
        'message',
        'requested_at',
        'appointment_date',
        'read_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'appointment_date' => 'date',
        'read_at' => 'datetime',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
