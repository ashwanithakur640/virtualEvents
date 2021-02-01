<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Session;
use DateTime;
use App\conferenceViews;
use DB;
use AWS;
use Storage;
use App\currentPresenter;
use App\Twillio;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use App\recordingLog;
use App\conferenceMessage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Cartalyst\Stripe\Stripe;
use Validator;
use App\modratorControl;
use App\userControl;
use App\attendedConference;
use Illuminate\Support\Facades\Log;
use Helper;
use Carbon\Carbon;

class ConferencecallController extends Controller
{
    public function __construct()
    {
        $this->agora_appId = "ebad90cb92fa4036b9c5dc8aad1bc433";
        $this->agora_user_name = "4c8e3a138e6848a485dfbf1efd5d12d7";
        $this->agora_password = "6c58a1e7634843929e93c38e7b9879f5";
        $this->s3_vendor = 1;
        $this->s3_region = 1;
        $this->s3_bucket = "virtualevent-test";
        $this->s3_accessKey = "AKIARIX53FCHXOMYBRPR";
        $this->s3_secretKey = "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";
		// $token = env("TWILIO_AUTH_TOKEN");
  //       $twilio_sid = env("TWILIO_SID");
  //       $this->twilio_verify_sid = env("TWILIO_VERIFY_SID");
  //       $this->twilio = new Client($twilio_sid, $token);
    }

    public function indexDup(Request $request)
{
$user = Auth::User();

$uid = $request->id;
$conference = Session::where('u_id',$request->id)->first();
// print_r( $conference);
if(!$conference){

echo 'Conference Not Found';
}
else{

$organizer = User::find($conference->user_id);
$organizer_id = $organizer->id;

if($organizer_id != Auth::id() && $conference->type !='1')
{
if(empty($request->get('secret')))
{
return redirect('enter-password')->with(['conference' =>$conference]);
}
if(!$this->checkInvite($conference->id,Auth::user()->email,$request->get('secret')))
{
return redirect('enter-password')->with(['conference' =>$conference]);
}

}

if($conference->user_id==Auth::id() && (date('H:i') >= $conference->time) && (strtotime($conference->conf_date) == strtotime(date('Y-m-d'))))
{

Conference::where('id',$conference->id)->update(['conf_status' => '1']);
}
$userSubscription = DB::table('user_subscriptions')->where('status','1')->where('user_id',$user->id)->first();
//print_r($userSubscription); die();
if(empty($userSubscription))
{
Session::flash('message', 'Please Subscribe To A Plan');
return redirect('/home');

}
else{
$plan_id = $userSubscription->plan_id;
$remote_controls_access = false;
if($plan_id==3 || $plan_id==6){

$self_controls_access = true;

}
else if($plan_id==2 || $plan_id==5){
$self_controls_access = true;
}
else if($plan_id==1 || $plan_id==4){
$self_controls_access = false;
}

if($organizer_id==$user->id)
$remote_controls_access = true;

}


$conference_time = date('Y-m-d H:i:s', strtotime("$conference->conf_date $conference->time"));
// echo '<br>';
//date_default_timezone_set('Asia/Kolkata');
$current_time = date('Y-m-d H:i:s', time());
// echo '<br>';
//echo $time_left = $conference_time - $current_time;
//echo date('H:i:s', $time_left);

$seconds_left = 0;
if(strtotime("$conference->conf_date $conference->time") < time()-60602){
//echo 'Conference has been closed';
$time_left = -1;
}
else if(strtotime("$conference->conf_date $conference->time") <= time()+60){
//echo 'Conference ongoing';
$time_left = 0;
$seconds_left = (strtotime("$conference->conf_date $conference->time") + 60601 - time())*1000;
}
else{
//echo $time_left = strtotime("$conference->conf_date $conference->time") -time();
// $time_left = (new DateTime($conference_time))->diff(new DateTime($current_time));
// echo '<br>';
// $time_left = $time_left->format('%Y-%m-%d %H:%i:%s');
// $time_left = strtotime($time_left);
$date_time = convertTimeUser(optional($conference)->conf_date." ".optional($conference)->time.":00",\Session::get('user_timezone'));
$time_left = $date_time['date']." ".$date_time['time'];
}

if($time_left !=='-1')
{
$conf_view = conferenceViews::where('conf_id',$conference->id)->where('user_id',Auth::id())->first();
if(empty($conf_view))
{
$view = new conferenceViews();
$view->conf_id = $conference->id;
$view->user_id = Auth::id();
$view->save();
Conference::where('id',$conference->id)->update(['views'=>($conference->views+1)]);
}
}


return view('conferencecall-dup', compact('user','conference','organizer','time_left','seconds_left','remote_controls_access','self_controls_access','plan_id','uid'));
}


}

