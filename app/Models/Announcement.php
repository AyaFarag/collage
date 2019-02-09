<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{

    protected $fillable = ["title", "content", "attachment"];

    public function semesterSession() {
    	return $this -> belongsTo(SemesterSession::class);
    }
}
