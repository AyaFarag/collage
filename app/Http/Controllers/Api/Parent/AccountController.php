<?php

namespace App\Http\Controllers\Api\Parent;

use App\Http\Controllers\Api\AuthenticatableController;

use App\Models\ParentModel;

use App\Http\Resources\ParentResource;

class AccountController extends AuthenticatableController
{
    public function loginGuard() {
        return auth("parent");
    }

    public function guard() {
        return auth("parent-api");
    }

    public function sendLoginResponse($parent) {
        return new ParentResource($parent);
    }

    public function model() {
    	return ParentModel::class;
    }
}