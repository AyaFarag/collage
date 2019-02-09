<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Gallery;

use App\Http\Resources\GalleryResource;

class GalleryController extends Controller
{
    public function index() {
        return GalleryResource::collection(Gallery::latest() -> paginate());
    }
}
