<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("students", function (Blueprint $table) {
            $table -> increments("id");
            $table -> integer("branch_id") -> unsigned();
            $table -> integer("parent_id") -> unsigned();
            $table -> string("ssn"); // student uniqe id
            $table -> string("name");
            $table -> string("phone") -> unique();
            $table -> enum("gender", ["male", "female", "other"]);
            $table -> string("password");
            $table -> date("birth_date");
            $table -> string("nationality");
            $table -> string("phone_confirmation_code", 4) -> nullable();
            $table -> timestamp("phone_code_created_at") -> default("2017-06-14 15:28:36");
            $table -> string("api_token") -> nullable();
            $table -> string("device_token") -> nullable();
            $table -> timestamps();

            $table
                -> foreign("branch_id")
                -> references("id")
                -> on("branches")
                -> onDelete("cascade");
            $table
                -> foreign("parent_id")
                -> references("id")
                -> on("parents")
                -> onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("students");
    }
}
