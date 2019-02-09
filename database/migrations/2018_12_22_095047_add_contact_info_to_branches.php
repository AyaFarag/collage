<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactInfoToBranches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("branches", function (Blueprint $table) {
            $table -> text("phone_numbers");
            $table -> text("social_networks");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("branches", function (Blueprint $table) {
            $table -> dropColumn("phone_numbers");
            $table -> dropColumn("social_networks");
        });
    }
}
