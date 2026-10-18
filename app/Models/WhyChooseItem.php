<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class WhyChooseItem extends Model
{
    use HasTranslations, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'why_choose_id',
        'text',
        'column',
        'order',
    ];


    protected $casts = [
        'order' => 'integer',
    ];


    public array $translatable = [
        'text',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    public function scopeLeft(Builder $query): Builder
    {
        return $query->where('column', 'left');
    }

    public function scopeRight(Builder $query): Builder
    {
        return $query->where('column', 'right');
    }

    public function whyChoose(): BelongsTo
    {
        return $this->belongsTo(WhyChoose::class);
    }
}
