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
        Schema::create("orders", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->longText("phone");
            $table->longText("address");
            $table->string("discount");
            $table->string("discount_type");
            $table->string("created_by");
            $table->string("status");
            $table->longText("note")->nullable();
            $table->string("Shipping_to");
            $table->string("Shipping_location");
            $table->string("Shipping_fees");
            $table->string("Shipping_status");
            $table->string("Shipping_company")->nullable();
            $table->longText("Shipping_note")->nullable();
            $table->integer("updated_by")->nullable();
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
        Schema::dropIfExists("orders");
    }
}