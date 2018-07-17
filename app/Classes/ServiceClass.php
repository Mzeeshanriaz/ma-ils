<?php
namespace App\Classes;
use App\Attachment;
use App\Email;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Http\Request;
use MartijnWagena\Gmail\Mail;
use Dacastro4\LaravelGmail\Services\Message\Mail as DMail;

class ServiceClass{
    public static function getBody($id){
        $access=LaravelGmail::getToken();
        /*Call to package by Token */
        $mails = Mail::create()
            ->setAccessToken($access['access_token'],$access['refresh_token']);
        $my_email= $mails->getMessage( $id , $id );
        $message_body  = $my_email['body']['html'];     // text / html
        if(empty($message_body)) {
            $message_body = $my_email['body']['text'];
        }
        return $message_body;
    }
    public static function getCategory($labels){

        $is_social=0;
        $label_id=array_search("CATEGORY_SOCIAL",$labels);
        if($label_id!==false){
            $is_social=1;
        }
        $is_promotion=0;
        $label_id_2=array_search("CATEGORY_PROMOTIONS",$labels);
        if($label_id_2!==false){
            $is_promotion=1;
        }
        if($is_promotion==1) $box_id=3;
        else if($is_social==1) $box_id=2;
        else $box_id=1;

        return $box_id;
    }
    public static function isUnread($label){
        $is_unread=1;
        $label_id_unread=array_search("UNREAD",$label);
        if($label_id_unread===false){
            $is_unread=0;
        }
        return $is_unread;

    }
    public static function createAttachment($attachment,$email){
        $path='folder'.rand(1,3).'/';
        $filename=rand(0,99).'-'.rand(1,999).str_replace(" ","-",$attachment->filename);
        $attachment->saveAttachmentTo( $path,  $filename, 'local');  ###problem
        $path=$path.$filename;
        Attachment::create([
            'email_id'=>$email->id,
            'name'=>$attachment->filename,
            'type'=>$attachment->mimeType,
            'path'=>$path,
        ]);
    }
    public static function sendMail(Request $request){
        $mail= new DMail();
        $mail->to($request->email);
        $mail->subject($request->subject);
        $mail->message($request->message);
        $mail->setHeader('MIME-Version',' 1.0');
        $mail->setHeader('Content-type',' text/html');
        $filename='';
        if($request->file('file')) {
            $extension=$request->file('file')->getClientOriginalExtension();
            $filename=rand(0,9).'-'.time().'-'.rand(0,999).'.'.$extension;
            $request->file('file')->move(public_path(),$filename);
            $mail->attach(public_path().'/'.$filename);
        }
        $mail->send();
        if($request->file('file')) unlink(public_path().'/'.$filename);
    }

    public static function getMessages($box,$account,$inbox_id){

        if($box!="inbox"){
            $messages=Email::where('account_id',$account->id)
                ->where(['box'=>$box])->orderBy('email_id','DESC')->paginate(20);
        }else {
            $messages = Email::where([
                'account_id'=> $account->id,
                'box' => $box,
                'box_id' => $inbox_id
            ])->orderBy('email_id', 'DESC')->paginate(20);
        }
        return $messages;
    }
}