    public function index(Request $request)
    {
        $conference = Session::where('u_id',$request->id)->first();	
		if(!Auth::Guest())
		{
        $user =  Auth::User();
		$user->guest = false;
		}
		else{
            if($conference->type=='1'){
        if(!empty(\Session::get('email_guest') && \Session::get('phone_number_guest') ))
        {
            $users = User::where('email',"anonymous".\Session::get('email_guest'))->first();
            if(!empty($users))
            {
                $user = $users->id;
            }
            else
            {
             $user = $this->createGuestUser(array('email'=>"anonymous".\Session::get('email_guest')));
            }
            
            $user =  userDetail($user);
            $user->guest = true;
           
        }
        else
        {
        return redirect('fill-guest-details/'.$request->id);
        }
				
        }
        else
        {
            Session::flash('message', 'Please login to continue!'); 
            Session::flash('alert-class', 'alert-danger'); 
            return redirect('/login');     
        }
    }
        $all_presenters = [];
        $uid = $request->id;
     
		$presenter = 0;
       // print_r( $conference);
       if(!$conference){

        echo 'Conference Not Found';
       }
       else{
             /* add view entery in database */ 
             attendedConference::updateOrCreate(
                ['conference_id' => $conference->id, 'user_id' => Auth::id()],
                ['view' => 1]
            );
             /* end view entery in database */

           if(($conference->type=='1' || $conference->type=='2') && $conference->admin_approval=='0')
		   {
			 Session::flash('message', 'Conference is pending for admin approval!'); 
			 Session::flash('alert-class', 'alert-danger'); 
             return redirect('/my-conferences');   
		   }
		    if($conference->type=='1'  && $conference->admin_decline =='3')
		   {
			 Session::flash('message', 'Conference is decline by the admin!'); 
			 Session::flash('alert-class', 'alert-danger'); 
             return redirect('/my-conferences');   
		   }
           
		$translator = translatorBooking::where('conf_id',$conference->id)->where('status','1')->where('user_id',Auth::id())->first();
		if($translator)
		{
		$flag = translator::where('user_id',$translator->user_id)->first();
		$user->translator = true;
        $user->translator_flag = $flag->language;		
		}
		else{
		$user->translator = false;	
		}
		
		
        $organizer = User::find($conference->user_id);	
        $organizer_id = $organizer->id;
		$translators = translatorBooking::where('conf_id',$conference->id)->get();
		$translator_users = [];
		foreach($translators as $trans)
		{
		$translator_users[] = 	$trans->user_id;
		}
        
		$presenters = [];
		if(!empty($conference->speakers))
		{
		$presenters = json_decode($conference->speakers,true);	
		}
		if($organizer_id != Auth::id() && $conference->type !='1' && !(in_array(Auth::id(),$translator_users)) && !(in_array(Auth::id(),$presenters)))
		{
			if(empty($request->get('secret')))
			{
			 return redirect('enter-password')->with(['conference' =>$conference]);
			}
			if(!$this->checkInvite($conference->id,Auth::user()->email,$request->get('secret')))
			{
			 return redirect('enter-password')->with(['conference' =>$conference]);	
			}
			
		}
		
		/* OTP verify translator */
		if(in_array(Auth::id(),$translator_users))
		{
			$otp_check = translatorBooking::where('conf_id',$conference->id)->where('user_id',Auth::id())->first();
			if(empty($otp_check->password))
			{
			return redirect('otp-verification-translator'."/".$otp_check->conf_id);	
			}
			
		}
       /* End OTP verify translator */		
        
        $userSubscription = DB::table('user_subscriptions')->where('status','1')->where('user_id',$user->id)->first();
       
        if(empty($userSubscription) && !$user->guest)
        {
           Session::flash('message', 'Please Subscribe To A Plan'); 
           return redirect('/home');

        }
        else{
		
             $plan_id = $userSubscription->plan_id;
             /* Check for free plan user access */
             if($plan_id==5)
             {
                 if(Auth::id()==$organizer_id)
                 {
                    $plan_id=4;  
                 }
                 elseif(Auth::id() !=$organizer_id && $this->checkFreePlanUser(Auth::id()) <= 3)
                 {
                    $plan_id=4;  
                 }
             }
            
             /* End check for user plan */
             $remote_controls_access = false;
			 $self_controls_access = true;
            if($plan_id==3){

                $self_controls_access = true;

            }
            else if($plan_id==4){
				
               $self_controls_access = true;
           }
           else if($plan_id==1 || $plan_id==5){
              $self_controls_access = false;
          }

          if($organizer_id==$user->id)
          $remote_controls_access = true;
         
        }
         $userSubscription_admin = DB::table('user_subscriptions')->where('status','1')->where('user_id',$organizer_id)->first();
        $plan_id_time = $userSubscription_admin->plan_id;
        /* free user with all access */
          if($plan_id_time==5)
             {
                 
            $plan_id_time=4;  
                 
             }
         /* End free user with all access */

         $conference_time = date('Y-m-d H:i:s', strtotime("$conference->conf_date $conference->time"));
		
       // echo '<br>';
        //date_default_timezone_set('Asia/Kolkata');
          $current_time = date('Y-m-d H:i:s', time());
       //  echo '<br>';
         //echo $time_left = $conference_time - $current_time;
         //echo  date('H:i:s', $time_left);
		 $total_time = 0;
		 $show_onging = true;
         if($plan_id_time=='3')
		 {
			$total_time = 60; 
		 }
		 if($plan_id_time=='4')
		 {
			$total_time = 90; 
		 }
        $seconds_left = 0;
		
        if(strtotime("$conference->conf_date $conference->time") < time()-$total_time*60*1){
            //echo 'Conference has been closed';
            $time_left = -1;
        }
        else if(strtotime("$conference->conf_date $conference->time") <= time()){
           // echo 'Conference ongoing';
			//echo time()+$total_time."<br/>";
			//die;
            $time_left = 0;
            $seconds_left = (strtotime("$conference->conf_date $conference->time") + $total_time*60*1 - time())*1000;	
        }
        else{
            //echo $time_left = strtotime("$conference->conf_date $conference->time") -time();
           // $time_left = (new DateTime($conference_time))->diff(new DateTime($current_time));
            // echo '<br>';
            // $time_left = $time_left->format('%Y-%m-%d %H:%i:%s');
            // $time_left = strtotime($time_left);
            $date_time = convertTimeUser(optional($conference)->conf_date." ".optional($conference)->time.":00",\Session::get('user_timezone'));
            $time_left = $date_time['date']." at ".$date_time['time'];
			$show_onging = false;
        }
         if($show_onging)
		 {
			if($conference->user_id==Auth::id() &&  (strtotime($conference->conf_date) == strtotime(date('Y-m-d'))))
		{
		
		Session::where('id',$conference->id)->update(['conf_status' => '1']);	
		} 
		 }
         if($time_left !=='-1')
		 {
			 $conf_view = conferenceViews::where('conf_id',$conference->id)->where('user_id',Auth::id())->first();
			 if(empty($conf_view))
			 {
				 $view = new conferenceViews();
				 $view->conf_id = $conference->id;
				 $view->user_id = (Auth::id())?$user->id:'0';
				 $view->save();
			Session::where('id',$conference->id)->update(['views'=>($conference->views+1)]); 
			 }
		 }
         
		 if($seconds_left <= 0 && optional($conference)->conf_date <= date('Y-m-d') && date('H:i') >= $conference->time || $conference->conf_status=='2')
		 {
			 Session::where('id',$conference->id)->update(['conf_status' => '2']);
		 Session::flash('message', 'Conference is completed!'); 
		 Session::flash('alert-class', 'alert-danger');
          if(Auth::id()==$organizer_id)
		  {
			  return redirect('my-conferences');
		  }
		  else
		  {
			return redirect('/home');  
		  }
       		 
		 }
		 
		 if(!empty($conference->speakers))
		{
		$all_presenters = json_decode($conference->speakers,true);
		}
		
		if(in_array($user->id, $all_presenters))
           {
	      $user->presenter = true;
           }
		   else
		   {
			$user->presenter = false;   
		   }
        $get_presenter = currentPresenter::where('conf_id',$conference->id)->first();
		/*echo "<pre>";
		print_r($get_presenter);
		echo "</pre>";
		die;*/
		$now_presenting = 0;
		if(empty($get_presenter) || optional($get_presenter)->user_id == 0)
		{
		$user->current_presenter = 0;	
		}
		else{
		$user->current_presenter = $get_presenter->user_id;	
        $now_presenting = $get_presenter->user_id;		
		}
		
		/* recording enable */
		$recordings = 0;
		$recording_log = recordingLog::where('conf_id',$conference->id)->first();
		if(!empty($recording_log) && $recording_log->status=='1')
		{
		$recordings = 1;	
		}
		
        /* */
        /* get remote controls state */
        $controls = modratorControl::where('conf_id',$conference->id)->first();
        /* End get remote controls state */
		
        return view('conferencecall', compact('user','conference','organizer','time_left','seconds_left','remote_controls_access','self_controls_access','plan_id','uid','translator_users','now_presenting','recordings','controls'));
       }
        
       
    }

