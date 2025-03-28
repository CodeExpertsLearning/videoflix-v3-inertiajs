<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    /** @use HasFactory<\Database\Factories\ContentFactory> */
    use HasFactory, Sluggable;

    protected $slugColumnFrom = 'title';

    protected $fillable = ['title', 'description', 'body', 'type', 'cover'];


    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}
