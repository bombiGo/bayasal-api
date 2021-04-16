<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeCategory extends Model
{
    use HasFactory;

    protected $table = "recipe_categories";

    public function recipes()
    {
        return $this->belongsToMany(Info::class, "recipe_has_categories", "category_id", "recipe_id");
    }
}