		//this is for customer without prefix
    public function getUsers($id)
    {
		
        $user = User::where('id',$id)->first();
		if($user->role_id =='4')
		{
		$user->guest = true;	
		}
		else
		{
		$user->guest = false;	
		}
		$all_presenters = [];
        
        $presenter = Session::where('id',$_GET['conf_id'])->first();
        /* Check basic user access */
        
         /* End Check basic user access */
		if(!empty($presenter->speakers))
		{
		$all_presenters = json_decode($presenter->speakers,true);
		}
		
		 if(in_array($user->id, $all_presenters))
           {
	      $user->presenter = true;
           }
		   else
		   {
			$user->presenter = false;   
		   }
		   
		   $get_presenter = currentPresenter::where('conf_id',$_GET['conf_id'])->first();
		if(empty($get_presenter) || optional($get_presenter)->user_id == 0)
		{
		$user->current_presenter = 0;	
		}
		else{
		$user->current_presenter = $get_presenter->user_id;		
		}
        return json_encode($user);  
    }



    public function getUser($prefix = null, $id)
    {
		
        $user = User::where('id',$id)->first();
		if($user->role_id =='4')
		{
		$user->guest = true;	
		}
		else
		{
		$user->guest = false;	
		}
		$all_presenters = [];
        
        $presenter = Session::where('id',$_GET['conf_id'])->first();
        /* Check basic user access */
        
         /* End Check basic user access */
		if(!empty($presenter->speakers))
		{
		$all_presenters = json_decode($presenter->speakers,true);
		}
		
		 if(in_array($user->id, $all_presenters))
           {
	      $user->presenter = true;
           }
		   else
		   {
			$user->presenter = false;   
		   }
		   
		   $get_presenter = currentPresenter::where('conf_id',$_GET['conf_id'])->first();
		if(empty($get_presenter) || optional($get_presenter)->user_id == 0)
		{
		$user->current_presenter = 0;	
		}
		else{
		$user->current_presenter = $get_presenter->user_id;		
		}
        return json_encode($user);  
    }
	
