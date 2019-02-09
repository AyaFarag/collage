<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassStudentYear extends Model
{
	protected $table = "class_student_year";

    public function student() {
    	return $this -> belongsTo(Student::class);
    }

    public function class() {
    	return $this -> belongsTo(ClassModel::class);
    }

    public function year() {
    	return $this -> belongsTo(Year::class);
    }
}
