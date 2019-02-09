<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentTotalPoints;
use App\Models\Year;
use App\Models\Level;

class StudentService {
	private $student;
	private $year;

    static function currentYearTopStudents($level = null, $class = null, $limit = 10) {
        $year = Year::latest() -> first();
        $totalPoints = StudentTotalPoints::with("student.classes.level");
        if ($class) {
            $totalPoints -> whereHas("student.classes", function ($query) use ($class) {
                $query -> where("classes.id", $class -> id);
            });
        }
        if ($level) {
            $totalPoints -> whereHas("student.classes", function ($query) use ($level) {
                $query -> where("level_id", $level -> id);
            });
        }
        return $totalPoints -> where("points", ">", 0)
            -> orderBy("points", "DESC")
            -> limit($limit) -> get();
    }

    public function __construct(Student $student, Year $year) {
        $this -> student = $student;
        $this -> year    = $year;
    }


	public function studentOfDayCount() : int {
		return $this -> year
			-> joinStudentsOfDay()
            -> where("students_of_day.student_id", $this -> student -> id)
            -> count();
    }

	public function totalQuizPoints() : float {
		return $this -> year
			-> joinQuizResponses()
            -> where("quiz_responses.student_id", $this -> student -> id)
            -> selectRaw("SUM(quiz_responses.points) as total")
            -> first()
            -> total
            ?? 0;
    }

    public function totalOtherPoints() : float {
    	return $this -> year
    		-> joinStudentPoints()
    		-> where("student_points.student_id", $this -> student -> id)
    		-> selectRaw("SUM(points) as total")
    		-> first()
    		-> total
    		?? 0;
    }

    public function absenceCount() : int {
    	return $this -> year
    		-> joinAbsences()
    		-> where("absences.student_id", $this -> student -> id)
    		-> count();
    }
}