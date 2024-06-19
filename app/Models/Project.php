<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'date',
        'location',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function cover(): HasOne
    {
        return $this->hasOne(Cover::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function coverUrl(): string
    {
        if ($this->cover && $this->cover->path) {
            return asset($this->cover->path);
        } else {
            return asset('storage/projects/cover/no-image.jpg');
        }
    }
}
