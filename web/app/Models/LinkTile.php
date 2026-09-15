<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatableFormFields;
use Database\Factories\LinkTileFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LinkTile extends Model
{
    /** @use HasFactory<LinkTileFactory> */
    use HasFactory;

    use HasTranslatableFormFields;

    public array $translatable = ['title'];

    protected $fillable = [
        'title',
        'title_translations',
        'url',
        'image',
        'sort_order',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? Storage::disk('public')->url($this->image) : null);
    }
}
