<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\EventParticipant;
use Carbon\Carbon;
use App\Session;
use App\Event;
use App\Review;
use App\User;
use App\conferenceViews;
use App\currentPresenter;
use App\attendedConference;

use Response;
use Flash;
use DB;
class SessionHallController extends Controller
{
    /**
     * @method:      index
     * @params:      
     * @createdDate: 14-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view session hall page with (the event in which the user has participated only those session will shown)
     */
    public function index(){

        $participatedEvents = EventParticipant::where('user_id',Auth::user()->id)->pluck('event_id')->toArray();

        $ddm = 0 ;
        $data2 =  Session::where(function ($q) {
            $q->where('date','>' , Carbon::now()->toDateString())->orWhere(function($q2) {
              $q2->where('date', Carbon::now()->toDateString())->where('end_time', '>=', Carbon::now()->toTimeString());
            });
        })->where('status', 'Active')->orderBy('date')->orderBy('start_time')->get();




if(!empty($data2)){
    $ddm = 1 ;
}


        $data = $data2 ; //$data1->merge($data2);
        $firstData = $data->first();
        $count = 0 ; 
        $avg = 0 ;
        if(!empty($firstData)){
            $myRating = Review::where('session_id' , $firstData['id'])->where( 'user_id' , Auth::user()->id )->first();
            $count = Review::where('session_id' , $firstData['id'])->count();
            $avg = round(Review::where('session_id' , $firstData['id'])->avg('rating')); 
        }else{
            $myRating = new Review();
        }

    	return view('customer.sessionHall', compact('data', 'firstData', 'myRating', 'count', 'avg' , 'ddm' , 'participatedEvents'));
    }

    /**
     * @method:      viewSessionDetail
     * @params:      $encryptId
     * @createdDate: 14-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view session detail
     */
    public function viewSessionDetail(Request $request , $encryptId){

        $id = Helper::decrypt($encryptId);

        $participatedEvents = EventParticipant::where('user_id',Auth::user()->id)->pluck('event_id')->toArray();

        $firstData = Session::where('id' , $id)->first();

        $sessionStartButton =  '' ;
        if(strtotime(Carbon::now()) >= strtotime("$firstData->date $firstData->start_time") ){
           // $sessionStartButton =  '' ;
        } else{
            //$sessionStartButton = 'disabled';
        }

        $count = Review::where('session_id' , $firstData->id)->count();
        $myRating = Review::where('session_id' , $id)->where( 'user_id' , auth()->user()->id )->first();
        $avg = round(Review::where('session_id' , $firstData->id)->avg('rating')); 
        if ($request->ajax()) {
            return view('customer.session-detail')->with('firstData', $firstData )->with('count', $count )->with('avg', $avg )->with('myRating', $myRating )->with('sessionStartButton' , $sessionStartButton)->with('participatedEvents' , $participatedEvents);
        }
    }

    /**
     * @method:      saveRating
     * @params:      Request $request
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To save reviews
     */
    public function saveRating(Request $request){
        $input = $request->all();
        $exists = Review::where('session_id' , $input['session_id'] )->where( 'user_id' , auth()->user()->id )->first();
        $save['user_id'] = Auth::user()->id ;
        $save['session_id'] = $input['session_id']  ; 
        $save['rating'] = isset($input['rating']) ? $input['rating'] : 0  ;
        $save['comment'] = $input['comment']  ;
        if(empty($exists)){
            $query =Review::create($save);
        }else{
            $query = $exists->update($input);
        }
        if($query){
            $notification=array(
                'message' => 'Rating saved successfully.',
                'alert-type' => 'success'
            );
        }else{
            $notification=array(
                'message' => 'Error saving rating',
                'alert-type' => 'error'
            );
        }
        return redirect('session-hall')->with($notification);
    }

    /**
     * @method:      getRatings
     * @params:      $encryptId
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To get reviews
     */
    public function getRatings($encryptId){
        $id = Helper::decrypt($encryptId);
        $ratings = Review::where('session_id' , $id)->orderBy('id', 'DESC')->paginate(10);
        return view('customer.session-rating')->with('ratings', $ratings);
    }

    //give-rating

    public function giveRating($id){
      //  $id = Helper::decrypt($encryptId);

        $firstData = Session::where('id' , $id)->first();


        $ratings = Review::where('session_id' , $id)->where( 'user_id' , auth()->user()->id )->first();
        return view('customer.session-rate')->with('myRating', $ratings)->with('confid' , $id)->with('firstData' , $firstData);
    }


