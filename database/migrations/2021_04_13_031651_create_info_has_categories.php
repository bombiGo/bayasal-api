<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoHasCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_has_categories', function (Blueprint $table) {
            $table->bigInteger("info_id")->unsigned();
            $table->bigInteger("category_id")->unsigned();
            $table->primary(["info_id", "category_id"]);

            $table->foreign("info_id")->references("id")->on("infos")->onUpdate("cascade")->onDelete("cascade");
            $table->foreign("category_id")->references("id")->on("info_categories")->onUpdate("cascade")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('info_has_categories');
    }
}
