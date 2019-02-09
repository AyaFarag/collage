<?php

Route::group(["middleware" => ["auth:parent-api,student-api,teacher-api"]], function () {
    Route::get("/feed", "FeedController@index");
    Route::get("/gallery", "GalleryController@index");
    Route::get("/video", "VideoController@index");
    Route::get("/about-us", "SettingController@about_us");

    Route::post("/suggest", "SuggestionController@store");
});