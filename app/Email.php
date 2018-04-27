<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Email extends Model
{
    protected $fillable = ['email_id','subject','name','box','account_id'];

    public function account(){
        $this->belongsTo(Account::class);
    }

    public function attachments(){
        $this->hasMany(Attachment::class);
    }
}
