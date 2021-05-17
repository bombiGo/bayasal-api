<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;

    protected $table = "infos";

    protected $casts = [
        "is_featured" => "integer",
    ];
    

    public function categories() 
    {
        return $this->belongsToMany(InfoCategory::class, "info_has_categories", "info_id", "category_id");
    }

    public function thumbnail()
    {
        return $this->morphOne(Thumbnail::class, "thumbnailable");
    }
}