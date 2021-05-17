<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->string("user_email");
            $table->string("user_name");
            $table->bigInteger("course_id");
            $table->string("course_title");
            $table->string("course_price");
            $table->smallInteger("course_day");
            $table->string("sender_invoice_no");
            $table->string("invoice_id")->nullable();
            $table->string("qr_image")->nullable();
            $table->string("status");
            $table->date("paid_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
