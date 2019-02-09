<?php

namespace App\Http\Controllers\Api\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Level;

use App\Http\Resources\LevelResource;

class LevelController extends Controller
{
    public function index() {
    	$teacher = auth("teacher-api") -> user();

    	$levels = Level::with("classes")
    		-> whereHas("classes.currentSemesterSessions", function ($query) use ($teacher) {
	    		$query -> where("teacher_id", $teacher -> id);
	    	}) -> paginate();

    	return LevelResource::collection($levels);
    }
}
