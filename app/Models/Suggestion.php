<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $fillable = ["title", "description"];

    public function user() {
        return $this->morphTo();
    }
}
