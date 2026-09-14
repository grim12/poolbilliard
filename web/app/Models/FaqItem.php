<?php

namespace App\Models;

use Database\Factories\FaqItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FaqItem extends Model
{
    /** @use HasFactory<FaqItemFactory> */
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'sort_order',
    ];

    /**
     * Which page(s)/section(s) this item shows on — see FaqGroup. `sort_order` is global (not
     * per-group) for now: an item's position is the same wherever it's shown. If a group ever
     * needs its own independent order, that belongs on the pivot table, not here.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(FaqGroup::class);
    }
}
