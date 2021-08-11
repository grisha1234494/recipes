<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Recipe extends Model
{
    use HasFactory;

    protected $table = 'recipes';

    public function getImageAttribute($value)
    {
        return ($value) ? Storage::disk('public')->url($value) : $value;
    }

    public function recipe_ingridients() 
    {
        return $this->hasMany(RecipeIngridient::class, 'recipe_id', 'id');
    }
    public function ingridients() 
    {
        return $this->belongsToMany(Ingridient::class, 'recipes_ingridients');
    }
    public function recipe_images() 
    {
        return $this->hasMany(RecipeImage::class, 'recipe_id', 'id');
    }
    public function images() 
    {
        return $this->belongsToMany(Image::class, 'recipes_images');
    }
    public function recipe_comments() 
    {
        return $this->hasMany(RecipeComment::class, 'recipe_id', 'id');
    }
}
