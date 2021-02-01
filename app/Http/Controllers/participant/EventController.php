<?php

namespace App\Http\Controllers\participant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Category;
use DataTables;
use Carbon\Carbon;
use App\Event;
use App\User;
use App\Session;
use App\PaymentInfo;
use App\Payment;
use App\EveParticipant;
use Stripe\{Stripe, Charge, Customer};
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
      /**
     * @method:      index
     * @params:      
     * @createdDate: 21-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view list of events
     */
    public function index(Request $request){
        if ($request->ajax()) {
            if(!empty($request->start_date)  & empty($request->end_date) ){
                $from = date($request->start_date);
                $data = Event::whereDate('start_date_time','>=', $from)->where('organisation_id' , Auth::id() )->get();
            }
            else if(empty($request->start_date)  & !empty($request->end_date)){
                $to = date($request->end_date);
                $data = Event::whereDate('end_date_time','<=', $to)->where('organisation_id' , Auth::id() )->get();
            }
            else if(!empty($request->start_date)  & !empty($request->end_date)){
                $from = date($request->start_date);
                $to = date($request->end_date);
                $data = Event::whereDate('start_date_time','>=', $from)->where('organisation_id' , Auth::id() )->whereDate('end_date_time','<=', $to)->get();
            }
            else{
                if(empty($request->start_date)  & empty($request->end_date)){
                    $data = Event::with('category')->where('organisation_id' , Auth::id() )->get();
                }
            }
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {        
                            if($modal->start_date_time > Carbon::now()){
                                $avtiveEvents = '<a href="'.url($this->Prefix.'/edit-events/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp';
                            } else{
                                $avtiveEvents = '';
                            }
                            $action = $avtiveEvents. '<a href="'.url($this->Prefix.'/show-events/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="'.url($this->Prefix.'/delete-events/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                            return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('vendors.events.index');
    }


    /**
     * @method:      todayEvents
     * @params:      
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view today events
     */
    public function todayEvents(Request $request){
        if ($request->ajax()) {
            $today = date('Y-m-d');
            $data = Event::where('start_date_time','<=', $today)->where('end_date_time','>=', $today)->where('organisation_id' , Auth::id() )->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  
                    $action = '<a href="'.url($this->Prefix."/events/show-events/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                        <form action="'.url($this->Prefix.'/delete-events/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('vendors.events.todays');
    }

    /**
     * @method:      pastEvents
     * @params:      
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view past events
     */
    public function pastEvents(Request $request){
        if ($request->ajax()) {
            $today = date('Y-m-d');
            $data = Event::where('end_date_time','<', $today)->where('organisation_id' , Auth::id() )->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  
                    $action = '<a href="'.url($this->Prefix."/show-events/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                        <form action="'.url($this->Prefix.'/delete-events/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('vendors.events.past');
    }

    /**
     * @method:      weeklyEvents
     * @params:      
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view weekly events
     */
    public function weeklyEvents(Request $request){
        if ($request->ajax()) {
            $signupweek = date('Y-m-d');
            /*start day*/
            for($i = 0; $i <7 ; $i++){
                $date = date('Y-m-d', strtotime("-".$i."days", strtotime($signupweek)));
                $dayName = date('D', strtotime($date));
                if($dayName == "Sun"){
                    $weekStartDate =  $date ;
                }
            }
            /*end day*/
            for($i = 0; $i <7 ; $i++){
                $date = date('Y-m-d', strtotime("+".$i."days", strtotime($signupweek)));
                $dayName = date('D', strtotime($date));
                if($dayName == "Sat"){
                    $weekEndDate = $date;
                }
            }
            $data = Event::where('start_date_time','>=', $weekStartDate)->where('end_date_time','<=', $weekEndDate)->where('organisation_id' , Auth::id() )->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  
                    if($modal->start_date_time > Carbon::now()){
                        $avtiveEvents = '<a href="'.url($this->Prefix.'/edit-events/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp';
                    } else{
                        $avtiveEvents = '';
                    }
                    $action = $avtiveEvents. '<a href="'.url($this->Prefix."/show-events/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                        <form action="'.url($this->Prefix.'/delete-events/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                        <input type="hidden" name="_token" value="'. csrf_token() .'">
                        <input type="hidden" name="_method" value="DELETE">
                    </form>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('vendors.events.weekly');
    }

    /**
     * @method:      futureEvents
     * @params:      
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view future events
     */
    public function futureEvents(Request $request){
         if ($request->ajax()) {
            $today = date('Y-m-d');
            $data = Event::where('start_date_time','>', $today)->where('organisation_id' , Auth::user()->id);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  
                    $action = '<a href="'.url($this->Prefix."/show-events/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                        <form action="'.url($this->Prefix.'/delete-events/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('vendors.events.list');
    }

    /**
     * @method:      rescheduledEvents
     * @params:      
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view rescheduled events
     */
    public function rescheduledEvents(Request $request){
        if ($request->ajax()) {
            $data = Event::where('rescheduled','=', Event::RESCHEDULED)->where('organisation_id' , Auth::user()->id);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  
                    
                    $action = '<a href="'.url($this->Prefix."/show-events/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                        <form action="'.url($this->Prefix.'/delete-events/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('vendors.events.rescheduled');
    }

    /**
     * @method:      holdEvents
     * @params:      
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view hold events
     */
    public function holdEvents(Request $request){
        if ($request->ajax()) {
            $data = Event::where('status','=', 'Onhold')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  
                    $action = '<a href="'.url($this->Prefix."/show-events/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                        <form action="'.url($this->Prefix.'/delete-events/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
            }
            return view('vendors.events.hold');
    }

    /**
     * @method:      create
     * @params:      
     * @createdDate: 21-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Create event form
     */
    public function create(){
        
     

        $user = User::where('id' , Auth::user()->id )->first();

        $presenters = User::where('role_id' , User::ROLE_PARTICIPANTS)->where('vendor_id' , Auth::id())->get();


        $payment = Payment::where([['user_id', Auth::user()->id], ['validity', 'Purchased']])->first();
        $categories = Category::get();
        $paymentAmount = PaymentInfo::first();
        return view('vendors.events.create', compact('categories', 'payment', 'paymentAmount' , 'user' , 'presenters'));
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validator(Request $request, $id = null){
        $rules = [
                    /*'name' => 'required|max:30|unique:events,name,NULL,id,deleted_at,NULL',*/
                    'name' => 'required|max:30',
                    'title' => 'required|max:50',
                    'category_id' => 'required',
                    'start_date_time' => 'required',
                    'end_date_time' => 'required',
                    'type' => 'required',
                    'amount' => 'required_if:type,==,Paid',
                    'description' => 'required',
                ];
        $messages = [
                        'name.required' => 'Please enter event name.',
                        /*'name.unique' => 'Event name already has been taken.',*/
                        'name.max' => 'Event name may not be greater than 30 characters.',
                        'title.required' => 'Please enter event title.',
                        'category_id.required' => 'Please select category.',
                        'start_date_time.required' => 'Please select event start date & time.',
                        'end_date_time.required' => 'Please select event end date & time.',
                        'type.required' => 'Please select a type.',
                        'amount.required_if' => 'Please enter an amount.',
                        'description.required' => 'Please enter event description.',
                    ];
        $request->validate($rules,$messages);
    }

    //add-participant-data

    public function participantEventDoc(Request $request){

            $reqData = $request->all();

            //$events = EveParticipant::where('id',$reqData->data_id)->first();

            if(isset($reqData['image']) && !empty($reqData['image'])){
                $profilePicName = time() . '_' . $reqData['image']->getClientOriginalName();
                $path = public_path('/assets/images/documents');
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                Helper::uploadImage($reqData['image'], $path, $profilePicName);

                $dm['document'] = $profilePicName;

                EveParticipant::where('id',$_POST['data_id'])->update($dm);
            }

            return back()->withSuccess('Documents uploaded successfully.');
        
    }

    /**
     * @method:      store
     * @params:      Request $request
     * @createdDate: 21-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Add event
     */
    public function store(Request $request){


        $this->validator($request);
       // try{
            $reqData = $request->all();
            $reqData['user_id'] = Auth::user()->id;
            $reqData['organisation_id'] = Auth::user()->id;
            $event = Event::create($reqData);
// print_r($request->speakers);
// die();
            if(!empty($request->speakers)){
                foreach($request->speakers as $speakers){
                    $data['user_id'] =  $speakers;
                    $data['event_id'] = $event->id;
                    EveParticipant::create($data);


                    //$users = json_decode($reqData['speakers'],true);  


                // foreach($speakers as $user)
                // {
                    $presenter_user = User::find($speakers);    

                    // print_r($presenter_user);

                    // die();
                    /* Start sent welcome email to vendor */
                    $subject = 'Participant for new event';            
                    $templateName = 'emails.event';
                    $mailData = [    
                        'name' => $presenter_user->first_name .' '. $presenter_user->middle_name.' '. $presenter_user->last_name,
                        'message' => 'You are invited as a particpant',
                        'email' => $presenter_user->email,
                        'starttime' => $event->start_date_time,
                        'eventname' => $event->title
                    ];
                    $toEmail = $presenter_user->email;
                    Helper::sendMail($subject, $templateName, $mailData, $toEmail);
                    /* End sent welcome email to vendor */


                //}




                }
            }


         


            return redirect($this->Prefix.'/events/')->withSuccess('Event has been created successfully.');
        // } catch(\Exception $e) {
     //     return back()->withError($e->getMessage());
     //    }
    }

    /**
     * @method:      edit
     * @params:      $encryptId
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To edit event
     */
    public function edit($prefix , $encryptId){
        $id = Helper::decrypt($encryptId);
        $data = Event::findOrFail($id);
        $categories = Category::get();

        $presenters = User::where('role_id' , User::ROLE_PARTICIPANTS)->where('vendor_id' , Auth::id())->get();

        $user = User::where('id' , Auth::user()->id )->first();

        $usersIdArr = EveParticipant::where('event_id' , $data->id)->pluck('user_id')->toArray();

        try{
            return view('vendors.events.edit', compact('data', 'categories' , 'usersIdArr' , 'presenters' , 'user'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      update
     * @params:      Request $request, $encryptId
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To update event
     */
    public function update($prefix , Request $request, $encryptId){
        $id = Helper::decrypt($encryptId);
        $this->validator($request, $id);
        try{
            $reqData = $request->except(['_token', '_method']);
            $data = Event::findOrFail($id);
            $comments = $data->reschedule_comments;
            if($data->start_date_time  != $request->start_date_time){
                $comments .= '<br>Start Time has been changed from '.$data->start_date_time.' to '.$request->start_date_time;
                $reqData['rescheduled'] = 1;
            }
            if($data->end_date_time  != $request->end_date_time){
                $comments .= '<br>Start Time has been changed from '.$data->end_date_time.' to '.$request->end_date_time;
                $reqData['rescheduled'] = 1;
            }
            $reqData['reschedule_comments'] = $comments;
            Event::where('id', $id)->update($reqData);
// print_r($request->speakers);
// die();
            if(!empty($request->speakers)){
//die('dsfsdfsdfsdf');
                $res = EveParticipant::where('event_id',$id)->whereNotIn('user_id',$request->speakers)->delete();

                foreach($request->speakers as $speakers){



                $dm = EveParticipant::where('event_id',$id)->whereIn('user_id', $speakers)->get();

              //  print_r($dm);
//die('xcx');


                    if(empty($dm)){
                        die('bcda');
                        $datam['user_id'] =  $speakers;
                        $datam['event_id'] = $id;
                        EveParticipant::create($datam);
                    }else{
                        die('dkkf');
                    }

                    
                }
            }else{
                $res = EveParticipant::where('event_id',$id)->delete();
            }



            

            return redirect($this->Prefix.'/events/')->withSuccess('Event has been update successfully');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      destroy
     * @params:      $encryptId
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To soft delete event
     */
    public function destroy($prefix , $encryptId){
        $id = Helper::decrypt($encryptId);
        $data = Event::findOrFail($id);
        try{
            Event::where('id', $data->id)->delete();
            Session::where('event_id', $data->id)->delete();
            return back()->withSuccess('Event has been deleted successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      eventShow
     * @params:      $encryptId
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To show event detail
     */
    public function eventShow($prefix , $encryptId){
        $id = Helper::decrypt($encryptId);
        $data = Event::findOrFail($id);
        try{
            return view('vendors.events.view', compact('data'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      changeState
     * @params:      $id
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To change event status
     */
    public function changeState($id , $status){
        $event =  Event::where('id' ,$id)->first();
        if(!empty($event)){
            $event->update([ 'status' => $status]);
            return back()->withSuccess('Event status has been updated successfully.');
        }
    }

    public function payment(Request $request){
        try{
            $reqData = $request->all();
            if(isset($reqData['stripeToken']) && !empty($reqData['stripeToken'])){
                Stripe::setApiKey(env('SECRET_KEY'));
                /*Create charge*/
                $data = Charge::create([
                                'source' => $reqData['stripeToken'],
                                'amount' => number_format(($reqData['amount']*100) , 0, '', ''),
                                'currency' => 'inr'
                            ]);
                $chargeData = [
                                'user_id' => Auth::user()->id,
                                'charge_id' => $data->id,
                                'amount' => $data->amount/100,
                                'currency' => $data->currency,
                                'balance_transaction' => $data->balance_transaction,
                                'validity' => 'Purchased',
                                'type_id' => Payment::TYPE_VENDOR_EVENT_CREATION
                            ];
                Payment::create($chargeData);
            }
            return back()->withSuccess('Payment successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
