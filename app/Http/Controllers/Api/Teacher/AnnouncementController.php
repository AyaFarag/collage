<?php

namespace App\Http\Controllers\Api\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Session;

use App\Models\Announcement;

use App\Http\Resources\AnnouncementResource;
use App\Http\Requests\Api\Teacher\AnnouncementRequest;

class AnnouncementController extends Controller
{
    public function index(Session $session, Request $request) {
        $this -> authorize("view", $session);
    	return AnnouncementResource::collection($session -> announcements() -> paginate());
    }

    public function store(Session $session, AnnouncementRequest $request) {
        $this -> authorize("view", $session);

        $announcement = new Announcement($request -> all());
        $announcement -> session_id = $session -> id;
        $announcement -> save();

        return response() -> json(["message" => trans("api.added_successfully")], 201);
    }

    public function update(Session $session, Announcement $announcement, AnnouncementRequest $request) {
        $this -> authorize("view", $session);

        $announcement -> update($request -> all());

        return response() -> json(["message" => trans("api.updated_successfully")]);
    }

    public function destroy(Session $session, Announcement $announcement) {
        $this -> authorize("view", $session);

        $announcement -> delete();

        return response() -> json(["message" => trans("api.deleted_successfully")], 200);
    }
}
