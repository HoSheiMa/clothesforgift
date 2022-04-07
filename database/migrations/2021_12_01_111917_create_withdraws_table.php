<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("withdraws", function (Blueprint $table) {
            $table->id();
            $table->integer("money_needed");
            $table->integer("receiver");
            $table->string("receiver_name");
            $table->string("receiver_details");
            $table->string("type");
            $table->string("status")->default("await");
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
        Schema::dropIfExists("withdraws");
    }
}