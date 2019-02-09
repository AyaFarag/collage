<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\StudentTotalPoints;
use App\Models\Student;
use App\Models\SemesterSession;
use App\Models\Year;

class CacheStudentsPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "points:cache";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Cache the the points of every student to make it easier to get the totals";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this -> line("Caching points...");
        $this -> line("This may take a few minutes!");
        // This has been tested
        StudentTotalPoints::query() -> truncate();

        $currentYearId = Year::current() -> id;

        $semesterSessions = SemesterSession::with("class.students")
            -> where("year_id", $currentYearId)
            -> get();
        foreach ($semesterSessions as $semesterSession) {
            foreach ($semesterSession -> class -> students as $student) {
                $quiz_points = $semesterSession
                    -> leftJoin("sessions", "sessions.semester_session_id", "=", "semester_sessions.id")
                    -> leftJoin("quizzes", "quizzes.session_id", "=", "sessions.id")
                    -> leftJoin("quiz_responses", "quiz_responses.quiz_id", "=", "quizzes.id")
                    -> where("quiz_responses.student_id", $student -> id)
                    -> where("semester_sessions.id", $semesterSession -> id)
                    -> selectRaw("SUM(points) as total")
                    -> first()
                    -> total ?? 0;

                $other_points = $semesterSession
                    -> leftJoin("sessions", "sessions.semester_session_id", "=", "semester_sessions.id")
                    -> leftJoin("student_points", "student_points.session_id", "=", "sessions.id")
                    -> where("student_points.student_id", $student -> id)
                    -> where("semester_sessions.id", $semesterSession -> id)
                    -> selectRaw("SUM(points) as total")
                    -> first()
                    -> total ?? 0;

                $student_of_day_points = $semesterSession
                    -> leftJoin("sessions", "sessions.semester_session_id", "=", "semester_sessions.id")
                    -> leftJoin("students_of_day", "students_of_day.session_id", "=", "sessions.id")
                    -> where("students_of_day.student_id", $student -> id)
                    -> where("semester_sessions.id", $semesterSession -> id)
                    -> selectRaw("COUNT(student_id) as count")
                    -> first()
                    -> count * config("defaults.student_of_day_multiplier") ?? 0;

                StudentTotalPoints::create([
                    "student_id"          => $student -> id,
                    "semester_session_id" => $semesterSession -> id,
                    "points"              => $quiz_points + $other_points + $student_of_day_points
                ]);
            }
        }

        $this -> info("Students points' were cached successfull!");
    }
}
