<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatableFormFields;
use Database\Factories\LeaderboardFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    /** @use HasFactory<LeaderboardFactory> */
    use HasFactory;

    use HasTranslatableFormFields;

    public array $translatable = ['title'];

    protected $fillable = [
        'title',
        'title_translations',
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
