<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $table = "lessons";

    protected $casts = [
        "day" => "integer",
        "course_id" => "integer",
        "is_locked" => "integer"
    ];

    public function course()
    {
    	return $this->belongsTo(Course::class);
    }
}