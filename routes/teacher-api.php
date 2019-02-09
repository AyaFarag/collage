<?php


Route::post("login", "AccountController@login");

Route::post("/forget", "AccountController@requestPhoneIdentityConfirm");
Route::post("/forget/token", "AccountController@forgetPassword");
Route::post("/forget/reset/{token}", "AccountController@resetPassword");

Route::group(["middleware" => "auth:teacher-api"], function () {

	Route::get("contact", "ContactController@index");


	Route::post("change-password", "AccountController@changePassword");

	//===============================================================
	//
	// SCHEDULE
	//
	//===============================================================

	Route::get("schedule", "ScheduleController@index");



	//===============================================================
	//
	// SESSIONS
	//
	//===============================================================

	Route::resource("session", "SessionController") -> only("index", "store", "show", "update");
	



	//===============================================================
	//
	// QUIZZES
	//
	//===============================================================

	Route::resource("{session}/quiz", "QuizController") -> only("index", "store", "show", "update", "destroy");


	//===============================================================
	//
	// ANNOUNCEMENTS
	//
	//===============================================================

	Route::resource("{session}/announcement", "AnnouncementController") -> only("index", "store", "update", "destroy");



	//===============================================================
	//
	// QUIZ RESPONSES & GRADES
	//
	//===============================================================

	Route::get("quiz/{quiz}/response", "QuizResponseController@index");
	Route::post("quiz/{quiz}/response/{quiz_response}", "QuizResponseController@award");
	Route::delete("quiz/{quiz}/response/{quiz_response}", "QuizResponseController@destroy");

	//===============================================================
	//
	// CUSTOM POINTS
	//
	//===============================================================

	Route::post("point", "StudentPointController@store");


	//===============================================================
	//
	// LEVELS
	//
	//===============================================================

	Route::get("level", "LevelController@index");


	//===============================================================
	//
	// TOP STUDENTS
	//
	//===============================================================

	Route::get("top-student/student/{student}", "TopStudentController@show");
	Route::get("top-student/{level}", "TopStudentController@index");
});