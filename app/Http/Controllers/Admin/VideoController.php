<?php

namespace App\Http\Controllers\Admin;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VideoRequest;

class VideoController extends Controller
{
    public function index()
    {
        $video = Video::paginate();

        return view("admin.video.index", compact("video"));
    }

    public function create()
    {
        return view("admin.video.create");
    }

    public function store(VideoRequest $request)
    {
        $video = Video::create($request -> all());
        
        return redirect() -> route("admin.video.index")->with('success','Done');
    }

    public function edit(Video $video)
    {
        return view("admin.video.edit", compact("video"));
    }

    public function update(VideoRequest $request, Video $video)
    {
        $video->update($request -> all());

        return redirect() -> route("admin.video.index")->with('success','Done');
    }

    public function destroy(Video $video)
    {
        $video -> delete();
        return redirect() -> route("admin.video.index") ->with('success','Done');
    }
}
