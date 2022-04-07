<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("role");
            $table->string("phone");
            $table->integer("leader_id")->nullable();
            $table->integer("leader_ratio")->nullable();

            $table->decimal("active_balance")->default(0);
            $table->decimal("pending_balance")->default(0);
            $table->decimal("leader_balance")->default(0);
            $table->decimal("withdraw_balance")->default(0);
            $table->decimal("withdraw_done_balance")->default(0);
            $table->dateTime("last_login")->default(date("y-m-d G:i:s"));
            $table->string("blocked")->default("true");
            $table->string("email")->unique();
            $table->timestamp("email_verified_at")->nullable();
            $table->string("password");
            $table->rememberToken();
            $table->timestamps();
        });
        info("Hello from users migrations ...");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("users");
    }
}