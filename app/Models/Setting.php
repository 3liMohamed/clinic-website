<?php

namespace App\Models;

use App\Traits\HasAuditLog;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasAuditLog;
    public $timestamps = false;


    protected $fillable = [
        'key',
        'value'
    ];
}
