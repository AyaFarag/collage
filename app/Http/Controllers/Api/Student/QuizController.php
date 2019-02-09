<?php

namespace App\Http\Controllers\Api\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Session;
use App\Models\Quiz;

use App\Http\Resources\QuizResource;

class QuizController extends Controller
{
    public function index(Session $session, $type) {
        $this -> authorize("view", $session);

        $quizzes = Quiz::with("responses") -> where("session_id", $session -> id) -> paginate();

        $quizzes = $quizzes -> filter(function ($quiz) use ($type) {
            if ($type === Quiz::TYPE_OLD) {
                foreach ($quiz -> responses as $response) {
                    if ($response -> points !== null)
                        return true;
                }
                return false;
            } else {
                return $quiz -> responses -> every(function ($response) {
                    return $response -> points === null;
                });
            }
        });

        return QuizResource::collection($quizzes);
    }

    public function show(Quiz $quiz) {
        $this -> authorize("view", $quiz -> session);

        return new QuizResource($quiz);
    }
}