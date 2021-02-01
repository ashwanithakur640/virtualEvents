<?php

namespace App\Http\Controllers\vendors;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Carbon\Carbon;
use App\Session;
use App\SessionPresenter;
use App\User;
use App\conferenceMessage;
use DataTables;
use App\Event;
use App\attendedConference ;
use Illuminate\Support\Str;
use Mail;
use App\Mail\presenterEmail;
use Spipu\Html2Pdf\Html2Pdf;
use App\Review;

class SessionController extends Controller
{
    /**
     * @method:      index
     * @params:      
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     View session list
     */
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Session::with('event')->where('user_id', Auth::user()->id)->get();
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {  
                            
            date_default_timezone_set("Asia/Kolkata");

    $avtiveSession = '';

    $nowTime = strtotime("now") ;

    $endTime = strtotime("-20 minutes", strtotime("$modal->date $modal->start_time"));

    $upperTime = strtotime("+20 minutes", strtotime("$modal->date $modal->start_time"));

    if( ( $nowTime >= $upperTime ) && ( $endTime <=  $upperTime)   ){ 

    //can join before 20 minutes to after 20 min

    }else{
         $avtiveSession .=  '<a href="'.url($this->Prefix . '/webinar/'.$modal->u_id).'" data-toggle="tooltip" title="Join session"><i class="fa fa-video-camera"></i></a>&nbsp';
    }

                       

                        if(strtotime("$modal->date $modal->start_time") > strtotime(Carbon::now())) {
                            $avtiveSession .= '<a href="'.url($this->Prefix . '/edit-session/'.Helper::encrypt($modal->id)). '" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp';

                            

                        } 

                        // else{
                        //     $avtiveSession = '';
                        // }     

                            $action = $avtiveSession. 

                            '<a href="'.url($this->Prefix . '/show-session/'.Helper::encrypt($modal->id)). '" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="'.url($this->Prefix . '/delete-session/'.Helper::encrypt($modal->id)).'-'.strtotime("$modal->date $modal->start_time") .'" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
    	return view('vendors.session.index');
    }

    /**
     * @method:      create
     * @params:      
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Create session form
     */
    
