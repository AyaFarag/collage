<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;


use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;


use Hash;

class Teacher extends Authenticatable implements HasMedia
{
    use HasMediaTrait;

    protected $fillable = [
    	"branch_id",
    	"name",
    	"email",
    	"password",
    	"phone",
    	"description",
    	"address",
    	"gender",
    	"birth_date",
    	"nationality",
    	"device_token"
    ];

    protected $dates = ["birth_date"];

    static function selectOptions() {
        return self::with("branch")
            -> orderBy("branch_id")
            -> get()
            -> reduce(function ($cr, $teacher) {
                $cr[$teacher -> id] = "{$teacher -> branch -> name} - {$teacher -> name}";
                return $cr;
            }, []);
    }

    public function classes() {
        return $this -> belongsToMany(ClassModel::class, "semester_sessions", "teacher_id", "class_id");
    }

    public function branch() {
        return $this -> belongsTo(Branch::class);
    }

    public function sessions() {
    	return $this -> hasMany(Session::class);
    }

    public function semesterSessions() {
    	return $this -> hasMany(SemesterSession::class);
    }

    public function currentSemesterSessions() {
        return $this -> hasMany(SemesterSession::class)
            -> where("year_id", Year::current() -> id);
    }

    public function passwordReset() {
        return $this -> morphOne(PasswordReset::class, "user");
    }

    public function quizzes() {
        return $this -> hasManyThrough(Quiz::class, Session::class)
            -> whereHas("session.semesterSession", function ($query) {
                $query -> where("year_id", Year::current() -> id);
            });
    }

    public function suggestions() {
        return $this->morphMany(Suggestion::class, 'user');
    }

    public function canViewSession(Session $session) {
        return $session -> semesterSession -> teacher_id === $this -> id;
    }


    public function setPasswordAttribute($value) {
        $this -> attributes["password"] = Hash::needsReHash($value)
            ? Hash::make($value)
            : $value;
    }

    public function scopeSearch($query, $search) {
        return $query -> where(function ($query) use ($search) {
            $query -> where("name", "like", "%{$search}%")
                -> orWhere("email", "like", "%{$search}%");
        });
    }
}
