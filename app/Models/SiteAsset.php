<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteAsset extends Model
{
    protected $fillable = [
        'key',
        'path',
        'original_name',
        'mime_type',
        'size',
    ];
}
