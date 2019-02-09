<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $fillable = ["student_id", "session_id", "has_permission"];

    protected $casts = [
        "has_permission" => "boolean"
    ];

    public function student() {
    	return $this -> belongsTo(Student::class);
    }

    public function session() {
    	return $this -> belongsTo(Session::class);
    }
}
