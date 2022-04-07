<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("notifs", function (Blueprint $table) {
            $table->id();
            $table->integer("status")->default(1);
            $table->longText("message");
            $table->string("type")->default("alert");
            $table->string("for")->default("All");
            $table->string("created_by")->default("SYSTEM");
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
        Schema::dropIfExists("notifs");
    }
}