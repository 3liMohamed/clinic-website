<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class BreadcrumbItem extends Model
{
    use HasTranslations;

    public $timestamps = false;

    protected $fillable = [
        'page_id',
        'text',
        'url',
        'order',
    ];

    public array $translatable = [
        'text',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

}
