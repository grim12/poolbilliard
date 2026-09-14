<?php

namespace App\Models;

use Database\Factories\LinkTileFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LinkTile extends Model
{
    /** @use HasFactory<LinkTileFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'image',
        'sort_order',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? Storage::disk('public')->url($this->image) : null);
    }
}
