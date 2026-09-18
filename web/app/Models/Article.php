<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\HasTranslatableFormFields;
use App\Support\Seo;
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
    use HasTranslatableFormFields;

    public array $translatable = ['title', 'excerpt', 'body', 'seo_title', 'seo_description'];

    protected $fillable = [
        'title',
        'title_translations',
        'slug_cs',
        'slug_en',
        'article_category_id',
        'image',
        'excerpt',
        'excerpt_translations',
        'body',
        'body_translations',
        'gallery',
        'published_at',
        'seo_title',
        'seo_title_translations',
        'seo_description',
        'seo_description_translations',
        'seo_image',
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

    protected function seoImageUrl(): Attribute
    {
        return Attribute::get(fn () => Seo::imageUrl($this->seo_image));
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
