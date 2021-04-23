<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = "courses";

    public function lessons()
    {
    	return $this->hasMany(Lesson::class, "course_id")->orderBy("day");
    }

    public function thumbnail()
    {
        return $this->morphOne(Thumbnail::class, "thumbnailable");
    }
}