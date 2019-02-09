<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTotalPoints extends Model
{
	const TYPE_LEVEL = "level";
	const TYPE_CLASS = "class";

	protected $table = "students_total_points";

	protected $fillable = ["semester_session_id", "student_id", "points"];

    public function student() {
    	return $this -> belongsTo(Student::class);
    }

    public function semesterSession() {
    	return $this -> belongsTo(SemesterSession::class);
    }
}
