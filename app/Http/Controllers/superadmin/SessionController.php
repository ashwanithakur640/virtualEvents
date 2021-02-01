<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Carbon\Carbon;
use App\Session;
use App\User;
use App\conferenceMessage;
use DataTables;
use App\Event;
use Illuminate\Support\Str;
use Mail;
use App\Mail\presenterEmail;
use Spipu\Html2Pdf\Html2Pdf;
use App\Review;
use DB;
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

            $data = Session::with('event')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  

                    $action = '<a href="'.url('superadmin/show-session/'.Helper::encrypt($modal->id)). '" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>';
                     return $action;
                })
                ->rawColumns(['action'])
                ->make(true);

        }
    	return view('superadmin.session.index');
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


        $events = Event::where([['status', 'Active'], ['end_date_time', '>', Carbon::now()]])->whereIn('user_id', [1, Auth::user()->id])->latest()->get();
    	return view('vendors.session.create', compact('events' , 'user'));
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
        try{

        	$reqData = $request->all();
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
            $reqData['speakers'] = (!empty($request->speakers))?json_encode($request->speakers):'';
        	$conference = Session::create($reqData);

            //$conference = Session::findOrFail($id);




            if(!empty($reqData['speakers'])){

                $users = json_decode($reqData['speakers'],true);  


                foreach($users as $user)
                {
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
      
                    Mail::to($presenter_user->email)->send(new presenterEmail('Invite as presenter',$conference , $url)); 


                }
            }

        	return redirect($this->Prefix . '/session/')->withSuccess('Session has been created successfully.');
    	} catch(\Exception $e) {
        	return back()->withError($e->getMessage());
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
        $data = Session::findOrFail($id);
        try{
            $events = Event::where([['status', 'Active'], ['start_date_time', '>', Carbon::now()]])->whereIn('user_id', [1, Auth::user()->id])->latest()->get();

            $user = User::where('status' , 'Active')->get();
            $usersIdArr = json_decode($data->speakers);

            return view('vendors.session.edit', compact('data', 'events' , 'user' , 'usersIdArr' ));
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
        try{
            $reqData = $request->except(['_token', '_method']);
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
            return redirect($this->Prefix . '/session/')->withSuccess('Session has been update successfully');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
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


    public function show($encryptId){
        $id = Helper::decrypt($encryptId);
        $data = Session::findOrFail($id);
        try{

            $conference_message = conferenceMessage::where('conf_id',$data->id)->first();

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
          //dd($value);
        
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

            return view('superadmin.session.view', compact('data' , 'chat'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }


    public function exportChat($encryptId){ 
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


    //         header('Content-Type: application/octet-stream');
    // header('Content-Disposition: attachment; filename='.basename('myPdf.pdf'));
    // header('Expires: 0');
    // header('Cache-Control: must-revalidate');
    // header('Pragma: public');
    // header('Content-Length: ' . filesize('myPdf.pdf'));
    // readfile('myPdf.pdf');
    // exit;



        }

    } 

    public function showChat($encryptId){
           
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
            return view('superadmin.session.chat-report', compact('data' , 'chat'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }

    }



    public function sessionListing(Request $request){

        if ($request->ajax()) {

            $data = Session::with('event')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  

                    $action = '<a href="'.url('superadmin/chat-report/'.Helper::encrypt($modal->id)). '" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>';
                     return $action;
                })
                ->rawColumns(['action'])
                ->make(true);

        }

        return view('superadmin.session.session-listing');
    }

    public function feedbackListing(Request $request){

        if ($request->ajax()) {

            $data = Session::with('event')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  

                    $action = '<a href="'.url('superadmin/feedback-report/'.Helper::encrypt($modal->id)). '" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>';
                     return $action;
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        
        return view('superadmin.session.feedback-report');
    }


    public function showFeedback($encryptId, Request $request){

        
        if ($request->ajax()) {

            $id = Helper::decrypt($encryptId);

//DB::enableQueryLog();
            $data = Review::where('session_id' , $id )->get();

//dd(DB::getQueryLog());


            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);


        }
        
        return view('superadmin.session.session-feedback')->with('encryptId' , $encryptId);


           
        // $id = Helper::decrypt($encryptId);

        // $data = Session::findOrFail($id);

        // $myRating = Review::where('session_id' , $id )->all();

        // return view('superadmin.session.feedback-report', compact('data' , 'myRating'));
        
    }

}
