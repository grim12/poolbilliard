<?php

namespace App\Models;

use Database\Factories\FaqGroupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * A place an FaqItem can be shown — the global FAQ page ("obecne") plus, later, individual
 * pages/sections. Many-to-many with FaqItem: the same question can be relevant in more than
 * one place without duplicating the row (e.g. a question could belong to both "obecne" and a
 * specific page's own FAQ block).
 */
class FaqGroup extends Model
{
    /** @use HasFactory<FaqGroupFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
    ];

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(FaqItem::class);
    }
}
