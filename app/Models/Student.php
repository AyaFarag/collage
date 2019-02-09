<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;


use Hash;

class Student extends Authenticatable implements HasMedia
{
    use HasMediaTrait;

    protected $fillable = [
    	"branch_id",
    	"parent_id",
    	"ssn",
    	"name",
    	"phone",
    	"gender",
    	"password",
    	"birth_date",
    	"nationality",
    	"device_token",
        "address"
    ];

    protected $dates = ["birth_date"];

    protected $hidden = ["password"];

    //===============================================================
    //
    // RELATIONS
    //
    //===============================================================

    public function parent() {
    	return $this -> belongsTo(ParentModel::class);
    }

    public function branch() {
    	return $this -> belongsTo(Branch::class);
    }

    public function quizResponses() {
        return $this -> hasMany(QuizResponse::class);
    }

    public function years() {
        return $this -> belongsToMany(Year::class, "class_student_year")
            -> withPivot("joined_bus", "seat_number", "class_id")
            -> latest();
    }

    public function passwordReset() {
        return $this -> morphOne(PasswordReset::class, "user");
    }

    public function absences() {
        return $this -> belongsToMany(Session::class, "absences");
    }

    public function classes() {
        return $this -> belongsToMany(ClassModel::class, "class_student_year", "student_id", "class_id")
            -> withPivot("joined_bus", "seat_number", "year_id")
            -> latest();
    }

    public function suggestions() {
        return $this->morphMany(Suggestion::class, 'user');
    }

    public function studentOfDayCount() {
        return $this -> hasOne(StudentOfDay::class)
            -> selectRaw("COUNT(*) as aggregate, student_id")
            -> groupBy("student_id");
    }

    public function totalPoints() {
        return $this -> hasMany(StudentTotalPoints::class);
    }

    //===============================================================
    //
    // GETTERS
    //
    //===============================================================

    public function getStudentOfDayCountAttribute() {
        if (!array_key_exists("studentOfDayCount", $this -> relations))
            $this -> load("studentOfDayCount");

        $relation = $this -> getRelation("studentOfDayCount");

        return $relation ? $relation -> aggregate : 0;
    }

    public function getClassAttribute() {
        return $this -> classes() -> first();
    }

    public function getYearAttribute() {
        return $this -> years() -> first();
    }

    //===============================================================
    //
    // SETTERS
    //
    //===============================================================

    public function setPasswordAttribute($value) {
        $this -> attributes["password"] = Hash::needsReHash($value)
            ? Hash::make($value)
            : $value;
    }



    //===============================================================
    //
    // AUTHORIZATION
    //
    //===============================================================

    public function canViewSession(Session $session) {
        $class = $this -> class;
        $count = $class -> semesterSessions()
            -> whereHas("sessions", function ($query) use ($session) {
                $query -> whereId($session -> id);
            }) -> count();
        return $count > 0;
    }

    public function canViewQuizById($quiz_id) {
        $class = $this -> class;
        $count = $class -> semesterSessions()
            -> where("year_id", $class -> pivot -> year_id)
            -> whereHas("sessions.quizzes", function ($query) use ($quiz_id) {
                $query -> whereId($quiz_id);
            }) -> count();
        return $count > 0;
    }

    //===============================================================
    //
    // SCOPES
    //
    //===============================================================

    public function scopeSearch($query, $search) {
        return $query -> where("name", "like", "%{$search}%");
    }

    public function scopeFilter($query, $attributes) {
        if (!empty($attributes["query"]))
            $query -> search($attributes["query"]);
        if (!empty($attributes["level_id"])) {
            $query -> whereHas("class", function ($query) use ($attributes) {
                $query -> where("level_id", $attributes["level_id"]);
            });
        }

        if (!empty($attributes["branch"]))
            $query -> where("branch_id", $attributes["branch"]);
        if (!empty($attributes["ssn"]))
            $query -> where("ssn", $attributes["ssn"]);
    }
}
