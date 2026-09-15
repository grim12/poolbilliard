<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasTranslatableFormFields;
use Database\Factories\NoticeFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    /** @use HasFactory<NoticeFactory> */
    use HasFactory;

    use HasSlug;
    use HasTranslatableFormFields;

    public array $translatable = ['title', 'excerpt', 'body'];

    protected $fillable = [
        'title',
        'title_translations',
        'slug_cs',
        'slug_en',
        'excerpt',
        'excerpt_translations',
        'body',
        'body_translations',
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
