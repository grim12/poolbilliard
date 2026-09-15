<?php

namespace App\Models;

use App\Enums\BannerColor;
use App\Models\Concerns\HasTranslatableFormFields;
use Database\Factories\BannerFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    /** @use HasFactory<BannerFactory> */
    use HasFactory;

    use HasTranslatableFormFields;

    public array $translatable = ['title', 'text', 'tag_text', 'meta_text'];

    protected $fillable = [
        'title',
        'title_translations',
        'text',
        'text_translations',
        'image',
        'tag_text',
        'tag_text_translations',
        'meta_text',
        'meta_text_translations',
        'color',
        'buttons',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'color' => BannerColor::class,
            'buttons' => 'array',
        ];
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? Storage::disk('public')->url($this->image) : null);
    }
}
