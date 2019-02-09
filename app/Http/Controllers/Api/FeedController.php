<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Feed;

use App\Http\Resources\FeedResource;

class FeedController extends Controller
{
    public function index() {
        $feeds = Feed::paginate();

        return FeedResource::collection($feeds);
    }
}
