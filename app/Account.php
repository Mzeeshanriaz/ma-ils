<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable=[
        'email'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emails(){
      return $this->hasMany(Email::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function attachments(){
        return $this->hasManyThrough('App\Attachment','App\Email');
    }
}