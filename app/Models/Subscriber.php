<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasAuditLog;

    public $timestamps = false;

    protected $fillable = [
        'email',
        'subscribed_at',
        'read_at',
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'read_at' => 'datetime',
    ];
}
