<?php

namespace App\Models;

use App\Models\Concerns\HasTranslatableFormFields;
use Database\Factories\ArticleCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleCategory extends Model
{
    /** @use HasFactory<ArticleCategoryFactory> */
    use HasFactory;

    use HasTranslatableFormFields;

    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'name_translations',
        'color',
        'sort_order',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
