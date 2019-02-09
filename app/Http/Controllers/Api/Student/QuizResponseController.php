<?php

namespace App\Http\Controllers\Api\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Student\QuizResponseRequest;

use App\Models\Quiz;
use App\Models\QuizResponse;

class QuizResponseController extends Controller
{
    public function store(Quiz $quiz, QuizResponseRequest $request) {
    	$this -> authorize("createResponse", $quiz);

	    $quizResponse = new QuizResponse($request -> all());
	    $quizResponse -> student_id = auth("student-api") -> user() -> id;
	    $quizResponse -> quiz_id    = $quiz -> id;
	    $quizResponse -> save();

        if ($request -> file("attachment")) {
            $quizResponse -> addMedia($request -> file("attachment"))
                -> toMediaCollection("attachments");
        }


        return response() -> json(["message" => trans("api.added_successfully")]);
    }
}
