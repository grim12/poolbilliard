<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatableFormFields;
use App\Support\InternalLink;
use Database\Factories\LinkTileFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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
        'link_route',
        'linkable_type',
        'linkable_id',
        'image',
        'sort_order',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image ? Storage::disk('public')->url($this->image) : null);
    }

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The URL this tile should actually link to — see App\Support\InternalLink's docblock for
     * why link_route/linkable win over a manually typed `url`.
     */
    protected function resolvedUrl(): Attribute
    {
        return Attribute::get(fn () => InternalLink::resolve($this->link_route, $this->linkable, $this->url));
    }
}
