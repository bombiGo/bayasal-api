<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thumbnail extends Model
{
    use HasFactory;

    protected $table = "thumbnails";
    protected $fillable = ["small", "medium", "large"];

    public function thumbnail()
    {
        return $this->morphTo();
    }
}