<?php
namespace App\Http\Controllers;
use App\Account;
use App\Attachment;
use App\Email;
use App\Http\Requests\GmailRequest;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Dacastro4\LaravelGmail\Services\Message\Mail;
use Illuminate\Support\Facades\Storage;

class EmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('IsGmailLogin')->only('show','index','store','destroy');
    }
    public function store(GmailRequest $request)
    {
        $mail= new Mail();
        $mail->to($request->email);
        $mail->subject($request->subject);
        $mail->message($request->message);
        $mail->setHeader('MIME-Version',' 1.0');
        $mail->setHeader('Content-type',' text/html');
        $mail->attach(storage_path().'/app/folder3/17a.png');
        $mail->send();
        return redirect()->route('inbox',['box'=>'sent']);
    }
    public function index($box='inbox')
    {
        $token = LaravelGmail::getToken();
        $account=Account::where('email',$token['email'])
                    ->firstOrFail();
        $messages = LaravelGmail::message($token)
                                ->in($box)->all();
        foreach ( $messages as $message ){
            $email_id= $message->id;
            if(!Email::where('email_id',$email_id)->where('account_id',$account->id)->exists()) {
                $message = $message->load();
                if($message->getSubject()=='') $subject='';
                else $subject= $message->getSubject();
                $name= $message->getFromName(); //get the sender name
                $email=Email::create([
                    'email_id' => $email_id,
                    'subject' => $subject,
                    'name' => $name,
                    'box' =>$box,
                    'account_id'=>$account->id
                ]);
                if($message->hasAttachments())
                    foreach($message->getAttachments() as $attachment){
                        $path='folder'.rand(1,3).'/';
                        $attachment->saveAttachmentTo( $path,  null, 'local');
                        $path=$path.$attachment->filename;
                        Attachment::create([
                            'email_id'=>$email->id,
                            'name'=>$attachment->filename,
                            'type'=>$attachment->mimeType,
                            'path'=>$path,
                        ]);
                    }
            }
        }
        $messages=Email::where('account_id',$account->id)
                    ->where('box',$box)
                    ->orderBy('id','DESC')->get();
        return view('show', compact('messages'));
    }
    /*
     * @param id
     */
    public function show($id)
    {
        $email=Email::where('email_id',$id)->firstOrFail();
        $attachments=Attachment::where('email_id',$email->id)->get();
        $messages = LaravelGmail::message()->preload()->get($id);
        return view('single',compact('messages','attachments'));
    }
    public function destroy()
    {
        LaravelGmail::logout();
        return redirect()->route('login');
    }
}