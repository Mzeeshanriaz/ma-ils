<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable=['email_id','path','name','type'];

    public function email(){
        return $this->belongsTo(Email::class);
    }

}
