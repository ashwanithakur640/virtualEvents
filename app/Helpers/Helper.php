<?php
namespace App\Helpers;

use Mail;
use Crypt;
use App\User;
use Illuminate\Support\Facades\Auth;

class Helper {

	/**
     * @method:      sendMail
     * @params:      $subject, $templateName, $mailData, $toEmail
     * @createdDate: 14-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To send email
     */
    public static function sendMail($subject, $templateName, $mailData, $toEmail){
        $response = Mail::send($templateName, ['data' => $mailData], function($message) use ($toEmail, $subject) {
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($toEmail)->subject($subject);
        });
        return $response;
    }

    /**
     * @method:      uploadImage
     * @params:      $image, $destinationPath, $name
     * @createdDate: 14-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To move uploaded Image into destination path
     */
    public static function uploadImage($image, $destinationPath, $name)
    {
        $image->move($destinationPath, $name);
    }

    /**
     * @method:      uploadVideo
     * @params:      $video, $destinationPath, $name
     * @createdDate: 17-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To move uploaded video into destination path
     */
    public static function uploadVideo($video, $destinationPath, $name)
    {
        $video->move($destinationPath, $name);
    }

    /**
     * @method:      encrypt
     * @params:      $id
     * @createdDate: 14-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To encrypt id
     */
    public static function encrypt($id){
        return Crypt::encrypt($id);
    }

    /**
     * @method:      decrypt
     * @params:      $id
     * @createdDate: 14-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To decrypt id
     */
    public static function decrypt($id){
        return Crypt::decrypt($id);
    }

    /**
     * @method:      prefix
     * @params:      
     * @createdDate: 30-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To get vendor prefix
     */
    public static function prefix($authUser){
        if(!empty($authUser)){
            $user = User::with('vendor_details')->where('id', $authUser->id)->first();
            if($user->role_id == '1'){
                $prefix = $user['vendor_details']->company_business_domain;
                return $prefix;
            }
        }
    }

    public static function prefixs($authUser){
        //dd($authUser);

      //  return $authUser;
        if(!empty($authUser)){

            $users = User::where('id', $authUser->id)->first();

            $user = User::with('vendor_details')->where('id', $users->vendor_id)->first();
            if($user->role_id == '1'){
                $prefix = $user['vendor_details']->company_business_domain;
                return $prefix;
            }
        }
    }


    public static function convertTimeMessage($dateTime)
    {
        // if(empty($from)){
        //     if(!empty($_COOKIE['time_zone'])){
        //         $from = $_COOKIE['time_zone'];  
        //     }
        //     else{
                $from = 'Asia/Calcutta';
        //     }
        // }

       $datetime = $dateTime;
       $dt = new \DateTime($datetime);
       //$dt->setTimeZone(new \DateTimeZone($tz_to));
       $date = $dt->format('d M Y');
       $time = $dt->format('H:i');
       
       return $date .' | '. $time;
        
    }

    public static function userDetail($id)
    {
        return User::where('id',$id)->first();
    }

}

?>