    public function conferenceCall(Request $request)
    {

        $user = User::with('vendor_details')->where('id', Auth::User()->id)->first();
        if($user->role_id == '1'){
            $prefix = $user['vendor_details']->company_business_domain;
            
        }else if($user->role_id == '0'){
            $prefix = 'admin';
           
        }else{
            $prefix = '';
        }
     
        $conference = Session::where('u_id',$request->id)->first(); 

        $user =  Auth::User();

        /*
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

    */
        $all_presenters = [];
        $uid = $request->id;
     
        $presenter = 0;
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

        $organizer = User::find($conference->user_id);  
        $organizer_id = $organizer->id;
       
        $presenters = [];

        $presenters[] =  $conference->user_id;
        // if(!empty($conference->speakers))
        // {
        // $presenters = json_decode($conference->speakers,true);  
        // }


        // if($organizer_id != Auth::id() && $conference->type !='1' && !(in_array(Auth::id(),$translator_users)) && !(in_array(Auth::id(),$presenters)))
        // {
        //     if(empty($request->get('secret')))
        //     {
        //      return redirect('enter-password')->with(['conference' =>$conference]);
        //     }
        //     if(!$this->checkInvite($conference->id,Auth::user()->email,$request->get('secret')))
        //     {
        //      return redirect('enter-password')->with(['conference' =>$conference]); 
        //     }
            
        // }
        
        /* OTP verify translator */
        // if(in_array(Auth::id(),$translator_users))
        // {
        //     $otp_check = translatorBooking::where('conf_id',$conference->id)->where('user_id',Auth::id())->first();
        //     if(empty($otp_check->password))
        //     {
        //     return redirect('otp-verification-translator'."/".$otp_check->conf_id); 
        //     }
            
        // }

        
       /* End OTP verify translator */      
        
        // $userSubscription = DB::table('user_subscriptions')->where('status','1')->where('user_id',$user->id)->first();
       
        // if(empty($userSubscription) && !$user->guest)
        // {
        //    Session::flash('message', 'Please Subscribe To A Plan'); 
        //    return redirect('/home');

        // }
        // else{
        
        //      $plan_id = $userSubscription->plan_id;
        //      /* Check for free plan user access */
        //      if($plan_id==5)
        //      {
        //          if(Auth::id()==$organizer_id)
        //          {
        //             $plan_id=4;  
        //          }
        //          elseif(Auth::id() !=$organizer_id && $this->checkFreePlanUser(Auth::id()) <= 3)
        //          {
        //             $plan_id=4;  
        //          }
        //      }
            
        //      /* End check for user plan */
              $remote_controls_access = false;
        //      $self_controls_access = true;
        //     if($plan_id==3){

        //         $self_controls_access = true;

        //     }
        //     else if($plan_id==4){
                
        //        $self_controls_access = true;
        //    }
        //    else if($plan_id==1 || $plan_id==5){
        //       $self_controls_access = false;
        //   }

            if($organizer_id==$user->id){
                $remote_controls_access = true;
            }
        //  $userSubscription_admin = DB::table('user_subscriptions')->where('status','1')->where('user_id',$organizer_id)->first();
        // $plan_id_time = $userSubscription_admin->plan_id;
        // /* free user with all access */
        //   if($plan_id_time==5)
        //      {
                 
        //     $plan_id_time=4;  
                 
        //      }
         /* End free user with all access */

         $conference_time = date('Y-m-d H:i:s', strtotime("$conference->date $conference->time"));
        
       // echo '<br>';
        //date_default_timezone_set('Asia/Kolkata');
          $current_time = date('Y-m-d H:i:s', time());
       //  echo '<br>';
         //echo $time_left = $conference_time - $current_time;
         //echo  date('H:i:s', $time_left);
         $total_time = 0;
         $show_onging = true;
         // if($plan_id_time=='3')
         // {
         //    $total_time = 60; 
         // }
         // if($plan_id_time=='4')
         // {
         //    $total_time = 90; 
         // }
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
             $time_left = strtotime("$conference->date $conference->end_time") -time();
           
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
            if(empty($conf_view)){

                 $view = new conferenceViews();
                 $view->conf_id = $conference->id;
                 $view->user_id = (Auth::id())?$user->id:'0';
                 $view->save();

           // Session::where('id',$conference->id)->update(['views'=>($conference->views+1)]); 
             }
         }
         /*
        if($seconds_left <= 0 && optional($conference)->date <= date('Y-m-d') && date('H:i') >= $conference->end_time){
             
            // Session::flash('message', 'Conference is completed!'); 
            // Session::flash('alert-class', 'alert-danger');
        if(Auth::id()==$organizer_id){

              return redirect('my-conferences');
        }
        else{
            return redirect('/home');  
        }
             
        }

        */
         
         if(!empty($conference->speakers))
        {
        $all_presenters = json_decode($conference->speakers,true);
        }
        
