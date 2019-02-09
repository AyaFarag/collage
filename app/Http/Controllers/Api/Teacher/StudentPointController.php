<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Teacher\StudentPointRequest;

use App\Models\StudentPoint;

class StudentPointController extends Controller
{ 
    public function store(StudentPointRequest $request) {
        StudentPoint::create($request -> all());

        return response() -> json(["message" => trans("api.added_successfully")]);
    }
}