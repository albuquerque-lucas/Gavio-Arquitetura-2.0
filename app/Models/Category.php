<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'description',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->uuid)) {
                $category->uuid = (string) Str::uuid();
            }
            $category->slug = Str::slug($category->name);
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