        if(in_array($user->id, $all_presenters)){

          $user->presenter = true;
        }
        else{
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
        
        // /* recording enable */
        // $recordings = 0;
        // $recording_log = recordingLog::where('conf_id',$conference->id)->first();
        // if(!empty($recording_log) && $recording_log->status=='1')
        // {
        // $recordings = 1;    
        // }
        
        // /* */
        // /* get remote controls state */
        // $controls = modratorControl::where('conf_id',$conference->id)->first();
        /* End get remote controls state */
        
        return view('customer.conferencecall', compact('user','conference','organizer','time_left','seconds_left','uid', 'remote_controls_access','now_presenting' , 'prefix'));
       }
    }


 public function conferenceCallm(Request $request)
    {


       
        $user = User::with('vendor_details')->where('id', Auth::User()->id)->first();
        if($user->role_id == '1'){
            $prefix = $user['vendor_details']->company_business_domain;
            
        }else if($user->role_id == '0'){
            $prefix = 'subadmin';
           
        }else{
            $prefix = '';
        }
     
        $conference = Session::where('u_id',$request->id)->first(); 

        $user =  Auth::User();

        /*
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

    */
        $all_presenters = [];
        $uid = $request->id;
     
        $presenter = 0;
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

        $organizer = User::find($conference->user_id);  
        $organizer_id = $organizer->id;
       
        $presenters = [];

        $presenters[] =  $conference->user_id;
        // if(!empty($conference->speakers))
        // {
        // $presenters = json_decode($conference->speakers,true);  
        // }


        // if($organizer_id != Auth::id() && $conference->type !='1' && !(in_array(Auth::id(),$translator_users)) && !(in_array(Auth::id(),$presenters)))
        // {
        //     if(empty($request->get('secret')))
        //     {
        //      return redirect('enter-password')->with(['conference' =>$conference]);
        //     }
        //     if(!$this->checkInvite($conference->id,Auth::user()->email,$request->get('secret')))
        //     {
        //      return redirect('enter-password')->with(['conference' =>$conference]); 
        //     }
            
        // }
        
        /* OTP verify translator */
        // if(in_array(Auth::id(),$translator_users))
        // {
        //     $otp_check = translatorBooking::where('conf_id',$conference->id)->where('user_id',Auth::id())->first();
        //     if(empty($otp_check->password))
        //     {
        //     return redirect('otp-verification-translator'."/".$otp_check->conf_id); 
        //     }
            
        // }

        
       /* End OTP verify translator */      
        
        // $userSubscription = DB::table('user_subscriptions')->where('status','1')->where('user_id',$user->id)->first();
       
        // if(empty($userSubscription) && !$user->guest)
        // {
        //    Session::flash('message', 'Please Subscribe To A Plan'); 
        //    return redirect('/home');

        // }
        // else{
        
        //      $plan_id = $userSubscription->plan_id;
        //      /* Check for free plan user access */
        //      if($plan_id==5)
        //      {
        //          if(Auth::id()==$organizer_id)
        //          {
        //             $plan_id=4;  
        //          }
        //          elseif(Auth::id() !=$organizer_id && $this->checkFreePlanUser(Auth::id()) <= 3)
        //          {
        //             $plan_id=4;  
        //          }
        //      }
            
        //      /* End check for user plan */
              $remote_controls_access = false;
        //      $self_controls_access = true;
        //     if($plan_id==3){

        //         $self_controls_access = true;

        //     }
        //     else if($plan_id==4){
                
        //        $self_controls_access = true;
        //    }
        //    else if($plan_id==1 || $plan_id==5){
        //       $self_controls_access = false;
        //   }

           if($organizer_id==$user->id){
          $remote_controls_access = true;
         
         }
        //  $userSubscription_admin = DB::table('user_subscriptions')->where('status','1')->where('user_id',$organizer_id)->first();
        // $plan_id_time = $userSubscription_admin->plan_id;
        // /* free user with all access */
        //   if($plan_id_time==5)
        //      {
                 
        //     $plan_id_time=4;  
                 
        //      }
         /* End free user with all access */

         $conference_time = date('Y-m-d H:i:s', strtotime("$conference->date $conference->time"));
        
       // echo '<br>';
        //date_default_timezone_set('Asia/Kolkata');
          $current_time = date('Y-m-d H:i:s', time());
       //  echo '<br>';
         //echo $time_left = $conference_time - $current_time;
         //echo  date('H:i:s', $time_left);
         $total_time = 0;
         $show_onging = true;
         // if($plan_id_time=='3')
         // {
         //    $total_time = 60; 
         // }
         // if($plan_id_time=='4')
         // {
         //    $total_time = 90; 
         // }
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
            //$time_left = strtotime($time_left) $date_time['date']." at ".$date_time['time'];
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
            if(empty($conf_view)){

                 $view = new conferenceViews();
                 $view->conf_id = $conference->id;
                 $view->user_id = (Auth::id())?$user->id:'0';
                 $view->save();

           // Session::where('id',$conference->id)->update(['views'=>($conference->views+1)]); 
             }
         }
         /*
        if($seconds_left <= 0 && optional($conference)->date <= date('Y-m-d') && date('H:i') >= $conference->end_time){
             
            // Session::flash('message', 'Conference is completed!'); 
            // Session::flash('alert-class', 'alert-danger');
        if(Auth::id()==$organizer_id){

              return redirect('my-conferences');
        }
        else{
            return redirect('/home');  
        }
             
        }

        */
         
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
        
        // /* recording enable */
        // $recordings = 0;
        // $recording_log = recordingLog::where('conf_id',$conference->id)->first();
        // if(!empty($recording_log) && $recording_log->status=='1')
        // {
        // $recordings = 1;    
        // }
        
        // /* */
        // /* get remote controls state */
        // $controls = modratorControl::where('conf_id',$conference->id)->first();
        /* End get remote controls state */
        
        return view('customer.conferencecallm', compact('user','conference','organizer','time_left','seconds_left','uid', 'remote_controls_access','now_presenting' , 'prefix'));
       }
    }


