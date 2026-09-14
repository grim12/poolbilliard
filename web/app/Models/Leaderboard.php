<?php

namespace App\Models;

use Database\Factories\LeaderboardFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    /** @use HasFactory<LeaderboardFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'featured',
        'entries',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'entries' => 'array',
        ];
    }
}
