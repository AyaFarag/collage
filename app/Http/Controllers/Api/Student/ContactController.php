<?php

namespace App\Http\Controllers\Api\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\ContactResource;

use App\Models\Branch;

class ContactController extends Controller
{
    public function index() {
        $student = auth("student-api") -> user();
        
        return new ContactResource($student -> branch);
    }
}
