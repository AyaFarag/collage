<?php

namespace App\Http\Controllers\Api\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\ScheduleResource;

class ScheduleController extends Controller
{
    public function index() {
    	$teacher = auth("teacher-api") -> user();
    	$teacher -> load(
    		"currentSemesterSessions.class.level",
    		"currentSemesterSessions.class.studentsCount",
    		"currentSemesterSessions.schedules", 
    		"currentSemesterSessions.subject"
    	);
    	return new ScheduleResource($teacher -> currentSemesterSessions);
    }
}
