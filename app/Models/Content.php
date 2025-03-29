<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    /** @use HasFactory<\Database\Factories\ContentFactory> */
    use HasFactory, Sluggable;

    protected $slugColumnFrom = 'title';

    protected $fillable = ['title', 'description', 'body', 'type', 'cover'];

    public $appends = ['valid_videos'];


    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Scopes
     */
    public function scopeGetValidContents(Builder $query): Builder
    {
        return $query->whereHas(
            'videos',
            fn($query) =>
            $query
                ->whereNotNull('code')
                ->whereIsProcessed(true)
        );
    }

    /**
     * Appends
     */
    public function validVideos(): Attribute
    {
        return new Attribute(
            get: fn() =>
            $this->videos()
                ->whereNotNull('code')
                ->whereIsProcessed(true)->get()
        );
    }
}
