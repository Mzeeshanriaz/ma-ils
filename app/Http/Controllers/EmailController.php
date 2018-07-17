<?php
namespace App\Http\Controllers;
use App\Account;
use App\Attachment;
use App\Classes\ServiceClass;
use App\Email;
use App\Http\Requests\GmailRequest;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    protected $userDb;
    public function __construct()
    {
        $this->middleware('IsGmailLogin')->except('getEmailData');
    }
    public function store(GmailRequest $request)
    {
        ServiceClass::sendMail($request);
        return redirect()->route('inbox',['box'=>'sent']);
    }
    public function index($box='inbox',$inbox_id=1)
    {
        $token = LaravelGmail::getToken();
        $account=Account::where('email',$token['email'])->firstOrFail();
        $messages = LaravelGmail::message($token)->in($box)->all();
        foreach ( $messages as $message ){
            if( !Email::where(['email_id'=>$message->id,'account_id'=>$account->id])->exists()) {
                $message = $message->load(); //get the Message
                $email=$this->create($message,$box,$account);
                if($message->hasAttachments())
                    foreach($message->getAttachments() as $attachment){
                        ServiceClass::createAttachment($attachment,$email);
                    }
            }
        }
        $messages=ServiceClass::getMessages($box,$account,$inbox_id);
        return view('show', compact('messages','box'));
    }
    /**
     * @param $id
     * @return view
     */
    public function show($id)
    {
        Email::where('email_id',$id)->update(['is_unread'=>0]);
        $email=Email::where('email_id',$id)->firstOrFail();
        $attachments=Attachment::where('email_id',$email->id)->get();
        $message = LaravelGmail::message()->preload()->get($id);
        $message->removeLabel('UNREAD');
        $dated=$email->dated;
        $message_body=$message->getHtmlBody();
        if( $message->getBody( $type = 'text/plain' )==null) {
           $message_body=ServiceClass::getBody($id);
        }
        return view('single',
            compact('message', 'attachments', 'message_body','dated'));
    }
    public function create($message,$box,$account){
        $email=Email::create([
            'email_id' => $message->id,
            'subject' => $message->getSubject(),
            'name' => $message->getFromName(),
            'box' =>$box,
            'box_id'=>ServiceClass::getCategory($message->getLabels()),
            'is_unread'=>ServiceClass::isUnread($message->getLabels()),
            'dated'=>$message->getDate(),
            'account_id'=>$account->id
        ]);
        return $email;
    }
    public function destroy()
    {
        LaravelGmail::logout();
        return redirect()->route('login');
    }
    /**
     * @param Request $request
     * @return redirect
     * @internal param $request
     */
    public function trash(Request $request){
        foreach($request->email as $id){
            $message = LaravelGmail::message()->preload()->get($id);
            $message->sendToTrash();
            Email::where('email_id',$id)->update(['box'=>'trash']);
        }
        return redirect()->route('inbox',['box'=>'trash']);
    }
    public function getEmailData(){
        $fileArray = array('email.html','email5.html','email6.html');
        foreach($fileArray as $file){
            $email = new Email();
            $myfile = fopen(public_path($file), "r") or die("Unable to open file!");
            $text =  fread($myfile,filesize($file));
            fclose($myfile);
            $data = new \stdClass();
            $data->deposit_code = $email->getDepositCode($text);
            $data->full_name = $email->getFullName($text);
            $data->first_name = $email->getFirstName($text);
            $data->last_name = $email->getLastName($text);
            $data->replyTo = $email->getReplyTo($text);
            $data->payTo = $email->getPayTo($text);
            $data->total = $email->getTotal($text);
            $data->message = $email->getMessage($text);
            $data->type = $email->getType($text);
            $data->expires_on = $email->expiresOn($text);
            $data = (array)$data;
            foreach($data as $key=>$d) $data2[camel_case($key)] = $d;
            $fileArray[] = $data2;
        }
        return dd($fileArray);
    }
}