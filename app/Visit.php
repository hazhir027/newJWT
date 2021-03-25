<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{

    protected $fillable = ['user_id' , 'dr_code'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }


}
