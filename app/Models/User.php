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
}
