<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Api\AuthenticatableController;

use App\Models\Teacher;

use App\Http\Resources\TeacherResource;

class AccountController extends AuthenticatableController
{
    public function loginGuard() {
        return auth("teacher");
    }

    public function guard() {
        return auth("teacher-api");
    }

    public function sendLoginResponse($teacher) {
        return new TeacherResource($teacher);
    }

    public function model() {
    	return Teacher::class;
    }
}