	function validatePassword(Request $request)
	{
		$conference = Session::where('u_id',$request->conference_uid)->first();	
		if(!empty($conference))
		{
		if(!($this->checkInvite($conference->id,Auth::user()->email,$request->password)))
		{
		Session::flash('message', 'Your are not invited for this OR your password is incorrect'); 
        Session::flash('alert-class', 'alert-danger');
          return redirect('/home');
		}
		else{
         return redirect(url('conference')."/".$conference->u_id."?secret=".$request->password);
		}	
			
		}
		else{
		Session::flash('message', 'Conference not found'); 
        Session::flash('alert-class', 'alert-danger'); 	
		return redirect('home');	
		}
		$password = $request->password;
		
	}
	
	function checkInvite($id,$email,$password)
	{
		
		$conf_invities = conferenceInvite::where('conference_id',$id)->first();
		$emails = json_decode($conf_invities->invities);
		$password_all = json_decode($conf_invities->user_password);
		
		if(in_array(strtolower($email),$emails))
		{
       
		$key = array_search($email, $emails);	
		if($password==$password_all[$key])
		{
		return true;	
		}
		else{	
		return false;	
		}
		}
		else
		{
			return false;	
		}
    }
    
  /*  public function callRecordingCallback(Request $request)
    {
        $input = $request->all();
        DB::table('agora_callback_notifications')->insert(
            array( 
                   'response'   =>   json_encode($input)
            )
       );
        // print_r($input)
        Log::info('call recording call back '.json_encode($input));
         //echo json_encode($input);  
    }*/
	public function callRecordingCallback(Request $request)
    {
        $input = $request->all();

        $result_get_arr = json_decode(json_encode($input),TRUE);

        DB::table('agora_callback_notifications')->insert(
            array( 
                   'response'   =>   json_encode($input)
            )
       );
        Log::info('call recording call back '.json_encode($input));
       if($result_get_arr['payload']['details']['msgName']=='uploaded'){
        $msgName = $result_get_arr['payload']['details']['msgName'];
        $channel = $result_get_arr['payload']['cname'];

        $sid = $result_get_arr['payload']['sid'];


        $s3Recordings = Storage::disk('s3Recordings')->files($channel);
           $inputs = [];
           foreach($s3Recordings as $key){
            $path_info = pathinfo($key);
            //echo $path_info['extension'];
            if($path_info['extension']=='ts'){
                $inputs[]=['Key' => $key,
                'FrameRate' => 'auto',
                'Resolution' => 'auto',
                'AspectRatio' => 'auto',
                'Interlaced' => 'auto',
                'Container' => 'auto',
            ];
            }

           }


        $result = $ElasticTranscoder = AWS::createClient('ElasticTranscoder');
        //pipeline id 1489836190416-r56a8n
        //dd($ElasticTranscoder->listPipelines());
        $ElasticTranscoder->createJob([
        //pipeline id refer it in transcoder page eg : https://ap-southeast-2.console.aws.amazon.com/elastictranscoder/home?region=ap-southeast-2#pipelines:
        'PipelineId'=>'1590490963839-7u6c4d',
        'Inputs' => $inputs,
        'Outputs'=>[

        [
        //output prefix file name
        'Key' =>$sid.'_'.$channel.'.mp4',
        //System preset: HLS Audio - 64k
        
        //Preset id refer it in transcoder page eg : https://ap-southeast-2.console.aws.amazon.com/elastictranscoder/home?region=ap-southeast-2#presets:
        
        'PresetId'=>'1351620000001-000050',
        //seconds to split to file
        //'SegmentDuration'=>'10'
        ]
        ],
        //output folder name
        'OutputKeyPrefix'=>$channel.'/mp4/'
        ]);
        //dd($result);


        }
    
    }

    public function callRecordingAcquire(Request $request)
    {
        $input = $request->all();
        
       $callRecordingQuery = $this->callRecordingQuery($input['cname']);
	  
       if($callRecordingQuery!=200){


       $input['conf_uid'] = '0000'.$input['conference_id'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://api.agora.io/v1/apps/".$this->agora_appId."/cloud_recording/acquire");
        $jsonData1_get_r = array(
            'cname'=> $input['cname'],
            'uid'=> $input['conf_uid'],
            'clientRequest'=> array( "resourceExpiredHour" => 2)
            ); 
    
    $jsonDataEncoded_get  = json_encode($jsonData1_get_r );
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded_get );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type:application/json',
        'Authorization: Basic '. base64_encode($this->agora_user_name.":".$this->agora_password) 
    ));
    $result_get = curl_exec($ch);
    curl_close($ch);

    $result_get_arr = json_decode($result_get,TRUE);



