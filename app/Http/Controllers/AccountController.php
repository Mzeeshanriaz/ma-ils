<?php
namespace App\Http\Controllers;
use App\Account;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Http\Request;
use Mockery\Exception;
class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('IsGmailLogin')->only('insert');
    }
    public function create(){
        if(isset($_GET['error'])) return $_GET['error'];
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