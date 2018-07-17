<?php

namespace App\Http\Controllers;

use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use MartijnWagena\Gmail\Mail;

class TestController extends Controller
{
    public function call(){
        return LaravelGmail::redirect();
    }
    public function test(){
        $id = '163067e23666c002';
        $threadId = "163067e23666c002";
        $access = LaravelGmail::getToken();
        $mails     = Mail::create()
            ->setAccessToken($access['access_token'],$access['refresh_token']);
        $my_email= $mails->getMessage( $id , $threadId );
        $subject   = $my_email['subject'];
        $from_email= $my_email['from']['email'];
        $from_name = $my_email['from']['name'];
        $messages  = $my_email['body']['html'];     // text / html
        if(empty($messages)){
            $messages=$my_email['body']['text'];
        }
        dd( $my_email ,$subject,$from_name,$from_email,$messages);
    }
}
