<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("items", function (Blueprint $table) {
            $table->id();
            $table->string("color_id");
            $table->string("min_price");
            $table->string("max_price");
            $table->string("price");
            $table->string("product_created_by")->default("SYSTEM");
            $table->string("product_id");
            $table->string("color");
            $table->string("size");
            $table->string("needed_price");
            $table->string("benefits");
            $table->string("needed");
            $table->string("order_id");
            $table->string("product_name")->default("لا يوجد");
            $table
                ->string("product_image")
                ->default(
                    "https://www.generationsforpeace.org/wp-content/uploads/2018/07/empty.jpg"
                );
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
        Schema::dropIfExists("items");
    }
}