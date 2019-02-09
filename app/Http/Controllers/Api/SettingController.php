<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Setting;

class SettingController extends Controller
{
    public function about_us() {
        return response() -> json(["data" => Setting::first() -> about_us], 200);
    }
}
