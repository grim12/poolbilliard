<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatableFormFields;
use Database\Factories\TournamentCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TournamentCategory extends Model
{
    /** @use HasFactory<TournamentCategoryFactory> */
    use HasFactory;

    use HasTranslatableFormFields;

    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'name_translations',
        'color',
        'sort_order',
    ];

    public function tournaments(): HasMany
    {
        return $this->hasMany(Tournament::class);
    }
}
