<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipeHasCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipe_has_categories', function (Blueprint $table) {
            $table->bigInteger("recipe_id")->unsigned();
            $table->bigInteger("category_id")->unsigned();
            $table->primary(["recipe_id", "category_id"]);

            $table->foreign("recipe_id")->references("id")->on("recipes")->onUpdate("cascade")->onDelete("cascade");
            $table->foreign("category_id")->references("id")->on("recipe_categories")->onUpdate("cascade")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe_has_categories');
    }
}
