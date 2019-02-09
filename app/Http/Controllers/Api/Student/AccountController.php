<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Api\AuthenticatableController;

use App\Models\Student;

use App\Http\Resources\StudentResource;

class AccountController extends AuthenticatableController
{
    public function loginGuard() {
        return auth("student");
    }

    public function guard() {
        return auth("student-api");
    }

    public function sendLoginResponse($student) {
        return new StudentResource($student);
    }

    public function getUsernameAttribute() {
        return "ssn";
    }

    public function model() {
        return Student::class;
    }
}
