<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images';

    public $timestamps = false;

    protected $appends = ['full_path'];

    public function getFullPathAttribute()
    {
        return Storage::disk('public')->url($this->path);
    }
}