//conferenceCalls


    public function conferenceCalls(Request $request)
    {

        $user = User::with('vendor_details')->where('id', Auth::User()->id)->first();
        if($user->role_id == '1'){
            $prefix = $user['vendor_details']->company_business_domain;
            
        }else if($user->role_id == '0'){
            $prefix = 'admin';
           
        }else{
            $prefix = '';
        }
    // DB::enableQueryLog();

 $conference = Session::where('u_id',$request->id)->first(); 
//dd(DB::getQueryLog());
       

//dd($conference);
        $user =  Auth::User();

        /*
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

    */
        $all_presenters = [];
        $uid = $request->id;
     
        $presenter = 0;
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

        $organizer = User::find($conference->user_id);  
        $organizer_id = $organizer->id;
       
        $presenters = [];

        $presenters[] =  $conference->user_id;
        // if(!empty($conference->speakers))
        // {
        // $presenters = json_decode($conference->speakers,true);  
        // }


        // if($organizer_id != Auth::id() && $conference->type !='1' && !(in_array(Auth::id(),$translator_users)) && !(in_array(Auth::id(),$presenters)))
        // {
        //     if(empty($request->get('secret')))
        //     {
        //      return redirect('enter-password')->with(['conference' =>$conference]);
        //     }
        //     if(!$this->checkInvite($conference->id,Auth::user()->email,$request->get('secret')))
        //     {
        //      return redirect('enter-password')->with(['conference' =>$conference]); 
        //     }
            
        // }
        
        /* OTP verify translator */
        // if(in_array(Auth::id(),$translator_users))
        // {
        //     $otp_check = translatorBooking::where('conf_id',$conference->id)->where('user_id',Auth::id())->first();
        //     if(empty($otp_check->password))
        //     {
        //     return redirect('otp-verification-translator'."/".$otp_check->conf_id); 
        //     }
            
        // }

        
       /* End OTP verify translator */      
        
        // $userSubscription = DB::table('user_subscriptions')->where('status','1')->where('user_id',$user->id)->first();
       
        // if(empty($userSubscription) && !$user->guest)
        // {
        //    Session::flash('message', 'Please Subscribe To A Plan'); 
        //    return redirect('/home');

        // }
        // else{
        
        //      $plan_id = $userSubscription->plan_id;
        //      /* Check for free plan user access */
        //      if($plan_id==5)
        //      {
        //          if(Auth::id()==$organizer_id)
        //          {
        //             $plan_id=4;  
        //          }
        //          elseif(Auth::id() !=$organizer_id && $this->checkFreePlanUser(Auth::id()) <= 3)
        //          {
        //             $plan_id=4;  
        //          }
        //      }
            
        //      /* End check for user plan */
              $remote_controls_access = false;
        //      $self_controls_access = true;
        //     if($plan_id==3){

        //         $self_controls_access = true;

        //     }
        //     else if($plan_id==4){
                
        //        $self_controls_access = true;
        //    }
        //    else if($plan_id==1 || $plan_id==5){
        //       $self_controls_access = false;
        //   }

           if($organizer_id==$user->id){
          $remote_controls_access = true;
         
         }
        //  $userSubscription_admin = DB::table('user_subscriptions')->where('status','1')->where('user_id',$organizer_id)->first();
        // $plan_id_time = $userSubscription_admin->plan_id;
        // /* free user with all access */
        //   if($plan_id_time==5)
        //      {
                 
        //     $plan_id_time=4;  
                 
        //      }
         /* End free user with all access */

         $conference_time = date('Y-m-d H:i:s', strtotime("$conference->date $conference->time"));
        
       // echo '<br>';
        //date_default_timezone_set('Asia/Kolkata');
          $current_time = date('Y-m-d H:i:s', time());
       //  echo '<br>';
         //echo $time_left = $conference_time - $current_time;
         //echo  date('H:i:s', $time_left);
         $total_time = 0;
         $show_onging = true;
         // if($plan_id_time=='3')
         // {
         //    $total_time = 60; 
         // }
         // if($plan_id_time=='4')
         // {
         //    $total_time = 90; 
         // }
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
            //$time_left = strtotime($time_left) $date_time['date']." at ".$date_time['time'];
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
            if(empty($conf_view)){

                 $view = new conferenceViews();
                 $view->conf_id = $conference->id;
                 $view->user_id = (Auth::id())?$user->id:'0';
                 $view->save();

           // Session::where('id',$conference->id)->update(['views'=>($conference->views+1)]); 
             }
         }
         /*
        if($seconds_left <= 0 && optional($conference)->date <= date('Y-m-d') && date('H:i') >= $conference->end_time){
             
            // Session::flash('message', 'Conference is completed!'); 
            // Session::flash('alert-class', 'alert-danger');
        if(Auth::id()==$organizer_id){

              return redirect('my-conferences');
        }
        else{
            return redirect('/home');  
        }
             
        }

        */
         
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
        
        // /* recording enable */
        // $recordings = 0;
        // $recording_log = recordingLog::where('conf_id',$conference->id)->first();
        // if(!empty($recording_log) && $recording_log->status=='1')
        // {
        // $recordings = 1;    
        // }
        
        // /* */
        // /* get remote controls state */
        // $controls = modratorControl::where('conf_id',$conference->id)->first();
        /* End get remote controls state */
        
        return view('customer.conferencecall-2', compact('user','conference','organizer','time_left','seconds_left','uid', 'remote_controls_access','now_presenting' , 'prefix'));
       }
    }

    public function vendorconferencesCall(Request $request)
    {
       
        $conference = Session::where('u_id',$request->id)->first(); 

        $user =  Auth::User();
       
        /*
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

    */
        $all_presenters = [];
        $uid = $request->id;
     
        $presenter = 0;
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

        $organizer = User::find($conference->user_id);  
        $organizer_id = $organizer->id;
       
        $presenters = [];

        $presenters[] =  $conference->user_id;
        // if(!empty($conference->speakers))
        // {
        // $presenters = json_decode($conference->speakers,true);  
        // }


        // if($organizer_id != Auth::id() && $conference->type !='1' && !(in_array(Auth::id(),$translator_users)) && !(in_array(Auth::id(),$presenters)))
        // {
        //     if(empty($request->get('secret')))
        //     {
        //      return redirect('enter-password')->with(['conference' =>$conference]);
        //     }
        //     if(!$this->checkInvite($conference->id,Auth::user()->email,$request->get('secret')))
        //     {
        //      return redirect('enter-password')->with(['conference' =>$conference]); 
        //     }
            
        // }
        
        /* OTP verify translator */
        // if(in_array(Auth::id(),$translator_users))
        // {
        //     $otp_check = translatorBooking::where('conf_id',$conference->id)->where('user_id',Auth::id())->first();
        //     if(empty($otp_check->password))
        //     {
        //     return redirect('otp-verification-translator'."/".$otp_check->conf_id); 
        //     }
            
        // }

        
       /* End OTP verify translator */      
        
        // $userSubscription = DB::table('user_subscriptions')->where('status','1')->where('user_id',$user->id)->first();
       
        // if(empty($userSubscription) && !$user->guest)
        // {
        //    Session::flash('message', 'Please Subscribe To A Plan'); 
        //    return redirect('/home');

        // }
        // else{
        
        //      $plan_id = $userSubscription->plan_id;
        //      /* Check for free plan user access */
        //      if($plan_id==5)
        //      {
        //          if(Auth::id()==$organizer_id)
        //          {
        //             $plan_id=4;  
        //          }
        //          elseif(Auth::id() !=$organizer_id && $this->checkFreePlanUser(Auth::id()) <= 3)
        //          {
        //             $plan_id=4;  
        //          }
        //      }
            
        //      /* End check for user plan */
        //      $remote_controls_access = false;
        //      $self_controls_access = true;
        //     if($plan_id==3){

        //         $self_controls_access = true;

        //     }
        //     else if($plan_id==4){
                
        //        $self_controls_access = true;
        //    }
        //    else if($plan_id==1 || $plan_id==5){
        //       $self_controls_access = false;
        //   }

        //   if($organizer_id==$user->id)
        //   $remote_controls_access = true;
         
        // }
        //  $userSubscription_admin = DB::table('user_subscriptions')->where('status','1')->where('user_id',$organizer_id)->first();
        // $plan_id_time = $userSubscription_admin->plan_id;
        // /* free user with all access */
        //   if($plan_id_time==5)
        //      {
                 
        //     $plan_id_time=4;  
                 
        //      }
         /* End free user with all access */

         $conference_time = date('Y-m-d H:i:s', strtotime("$conference->date $conference->time"));
        
       // echo '<br>';
        //date_default_timezone_set('Asia/Kolkata');
          $current_time = date('Y-m-d H:i:s', time());
       //  echo '<br>';
         //echo $time_left = $conference_time - $current_time;
         //echo  date('H:i:s', $time_left);
         $total_time = 0;
         $show_onging = true;
         // if($plan_id_time=='3')
         // {
         //    $total_time = 60; 
         // }
         // if($plan_id_time=='4')
         // {
         //    $total_time = 90; 
         // }
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
            //$time_left = strtotime($time_left) $date_time['date']." at ".$date_time['time'];
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
            if(empty($conf_view)){

                 $view = new conferenceViews();
                 $view->conf_id = $conference->id;
                 $view->user_id = (Auth::id())?$user->id:'0';
                 $view->save();

           // Session::where('id',$conference->id)->update(['views'=>($conference->views+1)]); 
             }
         }
         
        if($seconds_left <= 0 && optional($conference)->date <= date('Y-m-d') && date('H:i') >= $conference->end_time){
             
            Session::flash('message', 'Conference is completed!'); 
            Session::flash('alert-class', 'alert-danger');
        if(Auth::id()==$organizer_id){

              return redirect('my-conferences');
        }
        else{
            return redirect('/home');  
        }
             
        }
    
        // /* recording enable */
        // $recordings = 0;
        // $recording_log = recordingLog::where('conf_id',$conference->id)->first();
        // if(!empty($recording_log) && $recording_log->status=='1')
        // {
        // $recordings = 1;    
        // }
        
       
        return view('customer.conferencecall', compact('user','conference','organizer','time_left','seconds_left','uid'));
       }
    }




    // webinar 


    public function webinar(Request $request)
    {

        $user = User::with('vendor_details')->where('id', Auth::User()->id)->first();
        if($user->role_id == '1'){
            $prefix = $user['vendor_details']->company_business_domain;
            
        }else if($user->role_id == '0'){
            $prefix = 'superadmin';
           
        }else{
            $prefix = '';
        }
    // DB::enableQueryLog();

 $conference = Session::where('u_id',$request->id)->first(); 
//dd(DB::getQueryLog());
       

//dd($conference);
        $user =  Auth::User();

        /*
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

    */
        $all_presenters = [];
        $uid = $request->id;
     
        $presenter = 0;
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

        $organizer = User::find($conference->user_id);  
        $organizer_id = $organizer->id;
       
        $presenters = [];

        $presenters[] =  $conference->user_id;
        // if(!empty($conference->speakers))
        // {
        // $presenters = json_decode($conference->speakers,true);  
        // }


        // if($organizer_id != Auth::id() && $conference->type !='1' && !(in_array(Auth::id(),$translator_users)) && !(in_array(Auth::id(),$presenters)))
        // {
        //     if(empty($request->get('secret')))
        //     {
        //      return redirect('enter-password')->with(['conference' =>$conference]);
        //     }
        //     if(!$this->checkInvite($conference->id,Auth::user()->email,$request->get('secret')))
        //     {
        //      return redirect('enter-password')->with(['conference' =>$conference]); 
        //     }
            
        // }
        
        /* OTP verify translator */
        // if(in_array(Auth::id(),$translator_users))
        // {
        //     $otp_check = translatorBooking::where('conf_id',$conference->id)->where('user_id',Auth::id())->first();
        //     if(empty($otp_check->password))
        //     {
        //     return redirect('otp-verification-translator'."/".$otp_check->conf_id); 
        //     }
            
        // }

        
       /* End OTP verify translator */      
        
        // $userSubscription = DB::table('user_subscriptions')->where('status','1')->where('user_id',$user->id)->first();
       
        // if(empty($userSubscription) && !$user->guest)
        // {
        //    Session::flash('message', 'Please Subscribe To A Plan'); 
        //    return redirect('/home');

        // }
        // else{
        
        //      $plan_id = $userSubscription->plan_id;
        //      /* Check for free plan user access */
        //      if($plan_id==5)
        //      {
        //          if(Auth::id()==$organizer_id)
        //          {
        //             $plan_id=4;  
        //          }
        //          elseif(Auth::id() !=$organizer_id && $this->checkFreePlanUser(Auth::id()) <= 3)
        //          {
        //             $plan_id=4;  
        //          }
        //      }
            
        //      /* End check for user plan */
              $remote_controls_access = false;
        //      $self_controls_access = true;
        //     if($plan_id==3){

        //         $self_controls_access = true;

        //     }
        //     else if($plan_id==4){
                
        //        $self_controls_access = true;
        //    }
        //    else if($plan_id==1 || $plan_id==5){
        //       $self_controls_access = false;
        //   }

           if($organizer_id==$user->id){
          $remote_controls_access = true;
         
         }
        //  $userSubscription_admin = DB::table('user_subscriptions')->where('status','1')->where('user_id',$organizer_id)->first();
        // $plan_id_time = $userSubscription_admin->plan_id;
        // /* free user with all access */
        //   if($plan_id_time==5)
        //      {
                 
        //     $plan_id_time=4;  
                 
        //      }
         /* End free user with all access */

         $conference_time = date('Y-m-d H:i:s', strtotime("$conference->date $conference->start_time"));
        
       // echo '<br>';
        //date_default_timezone_set('Asia/Kolkata');
          $current_time = date('Y-m-d H:i:s', time());
       //  echo '<br>';
         //echo $time_left = $conference_time - $current_time;
         //echo  date('H:i:s', $time_left);
         $total_time = 0;
         $show_onging = true;
         // if($plan_id_time=='3')
         // {
         //    $total_time = 60; 
         // }
         // if($plan_id_time=='4')
         // {
         //    $total_time = 90; 
         // }

        $seconds_left = 0;
        $time_left = 0;

        
        if(strtotime("$conference->date $conference->end_time") >= strtotime(now()) ){
      
            
             $seconds_left = strtotime("$conference->date $conference->end_time")  - strtotime(now());   
 
             
            // print_r($seconds_left);
            //  die();

        }
        //else{

        //      //die('ddd');
              //$time_left = strtotime("$conference->date $conference->start_time") -strtotime(now);
        //    // $time_left = (new DateTime($conference_time))->diff(new DateTime($current_time));
        //     // echo '<br>';
        //     // $time_left = $time_left->format('%Y-%m-%d %H:%i:%s');
        //     // $time_left = strtotime($time_left);
        //     // $date_time = convertTimeUser(optional($conference)->conf_date." ".optional($conference)->time.":00",\Session::get('user_timezone'));
        //     // //$time_left = strtotime($time_left) $date_time['date']." at ".$date_time['time'];
        //     // $show_onging = false;

        //     // $diff = strtotime('2009-10-05 18:11:08') - strtotime('2009-10-05 18:07:13')
        //      //die( $time_left);
        //}
        //  if($show_onging)
        //  {
        //     if($conference->user_id==Auth::id() &&  (strtotime($conference->date) == strtotime(date('Y-m-d'))))
        // {
        
        // Session::where('id',$conference->id)->update(['conf_status' => '1']);    
        // } 
        //  }
        //  if($time_left !=='-1')
        //  {
        //     $conf_view = conferenceViews::where('conf_id',$conference->id)->where('user_id',Auth::id())->first();
        //     if(empty($conf_view)){

        //          $view = new conferenceViews();
        //          $view->conf_id = $conference->id;
        //          $view->user_id = (Auth::id())?$user->id:'0';
        //          $view->save();

        //    // Session::where('id',$conference->id)->update(['views'=>($conference->views+1)]); 
        //      }
        //  }
         /*
        if($seconds_left <= 0 && optional($conference)->date <= date('Y-m-d') && date('H:i') >= $conference->end_time){
             
            // Session::flash('message', 'Conference is completed!'); 
            // Session::flash('alert-class', 'alert-danger');
        if(Auth::id()==$organizer_id){

              return redirect('my-conferences');
        }
        else{
            return redirect('/home');  
        }
             
        }

        */
         
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
        
        $now_presenting = 0;
        if(empty($get_presenter) || optional($get_presenter)->user_id == 0)
        {
            $user->current_presenter = 0;   
        }
        else{
            $user->current_presenter = $get_presenter->user_id; 
            $now_presenting = $get_presenter->user_id;      
        }
        
        // /* recording enable */
        // $recordings = 0;
        // $recording_log = recordingLog::where('conf_id',$conference->id)->first();
        // if(!empty($recording_log) && $recording_log->status=='1')
        // {
        // $recordings = 1;    
        // }
        
        // /* */
        // /* get remote controls state */
        // $controls = modratorControl::where('conf_id',$conference->id)->first();
        /* End get remote controls state */
        
        return view('customer.webinar', compact('user','conference','organizer','time_left','seconds_left','uid', 'remote_controls_access','now_presenting' , 'prefix'));
       }
    }

    public function webconferenceCalls(Request $request)
    {

        // print_r(Auth::id());

        // die();

        $user = User::with('vendor_details')->where('id',  Auth::id())->first();
        // if($user->role_id == '1'){
        //     $prefix = $user['vendor_details']->company_business_domain;
            
        // }else if($user->role_id == '0'){
        //     $prefix = 'admin';
           
        // }else{
        //     $prefix = '';
        // }
    // DB::enableQueryLog();

 $conference = Session::where('u_id',$request->id)->first(); 
//dd(DB::getQueryLog());
       

//dd($conference);
        $user =  Auth::User();

        /*
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

    */
        $all_presenters = [];
        $uid = $request->id;
     
        $presenter = 0;
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

        $organizer = User::find($conference->user_id);  
        $organizer_id = $organizer->id;
       
        $presenters = [];

        $presenters[] =  $conference->user_id;
        // if(!empty($conference->speakers))
        // {
        // $presenters = json_decode($conference->speakers,true);  
        // }


        // if($organizer_id != Auth::id() && $conference->type !='1' && !(in_array(Auth::id(),$translator_users)) && !(in_array(Auth::id(),$presenters)))
        // {
        //     if(empty($request->get('secret')))
        //     {
        //      return redirect('enter-password')->with(['conference' =>$conference]);
        //     }
        //     if(!$this->checkInvite($conference->id,Auth::user()->email,$request->get('secret')))
        //     {
        //      return redirect('enter-password')->with(['conference' =>$conference]); 
        //     }
            
        // }
        
        /* OTP verify translator */
        // if(in_array(Auth::id(),$translator_users))
        // {
        //     $otp_check = translatorBooking::where('conf_id',$conference->id)->where('user_id',Auth::id())->first();
        //     if(empty($otp_check->password))
        //     {
        //     return redirect('otp-verification-translator'."/".$otp_check->conf_id); 
        //     }
            
        // }

        
       /* End OTP verify translator */      
        
        // $userSubscription = DB::table('user_subscriptions')->where('status','1')->where('user_id',$user->id)->first();
       
        // if(empty($userSubscription) && !$user->guest)
        // {
        //    Session::flash('message', 'Please Subscribe To A Plan'); 
        //    return redirect('/home');

        // }
        // else{
        
        //      $plan_id = $userSubscription->plan_id;
        //      /* Check for free plan user access */
        //      if($plan_id==5)
        //      {
        //          if(Auth::id()==$organizer_id)
        //          {
        //             $plan_id=4;  
        //          }
        //          elseif(Auth::id() !=$organizer_id && $this->checkFreePlanUser(Auth::id()) <= 3)
        //          {
        //             $plan_id=4;  
        //          }
        //      }
            
        //      /* End check for user plan */
              $remote_controls_access = false;
        //      $self_controls_access = true;
        //     if($plan_id==3){

        //         $self_controls_access = true;

        //     }
        //     else if($plan_id==4){
                
        //        $self_controls_access = true;
        //    }
        //    else if($plan_id==1 || $plan_id==5){
        //       $self_controls_access = false;
        //   }

           if($organizer_id==$user->id){
          $remote_controls_access = true;
         
         }
        //  $userSubscription_admin = DB::table('user_subscriptions')->where('status','1')->where('user_id',$organizer_id)->first();
        // $plan_id_time = $userSubscription_admin->plan_id;
        // /* free user with all access */
        //   if($plan_id_time==5)
        //      {
                 
        //     $plan_id_time=4;  
                 
        //      }
         /* End free user with all access */

         $conference_time = date('Y-m-d H:i:s', strtotime("$conference->date $conference->time"));
        
       // echo '<br>';
        //date_default_timezone_set('Asia/Kolkata');
          $current_time = date('Y-m-d H:i:s', time());
       //  echo '<br>';
         //echo $time_left = $conference_time - $current_time;
         //echo  date('H:i:s', $time_left);
         $total_time = 0;
         $show_onging = true;
         // if($plan_id_time=='3')
         // {
         //    $total_time = 60; 
         // }
         // if($plan_id_time=='4')
         // {
         //    $total_time = 90; 
         // }
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
            //$time_left = strtotime($time_left) $date_time['date']." at ".$date_time['time'];
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
            if(empty($conf_view)){

                 $view = new conferenceViews();
                 $view->conf_id = $conference->id;
                 $view->user_id = (Auth::id())?$user->id:'0';
                 $view->save();

           // Session::where('id',$conference->id)->update(['views'=>($conference->views+1)]); 
             }
         }
         /*
        if($seconds_left <= 0 && optional($conference)->date <= date('Y-m-d') && date('H:i') >= $conference->end_time){
             
            // Session::flash('message', 'Conference is completed!'); 
            // Session::flash('alert-class', 'alert-danger');
        if(Auth::id()==$organizer_id){

              return redirect('my-conferences');
        }
        else{
            return redirect('/home');  
        }
             
        }

        */
         
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
        
        // /* recording enable */
        // $recordings = 0;
        // $recording_log = recordingLog::where('conf_id',$conference->id)->first();
        // if(!empty($recording_log) && $recording_log->status=='1')
        // {
        // $recordings = 1;    
        // }
        
        // /* */
        // /* get remote controls state */
        // $controls = modratorControl::where('conf_id',$conference->id)->first();
        /* End get remote controls state */
        
        $prefix = ''; 

        return view('customer.we-public', compact('user','conference','organizer','time_left','seconds_left','uid', 'remote_controls_access','now_presenting' , 'prefix'));
       }
    }


}

