<?php
namespace App\Http\Controllers;
use App\Account;
use App\Repositories\AccountRepository;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Http\Request;
class AccountController extends Controller
{
    protected $ac;
    public function __construct(AccountRepository $ac)
    {
        $this->ac = $ac;
        $this->middleware('IsGmailLogin')->only('insert');
    }
    public function index(){
        return $this->ac->getall();
    }
    public function create(Request $request){
        if($request->error) return $request->error;
        LaravelGmail::makeToken();
        return redirect(route('user.token.get'));
    }
    public function insert(){
        $token = LaravelGmail::getToken();
        $email=$token['email'];
        if(!Account::where('email',$email)->exists()){
            Account::create(['email'=>$email]);
        }
        return redirect(route('inbox'));
    }
}