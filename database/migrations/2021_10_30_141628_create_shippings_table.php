<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("Shippings", function (Blueprint $table) {
            $table->id();
            $table->string("location");
            $table->integer("admin");
            $table->integer("support");
            $table->integer("pagesCoordinator");
            $table->integer("leader");
            $table->integer("marketer");
            $table->integer("seller");
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
        Schema::dropIfExists("Shippings");
    }
}