    if($result_get_arr['resourceId']){
        $this->callRecordingStart($result_get_arr['resourceId'],$input);
    }
    else echo json_encode($result_get_arr);

    }
    else{
        $video_streamings = DB::table('video_streamings')->where('cname',$input['cname'])->orderBy('id','DESC')->first();
        $result_get_arr['video_streaming_id'] = $video_streamings->id;
        echo json_encode($result_get_arr);
    }
         
    }

    
    
    public function callRecordingStart($resourceId, $input)
    {
        $user =  Auth::User();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://api.agora.io/v1/apps/".$this->agora_appId."/cloud_recording/resourceid/".$resourceId."/mode/mix/start");
        $jsonData1_get_r = array(
          'cname'=> $input['cname'],
          'uid'=>'0000'.$input['conference_id'],
          "clientRequest"=>array( 
          "recordingConfig"=>array( 
          "channelType"=>1,
          "streamTypes"=>2,
          "audioProfile"=>1,
          //"videoStreamType"=>0,
          "maxIdleTime"=>120,
          "transcodingConfig"=>array( 
          "width"=>640,
          "height"=>480,
          "fps"=>15,
          "bitrate"=>800,
          "maxResolutionUid"=>1,
          "mixedVideoLayout"=>1,
          )
          ),
          "storageConfig"=>array( 
          "vendor"=>$this->s3_vendor,
          "region"=>$this->s3_region,
          "bucket"=>$this->s3_bucket."/".$input['cname'],
          "accessKey"=>$this->s3_accessKey,
          "secretKey"=>$this->s3_secretKey
          )
          )
          );
    
    $jsonDataEncoded_get  = json_encode($jsonData1_get_r );
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded_get );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type:application/json',
        'Authorization: Basic '. base64_encode($this->agora_user_name.":".$this->agora_password) 
    ));
    $result_get = curl_exec($ch);
    curl_close($ch);

    $result_get_arr = json_decode($result_get,TRUE);

    

    if(!empty($result_get_arr['sid'])){
        
    $agora_callback_notifications = DB::table('video_streamings')->insertGetId(
        array( 
               'resourceId'   =>   $result_get_arr['resourceId'],
               'sid'   =>   $result_get_arr['sid'],
               'cname'   =>   $input['cname'],
               'conference_id'   =>   $input['conference_id'],
               'conf_uid'   =>   $input['conf_uid']
        )
   );

    $result_get_arr['video_streaming_id'] = $agora_callback_notifications;
}
else{
    $video_streamings = DB::table('video_streamings')->where('cname',$input['cname'])->orderBy('id','DESC')->first();
    $result_get_arr['video_streaming_id'] = $video_streamings->id;
}

