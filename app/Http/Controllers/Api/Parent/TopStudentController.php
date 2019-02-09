<?php

namespace App\Http\Controllers\Api\Parent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Year;
use App\Models\Student;
use App\Models\StudentTotalPoints;

use App\Services\StudentService;

use App\Http\Resources\TopStudentResource;
use App\Http\Resources\StudentSnippetResource;
use App\Http\Resources\ClassResource;

class TopStudentController extends Controller
{
    public function index(Student $student, $type) {
    	if ($type === StudentTotalPoints::TYPE_CLASS) {
	    	$students = StudentService::currentYearTopStudents(null, $student -> class);
    	} else {
    		$students = StudentService::currentYearTopStudents($student -> class -> level);
    	}

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
