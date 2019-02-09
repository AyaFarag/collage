<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Video;

use App\Http\Resources\VideoResource;

class VideoController extends Controller
{
    public function index() {
        return VideoResource::collection(Video::latest() -> paginate());
    }
}
