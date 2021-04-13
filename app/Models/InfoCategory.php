<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoCategory extends Model
{
    use HasFactory;

    protected $table = "info_categories";

    public function infos()
    {
        return $this->belongsToMany(Info::class, "info_has_categories", "category_id", "info_id");
    }
}