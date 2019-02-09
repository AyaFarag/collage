<?php


Route::post("login", "AccountController@login");

Route::post("/forget", "AccountController@requestPhoneIdentityConfirm");
Route::post("/forget/token", "AccountController@forgetPassword");
Route::post("/forget/reset/{token}", "AccountController@resetPassword");

Route::group(["middleware" => "auth:student-api"], function () {
	Route::get("contact", "ContactController@index");

	Route::post("change-password", "AccountController@changePassword");
	
	Route::get("schedule", "ScheduleController@index");

	// Route::get("session", "SessionController@index");

	Route::get("/quiz/{quiz}", "QuizController@show");
	Route::post("/quiz/{quiz}/response", "QuizResponseController@store");

	Route::get("top-student/student/{student}", "TopStudentController@index");
	Route::get("top-student/{type}", "TopStudentController@index")
		-> where("type", \App\Models\StudentTotalPoints::TYPE_CLASS . "|" . \App\Models\StudentTotalPoints::TYPE_LEVEL);



	Route::get("/{session}/quiz/{type}", "QuizController@index")
		-> where("type", \App\Models\Quiz::TYPE_NEW . "|" . \App\Models\Quiz::TYPE_OLD);
});