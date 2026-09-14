<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'article_category_id',
        'image',
        'excerpt',
        'body',
        'gallery',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }

    protected static function slugSourceField(): string
    {
        return 'title';
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? Storage::disk('public')->url($this->image) : null);
    }

    /**
     * Full storage URLs for each uploaded gallery image, same reasoning as Herna::galleryUrls().
     *
     * @return list<string>
     */
    protected function galleryUrls(): Attribute
    {
        return Attribute::get(fn () => collect($this->gallery ?? [])
            ->map(fn (string $path) => Storage::disk('public')->url($path))
            ->all());
    }

    /**
     * Computed, not stored — Czech-formatted publish date, same "computed over manual"
     * reasoning as Tournament::dateText(): ui/'s prototype had a hand-typed `date` string per
     * article, we store a real published_at datetime and format it instead.
     */
    protected function dateText(): Attribute
    {
        return Attribute::get(fn () => $this->published_at?->locale('cs')->translatedFormat('j. F Y'));
    }
}