    public function create(){

        $user = User::where('status' , 'Active')->get();

         $presenters = User::where('role_id' , User::ROLE_PARTICIPANTS)->where('vendor_id' , Auth::id())->get();

        $events = Event::where([['status', 'Active'], ['end_date_time', '>', Carbon::now()]])->whereIn('user_id', [1, Auth::user()->id])->latest()->get();

    	return view('vendors.session.create', compact('events' , 'user' , 'presenters'));

    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validator(Request $request, $id = null){
        $rules = [
                    'name' => 'required|max:30',
                    // 'event_id' => 'required|unique:session,event_id,NULL,id,user_id,'.Auth::user()->id,
                    'date' => 'required',
                    'start_time' => 'required',
                    'end_time' => 'required',
                    'image' => 'required|mimes:jpg,jpeg,png',
                    'description' => 'required',
                ];
        if($id) {
            $rules = array_merge($rules, [
                // 'event_id' => 'required|unique:session,event_id,NULL,id,user_id,'.Auth::user()->id.$id,
                'image' => 'nullable|mimes:jpg,jpeg,png',
            ]);
        }
        $messages = [
                        'name.required' => 'Please enter session name.',
                        'name.max' => 'Session name may not be greater than 30 characters.',
                        'event_id.required' => 'Please select event.',
                        'date.required' => 'Please select date.',
                        'start_time.required' => 'Please select session start time.',
                        'end_time.required' => 'Please select session end time.',
                        'description.required' => 'Please enter session description.',
                    ];
                    
        $request->validate($rules,$messages);
    }

    /**
     * @method:      store
     * @params:      Request $request
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Add session
     */
    public function store(Request $request){

       
        $this->validator($request);

        if( strtotime("$request->date $request->start_time") < strtotime("$request->date $request->end_time") ){

           // try{

            $reqData = $request->except(['_token', '_method' , 'speakers']);
            /* Start upload session image */
            if(isset($reqData['image']) && !empty($reqData['image'])){
                $profilePicName = time() . '_' . $reqData['image']->getClientOriginalName();
                $path = public_path('/assets/images/session');
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                Helper::uploadImage($reqData['image'], $path, $profilePicName);
                $reqData['image'] = $profilePicName;
            }

            /* End upload session image */

            $reqData['user_id'] = Auth::user()->id;
            $reqData['u_id'] = Str::uuid()."-".time();
           // $reqData['speakers'] = (!empty($request->speakers))?json_encode($request->speakers):'';


            $conference = Session::create($reqData);

            //$conference = Session::findOrFail($id);

            if(!empty($request->speakers)){

                foreach($request->speakers as $user){

                    $data['user_id'] =  $user;
                    $data['session_id'] = $conference->id;
                    SessionPresenter::create($data);

                    $presenter_user = User::find($user);    

                    if($presenter_user->role_id == User::ROLE_SUPERADMIN){
                         $prefix = 'superadmin';
                    }else if($presenter_user->role_id == User::ROLE_VENDOR){

                        $pp = User::with('vendor_details')->where('id', $presenter_user->id)->first();
                        $prefix =  $pp['vendor_details']->company_business_domain; 

                    }

                    else if($presenter_user->role_id == User::ROLE_PARTICIPANTS){

                        $prefix = Helper::prefixs($presenter_user);  

                    }
                    else{
                        $prefix = ''; 
                    }

                    $url = url('');
              
                    if($prefix != '' ){
                        $url .= '/'.$prefix.'/';
                    }  

                    $url .= 'webinar/'.$conference->u_id;


                    $url =  $url;
                    $subject = 'Presenter for new event';            
                    $templateName = 'emails.event';
                    $mailData = [    
                        'name' => $presenter_user->first_name .' '. $presenter_user->middle_name.' '. $presenter_user->last_name,
                        'message' => 'You are invited as a presenter',
                        'email' => $presenter_user->email,
                        'starttime' => $conference->date .'at '. $conference->start_time ,
                        'eventname' => $conference->name,
                        'event_url'=>$url,
                        'creater_email' => Auth::user()->email
                    ];

                    $toEmail = $presenter_user->email;
                    Helper::sendMail($subject, $templateName, $mailData, $toEmail);
      
                    //Mail::to($presenter_user->email)->send(new presenterEmail('Invite as presenter',$conference , $url)); 


                }
            }

            return redirect($this->Prefix . '/session/')->withSuccess('Session has been created successfully.');
        // } catch(\Exception $e) {
        //     return back()->withError($e->getMessage());
        // }

        }else{
            return redirect($this->Prefix . '/create-session/')->withError('Start time is greater than end time.');
        }

        
    }

    /**
     * @method:      edit
     * @params:      $encryptId
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To edit session
     */
    public function edit($prefix, $encryptId){
        $id = Helper::decrypt($encryptId);

        $presenters = User::where('role_id' , User::ROLE_PARTICIPANTS)->where('vendor_id' , Auth::id())->get();

        $data = Session::findOrFail($id);

        $usersIdArr = SessionPresenter::where('session_id' , $data->id)->pluck('user_id')->toArray();

        try{
            
            $events = Event::where([['status', 'Active'], ['end_date_time', '>', Carbon::now()]])->whereIn('user_id', [1, Auth::user()->id])->latest()->get();

            $user = User::where('status' , 'Active')->get();
           

            return view('vendors.session.edit', compact('data', 'events' , 'user' , 'usersIdArr' , 'presenters'));


        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      update
     * @params:      Request $request, $encryptId
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To update session
     */
    public function update(Request $request, $prefix, $encryptId){
        $id = Helper::decrypt($encryptId);
        $this->validator($request, $id);

         if( strtotime("$request->date $request->start_time") < strtotime("$request->date $request->end_time") ){

        try{
            $reqData = $request->except(['_token', '_method', 'speakers']);
            /* Start upload session image */
            if(isset($reqData['image']) && !empty($reqData['image'])){
                $profilePicName = time() . '_' . $reqData['image']->getClientOriginalName();
                $path = public_path('/assets/images/session');
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                Helper::uploadImage($reqData['image'], $path, $profilePicName);
                $reqData['image'] = $profilePicName;
            }
            /* End upload session image */
            $reqData['user_id'] = Auth::user()->id;
            Session::where('id', $id)->update($reqData);

            if(!empty($request->speakers)){

                $res = SessionPresenter::where('session_id',$id)->whereNotIn('user_id',$request->speakers)->delete();

                foreach($request->speakers as $speakers){

                    $dm = SessionPresenter::where('session_id',$id)->where('user_id', $speakers)->first();

                    if(empty($dm)){
                       // die('bcda');
                        $datam['user_id'] =  $speakers;
                        $datam['session_id'] = $id;
                        SessionPresenter::create($datam);
                    }

                }
            }else{
                $res = SessionPresenter::where('session_id',$id)->delete();
            }


            return redirect($this->Prefix . '/session/')->withSuccess('Session has been update successfully');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
        }else{
            return redirect($this->Prefix . '/edit-session/'.$encryptId)->withError('Start time is greater than end time.');
        }
    }

    /**
     * @method:      destroy
     * @params:      $encryptId
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To soft delete session
     */
    public function destroy($prefix, $encryptId){
        $id = Helper::decrypt($encryptId);
        $data = Session::findOrFail($id);
        try{
            Session::where('id', $data->id)->delete();
            return back()->withSuccess('Session has been deleted successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }


    public function show($prefix , $encryptId){
        $id = Helper::decrypt($encryptId);
        $data = Session::findOrFail($id);
        try{
            return view('vendors.session.view', compact('data'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }


    public function sessionListing(Request $request){

        if ($request->ajax()) {

            $eventsId = Event::where('user_id' ,  Auth::id() )->pluck('id')->toArray();

            if(!empty($eventsId)){

                $data = Session::with('event')->whereIn('event_id' , $eventsId)->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($modal) {  

                        $action = '<a href="'.url($this->Prefix . '/chat-reports/'.Helper::encrypt($modal->id)). '" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>';
                         return $action;

                    })
                    ->rawColumns(['action'])
                    ->make(true);

            }

       }
        return view('vendors.session.session-listing');
    }


    public function showChat($prefix ,$encryptId){
           
        $id = Helper::decrypt($encryptId);
        $data = Session::findOrFail($id);
        try{

            $conference_message = conferenceMessage::where('conf_id',$data->id)->first();

        $messages = [];
        if(!empty($conference_message->messages))
        {
            $all_messages = json_decode($conference_message->messages,true);
            foreach($all_messages as $all_message)
            {
                $date_time = Helper::convertTimeMessage($all_message['time']);
            $messages[] = array('message'=> $all_message['message'] ,'user_name'=>optional(Helper::userDetail($all_message['user']))->first_name ,"time"=>$date_time,"user_id" => $all_message['user']  );  
            }
            
        }

        $chat = '';

        if(!empty($messages)){

            foreach( $messages as $value) {
         
                $chat .= '<div class="box_text_color color4">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-0">'. $value['user_name']  .'</h4>
                        <h4 class="mb-0">'. $value['time'] .'</h4>
                    </div>
                    <p class="mb-0">'. $value['message'] .'</p>
                    </div>';

            }

        }else{
            $chat .= '<div class="">No messages</div>';
        }
            return view('vendors.session.chat-report', compact('data' , 'chat'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }

    }


    public function exportChat($prefix ,$encryptId){ 
        $id = Helper::decrypt($encryptId);


        $conference_message = conferenceMessage::where('conf_id',$id)->first();

            // print_r($conference_message);
            // die();
        $messages = [];
        if(!empty($conference_message->messages))
        {
            $all_messages = json_decode($conference_message->messages,true);
            foreach($all_messages as $all_message)
            {
                $date_time = Helper::convertTimeMessage($all_message['time']);
            $messages[] = array('message'=> $all_message['message'] ,'user_name'=>optional(Helper::userDetail($all_message['user']))->first_name ,"time"=>$date_time,"user_id" => $all_message['user']  );  
            }
            
        }

        $chat = '';

        if(!empty($messages)){

            foreach( $messages as $value) {
          
        
                $chat .= '<div class="box_text_color color4">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-0">'. $value['user_name']  .'</h4>
                        <h4 class="mb-0">'. $value['time'] .'</h4>
                    </div>
                    <p class="mb-0">'. $value['message'] .'</p>
                    </div>';
          
          
            }

            $html2pdf = new Html2Pdf();
            $html2pdf->writeHTML($chat);
            $html2pdf->output('chat.pdf' , 'D'); 

        }

    } 



    public function feedbackListing(Request $request){

        if ($request->ajax()) {

            $eventsId = Event::where('user_id' ,  Auth::id() )->pluck('id')->toArray();

            if(!empty($eventsId)){

                $data = Session::with('event')->whereIn('event_id' , $eventsId)->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($modal) {  

                        $action = '<a href="'.url($this->Prefix . '/feedback-reports/'.Helper::encrypt($modal->id)). '" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>';
                         return $action;

                    })
                    ->rawColumns(['action'])
                    ->make(true);

            }

       }
        return view('vendors.session.feedback-report');
    }


    public function showFeedback( $prefix , $encryptId, Request $request){

        
        if ($request->ajax()) {



            $id = Helper::decrypt($encryptId);



            $data = Review::where('session_id' , $id )->get();


            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);

        }
        
        return view('vendors.session.session-feedback')->with('encryptId' , $encryptId);

    }


    public function attendeesReport(Request $request){

        if ($request->ajax()) {

           $eventsId = Event::where('user_id' ,  Auth::id() )->pluck('id')->toArray();

            if(!empty($eventsId)){
           $data = attendedConference::whereIn('conference_id', $eventsId)->orderBy('id', 'desc')->get();

            return DataTables::of($data)
                        ->addIndexColumn()       
                        ->make(true);
                    }
        }

        return view('vendors.reports.attendees-report');

     } 




}
