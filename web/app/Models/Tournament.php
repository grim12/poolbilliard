<?php

namespace App\Models;

use Database\Factories\TournamentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    /** @use HasFactory<TournamentFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'tag_text',
        'tag_color',
        'date_text',
        'location_text',
        'badge',
        'soon',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'badge' => 'boolean',
            'soon' => 'boolean',
        ];
    }
}
