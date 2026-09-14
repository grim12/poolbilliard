<?php

namespace App\Models;

use App\Enums\HernaStatus;
use App\Enums\Region;
use App\Models\Concerns\HasSlug;
use Database\Factories\HernaFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Herna extends Model
{
    /** @use HasFactory<HernaFactory> */
    use HasFactory;

    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'city',
        'region',
        'lat',
        'lng',
        'about_text',
        'phone',
        'email',
        'website',
        'sports',
        'hours',
        'gallery',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'region' => Region::class,
            'lat' => 'decimal:7',
            'lng' => 'decimal:7',
            'sports' => 'array',
            'hours' => 'array',
            'gallery' => 'array',
            'status' => HernaStatus::class,
        ];
    }

    /**
     * Full storage URLs for each uploaded gallery image — `gallery` itself stores relative
     * paths (same reasoning as Partner::logo_url()).
     *
     * @return list<string>
     */
    protected function galleryUrls(): Attribute
    {
        return Attribute::get(fn () => collect($this->gallery ?? [])
            ->map(fn (string $path) => Storage::disk('public')->url($path))
            ->all());
    }
}