echo json_encode($result_get_arr);

    }

    /* update screen share layout */
    function updateRecordingLayout(Request $request)
    {
       $video =  DB::table('video_streamings')->where('conference_id',$request->conf_id)->orderBy('id', 'desc')->first();
       
       if(!empty($video))
       {
          
           if($request->type=='1'){
             
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://api.agora.io/v1/apps/".$this->agora_appId."/cloud_recording/resourceid/".$video->resourceid."/sid/".$video->sid."/mode/mix/updateLayout");
        $jsonData1_get_r = array(
          'cname'=> $video->cname,
          'uid'=>'0000'.$video->conference_id,
          "clientRequest"=>array( 
            "maxResolutionUid"=>"$request->id",
            "mixedVideoLayout"=>2,
          ));
        }
        else{
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,"https://api.agora.io/v1/apps/".$this->agora_appId."/cloud_recording/resourceid/".$video->resourceid."/sid/".$video->sid."/mode/mix/updateLayout");
            $jsonData1_get_r = array(
              'cname'=> $video->cname,
              'uid'=>$video->conf_uid,
              "clientRequest"=>array( 
                "maxResolutionUid"=>"$request->id",
                "mixedVideoLayout"=>1,
              )); 
        }
   //echo json_encode($jsonData1_get_r);
   //die;
    $jsonDataEncoded_get  = json_encode($jsonData1_get_r);
    //echo $jsonDataEncoded_get;
    //die;
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded_get );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type:application/json',
        'Authorization: Basic '. base64_encode($this->agora_user_name.":".$this->agora_password) 
    ));
    $result_get = curl_exec($ch);
    curl_close($ch);

    $result_get_arr = json_decode($result_get,TRUE);   
    print_r($result_get_arr);
       }
    }

    
    public function callRecordingStop(Request $request)
    {
        $input = $request->all();
        //print_r($input);
        $ch = curl_init();
        $video_streamings = DB::table('video_streamings')->where('id',$input['video_streaming_id'])->first();

        curl_setopt($ch, CURLOPT_URL,"https://api.agora.io/v1/apps/".$this->agora_appId."/cloud_recording/resourceid/".$video_streamings->resourceid."/sid/".$video_streamings->sid."/mode/mix/stop");


//         $jsonData1_get_r = array(
//             'cname'=> $video_streamings->cname,
//             'uid'=>$video_streamings->conference_id,
//             'clientRequest'=> array()
//             ); 


// $jsonDataEncoded_get  = json_encode($jsonData1_get_r);



    
    $jsonDataEncoded_get  = '{
        "cname": "'.$video_streamings->cname.'",
       "uid": "'.$video_streamings->conf_uid.'",
       "clientRequest":{
       }
     }';
    //echo $jsonDataEncoded_get;
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded_get );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type:application/json',
        'Authorization: Basic '. base64_encode($this->agora_user_name.":".$this->agora_password) 
    ));
    $result_get = curl_exec($ch);
    curl_close($ch);

    $result_get_arr = json_decode($result_get,TRUE);
    echo json_encode($result_get_arr);

         
    }


    
    public function callRecordingQuery($cname)
    {
      
        $video_streaming = DB::table('video_streamings')->where('cname',$cname)->orderBy('id','DESC')->first();
        if($video_streaming){

         

        $ch = curl_init();
      

        curl_setopt($ch, CURLOPT_URL,"https://api.agora.io/v1/apps/".$this->agora_appId."/cloud_recording/resourceid/".$video_streaming->resourceid."/sid/".$video_streaming->sid."/mode/mix/query");

    
        //echo $jsonDataEncoded_get;
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json',
            'Authorization: Basic '. base64_encode($this->agora_user_name.":".$this->agora_password) 
        ));
        $result_get = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
		$result_get_arr = json_decode($result_get,TRUE);
        
        return $httpcode;

        // $result_get_arr = json_decode($result_get,TRUE);
        // echo json_encode($result_get_arr);
    }
    else return 404;
         
    }

    
   


    
    public function stopRecording($channel)
    {
        
        $video_streamings = DB::table('video_streamings')->where('cname',$channel)->first();
        if($video_streamings){
 $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.agora.io/v1/apps/".$this->agora_appId."/cloud_recording/resourceid/".$video_streamings->resourceid."/sid/".$video_streamings->sid."/mode/mix/stop");
        $jsonData1_get_r = array(
            'cname'=> $video_streamings->cname,
            'uid'=>$video_streamings->conference_id,
            'clientRequest'=> array()
            ); 
    $jsonDataEncoded_get  = json_encode($jsonData1_get_r);
    // $jsonDataEncoded_get  = '{
    //     "cname": "'.$video_streamings->cname.'",
    //    "uid": "'.$video_streamings->conference_id.'",
    //    "clientRequest":{}
    //  }';
    //echo $jsonDataEncoded_get;
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded_get );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type:application/json',
        'Authorization: Basic '. base64_encode($this->agora_user_name.":".$this->agora_password) 
    ));
    $result_get = curl_exec($ch);
    curl_close($ch);

    $result_get_arr = json_decode($result_get,TRUE);
    //echo json_encode($result_get_arr);

    return true;
    }
    else return false;
    }


    public function RecordingDone(Request $request)
    {
        try {
        //$input = $request->all();
          // $data = json_encode($input);
          $json_write_to_text = file_get_contents("php://input");
           DB::table('agora_callback_notifications')->insert(
            array( 
                   'response'   =>   $json_write_to_text
            )
       );
	   Log::info('Recording done '.$json_write_to_text);
       $data = json_decode(file_get_contents("php://input"),TRUE);
       //$s3key = $data['Records'][0]['s3']['object']['key'];
        $s3key = $data['input']['key'];
        $output = $data['outputs'][0]['key'];

        $output_file_arr = explode('.',$output);
        $output_file_name = $output_file_arr[0];

        $output_file_name_arr = explode('_',$output_file_name);
        $sid = $output_file_name_arr[0];
       
        $channel = $output_file_name_arr[1];
      

        //   $key = explode('/',$s3key);
        //   $key = array_reverse($key);
        //   if(count($key)>=1){
        //      $channel = $key[1];
        //      $file_name = $key[0]; 
        //      $file_name_arr = explode('.',$file_name);
        // }
        // else{
        //     echo json_encode(['success' => 'false1']);
        // }

            
        //    if(count($file_name_arr)>=1){
        //    $file_name_woe = explode('_',$file_name_arr[0]);
        //   }
        //   else{
        //     echo json_encode(['success' => 'false2']);
        //   }

        //    if(count($file_name_woe)>=2){
        //     $sid = $file_name_woe[0];
           
        //     $cname = $file_name_woe[1];
           
        //     $output;
        //    }
        //    else{
        //     echo rjson_encode(['success' => 'false3']);
        //    }
           
        $input = $request->all();
      
        $updated = DB::table('video_streamings')->where('cname',$channel)->where('sid',$sid)
        ->update([
            's3_file' => $output
        ]);
        if($updated) {
            echo json_encode(['success' => 'true']);

            

        }
        else {
            echo json_encode(['success' => 'false4']);
        }

        // $s3Recordings = Storage::disk('s3Recordings')->files($channel);
        //    $inputs = [];
        //    foreach($s3Recordings as $key){
        //     $path_info = pathinfo($key);
        //     if($path_info['extension']=='ts'){

        //         $file = Storage::disk('s3Recordings')->get($key);
        //         // Storage::disk('s3Recordings')->put('merged/'.$file, $file);
        //         // Storage::disk('s3Recordings')->delete($key);
        //     }

        //    }
           
    }

    //catch exception
    catch(Exception $e) {
        Log::info('Recording error '.$e->getMessage());
      echo 'Message: ' .$e->getMessage();
    }
     
    }

    public function mergeVideos(Request $request)
        {
          // $input = $request->all();
          // $data = json_encode($input);
          $json_write_to_text = file_get_contents("php://input");

          $data = json_decode(file_get_contents("php://input"),TRUE);
          $s3key = $data['Records'][0]['s3']['object']['key'];
         //print_r($data);
          
           DB::table('agora_callback_notifications')->insert(
            array( 
                   'response'   =>   $json_write_to_text
            )
       );
      
       
       
           $key = explode('/',$s3key);
           $key = array_reverse($key);
           $channel = $key[1]; 
           $file_name = $key[0]; 
           $file_name_arr = explode('.',$file_name);
             $file_name_arr[0];
           

           $s3Recordings = Storage::disk('s3Recordings')->files($channel);
           $inputs = [];
           foreach($s3Recordings as $key){
            $path_info = pathinfo($key);
            //echo $path_info['extension'];
            if($path_info['extension']=='ts'){
                $inputs[]=['Key' => $key,
                'FrameRate' => 'auto',
                'Resolution' => 'auto',
                'AspectRatio' => 'auto',
                'Interlaced' => 'auto',
                'Container' => 'auto',
            ];
            }

           }

        $result = $ElasticTranscoder = AWS::createClient('ElasticTranscoder');
        //pipeline id 1489836190416-r56a8n
        //dd($ElasticTranscoder->listPipelines());
        $ElasticTranscoder->createJob([
        //pipeline id refer it in transcoder page eg : https://ap-southeast-2.console.aws.amazon.com/elastictranscoder/home?region=ap-southeast-2#pipelines:
        'PipelineId'=>'1590490963839-7u6c4d',
        'Inputs' => $inputs,
        'Outputs'=>[

        [
        //output prefix file name
        'Key' =>$file_name_arr[0].'.mp4',
        //System preset: HLS Audio - 64k
        
        //Preset id refer it in transcoder page eg : https://ap-southeast-2.console.aws.amazon.com/elastictranscoder/home?region=ap-southeast-2#presets:
        
        'PresetId'=>'1351620000001-000050',
        //seconds to split to file
        //'SegmentDuration'=>'10'
        ]
        ],
        //output folder name
        'OutputKeyPrefix'=>$channel.'/mp4/'
        ]);
        //dd($result);

        
        }
        public function jobStatus($jobId)
        {
        $ElasticTranscoder = AWS::createClient('ElasticTranscoder');
        $result = $ElasticTranscoder->readJob(['Id' => $jobId]);
        dd($result);
        }
		
		function addPresenter(Request $request)
      {
	$conference_id = $request->conf_id;
	$user_id = $request->user_id;
	
	$joinee = currentPresenter::updateOrCreate(
    ['conf_id' => $conference_id],
    ['user_id' => $user_id] );
	if($joinee){
	return response()->json(array('success' => true,'msg' =>'joined'));
	}
	else{
	return response()->json(array('success' => false,'msg' =>'something went wrong'));	
	}
	
     }

	 /* Otp for translator */
	 
	//  function sendOtp(Request $request )
	// {
	// 	$booking = translatorBooking::where('user_id',Auth::id())->where('conf_id',$request->id)->where('status','1')->first();
	// 	if(!empty($booking))
	// 	{
	//    try{
 //       // $this->twilio->verify->v2->services($this->twilio_verify_sid)->verifications->create('+91 98775 97613', "sms");
	// 	$this->twilio->verify->v2->services($this->twilio_verify_sid)->verifications->create(Auth::user()->phone, "sms");
	//    }
	//    catch(TwilioException $e)
	//    {
		
	//    Session::flash('message','Unable to send OTP please try again'); 
 //       Session::flash('alert-class', 'alert-danger');
	//    }
	// 	return redirect('verify-phone-translator'."/".$booking->conf_id);
	// 	}
	// 	else{
	// 	 Session::flash('message','You are not authorized to perfoem this action'); 
 //         Session::flash('alert-class', 'alert-danger');	
	// 		return redirect('my-profile');
	// 	}
	// }
	
	protected function verify(Request $request)
    {
        $data = $request->validate([
            'verification_code' => ['required', 'numeric'],
			'conf_id' => ['required', 'numeric']
        ]);
        $user_phone = Auth::user()->phone;
        /*$verification = $this->twilio->verify->v2->services($this->twilio_verify_sid )
            ->verificationChecks
            ->create($data['verification_code'], array('to' =>'+91 98775 97613'));*/
			$verification = $this->twilio->verify->v2->services($this->twilio_verify_sid )
            ->verificationChecks
            ->create($data['verification_code'], array('to' =>$user_phone));
        if ($verification->valid) {
            $user = tap(translatorBooking::where('user_id',Auth::user()->id)->where('conf_id',$request->conf_id))->update(['password' => $request->verification_code]);
			if( $user )
			{
             Session::flash('message', 'logged in successfuly!'); 
             Session::flash('alert-class', 'alert-success'); 
			 $conference = conference::where('id',$request->conf_id)->first();
            return redirect('conference'.'/'.$conference->u_id);
			}
			else{
				
			Session::flash('message', 'Invalid verification code entered!'); 
         Session::flash('alert-class', 'alert-danger'); 
        return back();	
			}
        }
		 Session::flash('message', 'Invalid verification code entered!'); 
         Session::flash('alert-class', 'alert-danger'); 
        return back();
    }
	
	function verificationOtp(Request $request)
	{
		$user = User::find(Auth::id());
		$booking = translatorBooking::where('user_id',Auth::id())->where('conf_id',$request->id)->where('status','1')->first();
		return view('translator.otp',compact('user','booking'));
	}
	
	function addRecordingLog(Request $request)
	{
	$conference_id = $request->conf_id;
	$user_id = $request->user_id;
	
	$recording = recordingLog::updateOrCreate(
    ['conf_id' => $conference_id],
    ['user_id' => $user_id,'status'=>$request->status] );
	if($recording){
	return response()->json(array('success' => true,'msg' =>'log added'));
	}
	else{
	return response()->json(array('success' => false,'msg' =>'something went wrong'));	
	}
		
	}
	
	function saveMessage(Request $request)
	{
	$data = $request->validate([
            'message' => ['required'],
			'conf_id' => ['required', 'numeric'],
			'time' => ['required']
        ]);	
		
		$conference_message = conferenceMessage::where('conf_id',$request->conf_id)->first();
		$messages_all = [];
		if(!empty($conference_message->messages))
		{
			$messages_all = json_decode($conference_message->messages,true);
		}
		$messages_all[] = array("message" => $request->message,"user"=>$request->user_id,'time'=> $request->time);
		
		$messages = conferenceMessage::updateOrCreate(
    ['conf_id' => $request->conf_id],
    ['messages' => json_encode($messages_all), 'status' => '1']
);
	if($messages){
	return response()->json(array('success' => true,'msg' =>'message added'));
	}
	else{
	return response()->json(array('success' => false,'msg' =>'something went wrong'));	
	}	
	}
	
	
	function getOldMessage(Request $request)
	{
	    $data = $request->validate([
			'conf_id' => ['required', 'numeric']
          ]);		
		$conference_message = conferenceMessage::where('conf_id',$request->conf_id)->first();
		$messages = [];
		if(!empty($conference_message->messages))
		{
			$all_messages = json_decode($conference_message->messages,true);
			foreach($all_messages as $all_message)
			{
				$date_time = Helper::convertTimeMessage($all_message['time']);
			$messages[] = array('message'=> $all_message['message'],'user_name'=>optional(Helper::userDetail($all_message['user']))->first_name,'image'=> (!empty(optional(Helper::userDetail($all_message['user']))->image))?url('/')."/storage/profile-images/".optional(Helper::userDetail($all_message['user']))->image:url('/')."/images/client.jpg","time"=>$date_time,"user_id" => $all_message['user']  );	
			}
			
		}
		
		 return response()->json(array('success' => true,'messages' =>$messages));	
	}
	
	
	public function stopRecordingRefresh(Request $request)
    {

        $input = $request->all();

        $ch = curl_init();

        $video_streamings = DB::table('video_streamings')->where('conference_id',$request->id)->latest()->first();

        curl_setopt($ch, CURLOPT_URL,"https://api.agora.io/v1/apps/".$this->agora_appId."/cloud_recording/resourceid/".$video_streamings->resourceid."/sid/".$video_streamings->sid."/mode/mix/stop");

        $jsonData1_get_r = array(
            'cname'=> $video_streamings->cname,
            'uid'=> $video_streamings->conference_id,
            'clientRequest'=> array() 
            ); 
    
    // $jsonDataEncoded_get  = '{
    //     "cname": "'.$video_streamings->cname.'",
    //    "uid": "'.$video_streamings->conf_uid.'",
    //    "clientRequest":{
    //    }
    //  }';


    //  $jsonData1_get_r = array(
    //             'cname'=> $input['cname'],
    //             'uid'=> $input['conf_uid'],
    //             'clientRequest'=> array( "resourceExpiredHour" => 2)
    //         ); 
        
            $jsonDataEncoded_get  = json_encode($jsonData1_get_r);
    //echo $jsonDataEncoded_get;
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded_get );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type:application/json',
        'Authorization: Basic '. base64_encode($this->agora_user_name.":".$this->agora_password) 
    ));
    $result_get = curl_exec($ch);
    curl_close($ch);

    $result_get_arr = json_decode($result_get,TRUE);
    echo json_encode($result_get_arr);
    }
    
    /* function to save modrator logs */
    function saveModratorDetails(Request $request)
    {
       $type = $request->type;
       $control = $request->control;
       $flight = modratorControl::updateOrCreate(
        ['conf_id' => $request->conf_id],
        [$type => $control]
    );
     
    if($flight)
    {

     return response()->json(array('success' => true,'msg' => 'updated'));   
    }
    else
    {
        return response()->json(array('success' => false,'msg' => 'not updated'));    
    }
    }
	    /* save user controls */
    function saveUserDetails(Request $request)
    {
        $type = $request->type;
        $control = $request->control;
        $control = userControl::updateOrCreate(
         ['conf_id' => $request->conf_id,'user_id' =>$request->user_id],
         [$type => $control]
     );
      
     if($control)
     {
 
      return response()->json(array('success' => true,'msg' => 'updated'));   
     }
     else
     {
         return response()->json(array('success' => false,'msg' => 'not updated'));    
     }
    }

    function checkFreePlanUser($id)
    {
        $count = 0;
        $all_views = attendedConference::where('user_id',$id)->get();  
        if(!empty($all_views))
        {
            $count = count($all_views);
        }
        return $count;
    }


//endConferenceTime

    function endConferenceTime(Request $request)
    {
    	$mytime = Carbon::now()->toDateTimeString();

        $all_views = attendedConference::where('user_id',$request->user_id)->where('conference_id' , $request->conference_id)->update(['left_at' => $mytime ]);
        // if(!empty($all_views))
        // {
        //     $count = count($all_views);
        // }
        // return $count;
    }


    function endAllConference(Request $request)
    {
    	$mytime = Carbon::now()->toDateTimeString();

        $all_views = attendedConference::where('conference_id' , $request->conference_id)->update(['left_at' => $mytime ]);
        // if(!empty($all_views))
        // {
        //     $count = count($all_views);
        // }
        // return $count;
    }

}

