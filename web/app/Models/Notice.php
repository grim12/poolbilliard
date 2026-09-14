<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Database\Factories\NoticeFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    /** @use HasFactory<NoticeFactory> */
    use HasFactory;

    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'is_important',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_important' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Computed, not stored — Czech-formatted publish date, same reasoning as
     * Article::dateText()/Tournament::dateText().
     */
    protected function dateText(): Attribute
    {
        return Attribute::get(fn () => $this->published_at?->locale('cs')->translatedFormat('j. F Y'));
    }

    protected static function slugSourceField(): string
    {
        return 'title';
    }
}
