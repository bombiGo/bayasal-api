<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $table = "recipes";

    public function categories() 
    {
        return $this->belongsToMany(RecipeCategory::class, "recipe_has_categories", "recipe_id", "category_id");
    }
}