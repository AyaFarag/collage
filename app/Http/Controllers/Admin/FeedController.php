<?php

namespace App\Http\Controllers\Admin;

use App\Models\Feed;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeedRequest;


class FeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feed = Feed::paginate();
        return view('admin.feed.index',compact('feed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.feed.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeedRequest $request)
    {
        $feed = Feed::create($request->all());
        
        foreach ($request->file('images') as  $image) {
            $feed->addMedia($image)->toMediaCollection('images');
        }
        return redirect()->route('admin.feed.index')->with('success','Done');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function show(Feed $feed)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function edit(Feed $feed)
    {
        return view('admin.feed.edit',compact('feed'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function update(FeedRequest $request, Feed $feed)
    {
        $feed->update($request->all());
        if ($request -> file("images")) {
            $feed -> clearMediaCollection("images");
            foreach ($request->file('images') as  $image) {
                $feed->addMedia($image)->toMediaCollection('images');
            }
        }
        return redirect()->route('admin.feed.index')->with('success','Done');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feed $feed)
    {
        $feed->delete();
        return back()->with('success','Done');
    }
}
