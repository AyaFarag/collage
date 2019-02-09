<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSemesterSessionIdToSessionIdInAnnouncements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table -> dropForeign('announcements_semester_session_id_foreign');
            $table -> dropColumn("semester_session_id");
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table -> integer("session_id") -> unsigned();
            $table
                -> foreign("session_id")
                -> references("id")
                -> on("sessions")
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
        Schema::table('announcements', function (Blueprint $table) {
            $table -> integer("semester_session_id") -> unsigned();
            $table
                -> foreign("semester_session_id")
                -> references("id")
                -> on("semester_sessions")
                -> onDelete("cascade");
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table -> dropColumn("session_id");
        });
    }
}
