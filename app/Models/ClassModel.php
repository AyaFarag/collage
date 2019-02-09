<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
	protected $table = "classes";

    protected $fillable = ["level_id", "name"];

    static function selectOptions() {
        return self::with("level")
            -> orderBy("level_id")
            -> get()
            -> reduce(function ($cr, $class) {
                $cr[$class -> id] = "{$class -> level -> name} - {$class -> name}";
                return $cr;
            }, []);
    }

    public function semesterSessions() {
        return $this -> hasMany(SemesterSession::class, "class_id");
    }

    public function currentSemesterSessions() {
        return $this -> hasMany(SemesterSession::class, "class_id")
            -> where("year_id", Year::latest() -> first(["id"]) -> id);
    }

    public function schedule() {
        return $this -> hasManyThrough(Schedule::class, SemesterSession::class, "class_id", "semester_session_id");
    }

    public function studentsCount()
    {
        return $this->hasOne(ClassStudentYear::class, 'class_id')
            ->selectRaw('student_id, class_id, count(*) as aggregate')
            ->where('year_id', Year::current() -> id)
            ->groupBy('class_id');
    }

    public function subjects() {
        return $this -> belongsToMany(Subject::class, "semester_sessions", "class_id", "subject_id");
    }

    public function students() {
        return $this -> belongsToMany(Student::class, "class_student_year", "class_id")
            -> where("year_id", Year::current() -> id);
    }

    public function level() {
        return $this -> belongsTo(Level::class);
    }

}
