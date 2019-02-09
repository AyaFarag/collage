<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
	protected $fillable = ["token"];

    public function user() {
    	return $this -> morphTo();
    }
}
