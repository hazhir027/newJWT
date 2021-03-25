<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{



    public function visits(){
        return $this->hasMany(Visit::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function favorites(){
        return $this->hasMany(Visit::class);
    }
}
