<?php
namespace App;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Database\Eloquent\Model;
class Email extends Model
{
    protected $fillable = ['email_id','subject','name','box','account_id',
        'dated','box_id','is_unread'];

    public function account(){
        return $this->belongsTo(Account::class);
    }

    public function attachments(){
        return $this->hasMany(Attachment::class);
    }

    public function getPayTo($body){
        $body = str_after($body , 'Bonjour ');
        return str_before($body,',');
    }
    public function getFullName($body){
        $body = str_after($body , 'Bonjour ');
        $body = str_after($body , ',');
        $body = str_before($body,'vous');

        return trim( strip_tags( str_replace("\r\n","",$body)));
    }
    public function getAllImageSrc($body){
        preg_match_all('/<img[^>]+>/i',$body, $results);
        $img_src = array();
        foreach( $results[0] as $result){
            preg_match_all('/(src)=("[^"]*")/i',$result, $img);
            $img_src [] = $img[2][0];
        }
        return $img_src;
    }
    public function getAllUrls($body){
        preg_match_all('/<a[^>]+>/i',$body, $results);
        $img_src = array();
        foreach( $results[0] as $result){
            preg_match_all('/(href)=("[^"]*")/i',$result, $img);
            $img_src [] = $img[2][0];
        }
        return $img_src;
    }
    public function getReplyTo($body){
        $email = str_after($body , 'reply-to: ');
        if( filter_var( $email, FILTER_VALIDATE_EMAIL ) === false ){
            return ;
        }
        return $email;
    }
    public function getDepositCode($body){
        $body = str_after($body , 'https://etransfer.interac.ca/fr/');
        $code = str_before($body,'/');
        if(strlen($code)>9) return ;
        return $code;
    }

    public function splitName($name) {
        $name = trim($name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim( preg_replace('#'.$last_name.'#', '', $name ) );
        return array($first_name, $last_name);
    }
    public function getFirstName($body){
        $full_name = $this->getFullName($body);
        $array = $this->splitName($full_name);
        return $array[0];
    }
    public function getLastName($body){
        $full_name = $this->getFullName($body);
        $array = $this->splitName($full_name);
        return $array[1];
    }
    public function getTotal($body){
        $body = str_after($body , 'vous a envoyé');
        $total = trim(str_before($body,'$'));
        $total = str_replace("un virement de","",$total);
        return (int)$total;
    }
    public function getType($body){
        $body = str_before($body,'$');
        if( str_contains($body,'vous a été envoyé ') == true ) $text = 'été envoyé';
        else $text = 'envoyé';
        return $text;
    }
    public function getMessage($body){
        if(!str_contains($body,"Message :") && !str_contains($body,"Message:")) return ;
        $body = strip_tags($body);
        $body = str_replace(PHP_EOL,"",$body);
        $body = trim($body);
        $body = preg_replace('!\s+!', ' ', $body);
        $body = str_after($body , 'Message:');
        if($body==null) $body = str_after($body , 'Message :');
        $body = str_before($body,'Déposer');
        return $body;
    }
    public function expiresOn($body){
        $body = str_after($body , 'Expire le :');
        $body = str_before($body,'FaQs');
        $body = strip_tags($body);
        $body = str_replace(PHP_EOL,"",$body);
        return $body;
    }
}
