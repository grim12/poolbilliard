<?php

namespace App\Models;

use Database\Factories\ClubMemberFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ClubMember extends Model
{
    /** @use HasFactory<ClubMemberFactory> */
    use HasFactory;

    protected $fillable = [
        'club_id',
        'name',
        'photo',
        'sort_order',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    protected function photoUrl(): Attribute
    {
        return Attribute::get(fn () => $this->photo ? Storage::disk('public')->url($this->photo) : null);
    }
}
