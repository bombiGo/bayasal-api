<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->integer("day");
            $table->boolean("is_locked")->default(0);
            $table->string("mode");
            $table->string("duration")->nullable();
            $table->string("pdf_url")->nullable();
            $table->string("video_id")->nullable();
            $table->longText("content")->nullable();
            $table->bigInteger("course_id")->unsigned();
            $table->timestamps();

            $table->foreign("course_id")->references("id")->on("courses");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}
