<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeIngridient extends Model
{
    use HasFactory;

    protected $table = 'recipes_ingridients';

    public $timestamps = false;
}
