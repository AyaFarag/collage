<?php

namespace App\Http\Controllers\Api\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Quiz;
use App\Models\Session;

use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizSnippetResource;

use App\Http\Requests\Api\Teacher\QuizRequest;

class QuizController extends Controller
{
    public function index(Session $session, Request $request) {
        $this -> authorize("view", $session);

    	return QuizSnippetResource::collection($session -> quizzes() -> paginate());
    }

    public function store(Session $session, QuizRequest $request) {
        $this -> authorize("view", $session);

        $quiz = new Quiz($request -> all());
        $quiz -> session_id = $session -> id;
        $quiz -> save();

        return new QuizResource($quiz);
    }

    public function update(Session $session, Quiz $quiz, QuizRequest $request) {
        $this -> authorize("view", $session);

        $quiz -> update($request -> all());

        return new QuizResource($quiz);
    }

    public function show(Session $session, Quiz $quiz) {
        $this -> authorize("view", $session);
        
        return new QuizResource($quiz);
    }

    public function destroy(Session $session, Quiz $quiz) {
        $this -> authorize("view", $session);

        $quiz -> delete();

        return response() -> json(["message" => trans("api.deleted_successfully")], 200);
    }
}
