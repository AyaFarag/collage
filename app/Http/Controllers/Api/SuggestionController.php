<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Api\SuggestionRequest;

use App\Models\Suggestion;

class SuggestionController extends Controller
{
    public function store(SuggestionRequest $request) {
        $user = auth('parent-api') -> user() ?? auth('teacher-api') -> user() ?? auth('student-api') -> user();

        $user -> suggestions() -> create($request -> all());

        return response() -> json([
            "message" => trans("api.added_successfully")
        ]);
    }
}
