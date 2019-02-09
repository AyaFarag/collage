<?php

namespace App\Http\Controllers\Api\Parent;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\ContactResource;

use App\Models\Student;
use App\Models\Branch;

class ContactController extends Controller
{
    public function index(Student $child) {
        return new ContactResource($child -> branch);
    }
}
