<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'cover_path',
        'cover_filename',
        'description',
        'ownership',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ownership' => 'boolean',
        ];
    }

    public function coverUrl(): string
    {
        if ($this->cover_path) {
            return asset($this->cover_path);
        } else {
            return asset('storage/users/cover/no-image.jpg');
        }
    }

    public function hasCoverPhoto(): bool
    {
        return !empty($this->cover_path);
    }

    public function profileInitials(): string
    {
        $name = trim((string) $this->name);
        if ($name === '') {
            return 'US';
        }

        $parts = preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if (count($parts) >= 2) {
            $first = mb_substr($parts[0], 0, 1);
            $second = mb_substr($parts[1], 0, 1);
            return mb_strtoupper($first . $second);
        }

        $single = $parts[0] ?? $name;
        return mb_strtoupper(mb_substr($single, 0, 2));
    }
}
