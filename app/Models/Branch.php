<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ["name", "lat", "lng", "address", "phone_numbers", "social_networks"];

    protected $casts = [
        "phone_numbers" => "array",
        "social_networks" => "array"
    ];

    static function selectOptions() {
    	return self::orderBy("name") -> pluck("name", "id") -> all();
    }

    public function teachers() {
    	return $this -> hasMany(Teacher::class);
    }

    public function students() {
    	return $this -> hasMany(Student::class);
    }
}
