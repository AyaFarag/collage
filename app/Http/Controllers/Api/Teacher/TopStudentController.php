<?php

namespace App\Http\Controllers\Api\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Student;
use App\Models\Year;

use App\Http\Resources\TopStudentResource;
use App\Http\Resources\ClassResource;

use App\Services\StudentService;

class TopStudentController extends Controller
{
    public function index($level_id) {
    	$teacher = auth("teacher-api") -> user();

    	$level = $teacher -> classes() -> where("level_id", $level_id) -> firstOrFail();

    	$students = StudentService::currentYearTopStudents($level);

    	return TopStudentResource::collection($students);
    }

    public function show(Student $student) {
    	$service = new StudentService($student, Year::current());

    	return response() -> json([
			"id"             => $student -> id,
			"name"           => $student -> name,
			"class"          => new ClassResource($student -> class),
			"quiz"           => $service -> totalQuizPoints(),
			"other"          => $service -> totalOtherPoints(),
			"student_of_day" => $service -> studentOfDayCount() * config("defaults.student_of_day_multiplier")
    	]);
    }
}
