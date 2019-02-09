<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("parents", function (Blueprint $table) {
            $table -> increments("id");
            $table -> string("name");
            $table -> string("email") -> unique();
            $table -> string("password");
            $table -> string("phone") -> unique();
            $table -> string("phone_confirmation_code", 4) -> nullable();
            $table -> timestamp("phone_code_created_at") -> default("2017-06-14 15:28:36");
            $table -> string("api_token") -> nullable();
            $table -> string("device_token") -> nullable();
            $table -> boolean("status")->default(0);
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("parents");
    }
}
