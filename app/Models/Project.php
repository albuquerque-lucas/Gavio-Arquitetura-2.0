<?php

namespace App\Models;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory;
    private static bool $fallbackCoverLoaded = false;
    private static ?string $fallbackCoverUrl = null;

    protected $fillable = [
        'uuid',
        'category_id',
        'title',
        'description',
        'area',
        'slug',
        'year',
        'location',
        'status',
        'order',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($project) {
            if (empty($project->uuid)) {
                $project->uuid = (string) Str::uuid();
            }
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }

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

    public function coverUrl(): ?string
    {
        if ($this->cover && $this->cover->path) {
            return asset($this->cover->path);
        }

        if (!self::$fallbackCoverLoaded) {
            self::$fallbackCoverLoaded = true;
            self::$fallbackCoverUrl = $this->resolveFallbackCoverUrl();
        }

        return self::$fallbackCoverUrl;
    }

    public function titleInitials(): string
    {
        $text = trim((string) $this->title);
        if ($text === '') {
            return 'PR';
        }

        $parts = preg_split('/\s+/', $text) ?: [];
        if (count($parts) >= 2) {
            return Str::upper(Str::substr($parts[0], 0, 1) . Str::substr($parts[1], 0, 1));
        }

        return Str::upper(Str::substr($text, 0, 2));
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    private function resolveFallbackCoverUrl(): ?string
    {
        try {
            if (!Schema::hasTable('site_assets')) {
                return null;
            }

            $asset = SiteAsset::query()
                ->where('key', 'project_cover_fallback')
                ->first();

            if (!$asset || empty($asset->path)) {
                return null;
            }

            return asset(ltrim($asset->path, '/'));
        } catch (Throwable $e) {
            return null;
        }
    }
}
