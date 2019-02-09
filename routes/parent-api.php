<?php


Route::post("login", "AccountController@login");

Route::post("/forget", "AccountController@requestPhoneIdentityConfirm");
Route::post("/forget/token", "AccountController@forgetPassword");
Route::post("/forget/reset/{token}", "AccountController@resetPassword");

Route::group(["middleware" => ["auth:parent-api"]], function () {
	Route::post("change-password", "AccountController@changePassword");
	Route::get("children", "StudentController@index");

	Route::group(["prefix" => "{child}"], function () {
		Route::get("contact", "ContactController@index");

		Route::get("schedule", "ScheduleController@index");

		Route::get("top-student/student/{student}", "TopStudentController@show");
		Route::get("top-student/{type}", "TopStudentController@index")
			-> where("type",\App\Models\StudentTotalPoints::TYPE_CLASS . "|" . \App\Models\StudentTotalPoints::TYPE_LEVEL);
	});
});