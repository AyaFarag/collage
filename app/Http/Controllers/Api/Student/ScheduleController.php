<?php

namespace App\Http\Controllers\Api\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\ScheduleResource;

class ScheduleController extends Controller
{
    public function index() {
		$student = auth("student-api") -> user();
		$class   = $student -> classes() -> first();
	  	$class -> load(
	  		"currentSemesterSessions.class.level",
	  		"currentSemesterSessions.class.studentsCount",
	  		"currentSemesterSessions.schedules",
	  		"currentSemesterSessions.subject"
	  	);
    	return new ScheduleResource($class -> semesterSessions);
    }
}
