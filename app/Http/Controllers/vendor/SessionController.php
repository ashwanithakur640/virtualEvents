<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Carbon\Carbon;
use App\Session;
use App\User;
use DataTables;
use App\Event;
use Illuminate\Support\Str;
use Mail;
use App\Mail\presenterEmail;


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
         $avtiveSession .=  '<a href="'.url($this->Prefix . '/confe/'.$modal->u_id).'" data-toggle="tooltip" title="Edit"><i class="fa fa-video-camera"></i></a>&nbsp';
    }

                       

                        if(strtotime($modal['event']->start_date_time) > strtotime(Carbon::now())) {
                            $avtiveSession .= '<a href="'.url($this->Prefix . '/session/edit-session/'.Helper::encrypt($modal->id)). '" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp';

                            

                        } 

                        // else{
                        //     $avtiveSession = '';
                        // }     

                            $action = $avtiveSession. '<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="'.url($this->Prefix . '/session/delete-session/'.Helper::encrypt($modal->id)).'-'.strtotime("$modal->date $modal->start_time") .'" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
    	return view('vendor.session.index');
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
    	return view('vendor.session.create', compact('events' , 'user'));
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

        // die('hi');
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

                    if($presenter_user->role_id == '0'){
                         $prefix = 'admin';
                    }else if($presenter_user->role_id == '1'){

                        $pp = User::with('vendor_details')->where('id', $presenter_user->id)->first();
                        $prefix =  $pp['vendor_details']->company_business_domain; 

                    }else{
                        $prefix = ''; 
                    }

                    $url = url('');
              
                    if($prefix != '' ){
                        $url .= '/'.$prefix.'/';
                    }  

                    $url .= 'confe/'.$conference->u_id; 
                   
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

            return view('vendor.session.edit', compact('data', 'events' , 'user' , 'usersIdArr' ));
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

}
