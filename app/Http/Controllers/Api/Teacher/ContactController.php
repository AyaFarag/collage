<?php

namespace App\Http\Controllers\Api\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\ContactResource;

use App\Models\Branch;

class ContactController extends Controller
{
    public function index() {
        $teacher = auth("teacher-api") -> user();
        
        return new ContactResource($teacher -> branch);
    }
}
