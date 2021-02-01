<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Category;
use DataTables;
use Carbon\Carbon;
use App\Event;
use App\Session;
use App\PaymentInfo;
use App\Payment;
use Stripe\{Stripe, Charge, Customer};
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * @method:      index
     * @params:      
     * @createdDate: 28-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     View event list
     */
    public function index(Request $request){ 
        if ($request->ajax()) {
            $data = Event::with('category')->where('user_id', Auth::user()->id)->get();
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {       
                            if($modal->start_date_time > Carbon::now()){
                                $avtiveEvents = '<a href="'.url($this->Prefix . '/events/edit-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp';
                            } else{
                                $avtiveEvents = '';
                            }          
                            $action = $avtiveEvents. '<a href="'.url($this->Prefix . '/events/view-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="' .url($this->Prefix . '/events/delete-event/' .Helper::encrypt($modal->id)). '" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
    	return view('vendor.events.index');
    }

    public function holdEvents(Request $request){

        if ($request->ajax()) {

            $data = Event::where('status','=', 'Onhold')->where('user_id', Auth::user()->id)->get();
        
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {       
                            if($modal->start_date_time > Carbon::now()){
                                $avtiveEvents = '<a href="'.url($this->Prefix . '/events/edit-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp';
                            } else{
                                $avtiveEvents = '';
                            }          
                            $action = $avtiveEvents. '<a href="'.url($this->Prefix . '/events/view-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="' .url($this->Prefix . '/events/delete-event/' .Helper::encrypt($modal->id)). '" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('vendor.events.hold');
    }

    public function rescheduledEvents(Request $request){

        if ($request->ajax()) {

            $data = Event::where('rescheduled','=', Event::RESCHEDULED)->where('user_id', Auth::user()->id)->get();
        
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {       
                            if($modal->start_date_time > Carbon::now()){
                                $avtiveEvents = '<a href="'.url($this->Prefix . '/events/edit-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp';
                            } else{
                                $avtiveEvents = '';
                            }          
                            $action = $avtiveEvents. '<a href="'.url($this->Prefix . '/events/view-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="' .url($this->Prefix . '/events/delete-event/' .Helper::encrypt($modal->id)). '" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('vendor.events.rescheduled');
    }

    public function futureEvents(Request $request){

        if ($request->ajax()) {

            $today = date('Y-m-d');


            $data = Event::whereDate('start_date_time','>', $today)->where('user_id', Auth::user()->id)->get();
        
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {       
                            if($modal->start_date_time > Carbon::now()){
                                $avtiveEvents = '<a href="'.url($this->Prefix . '/events/edit-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp';
                            } else{
                                $avtiveEvents = '';
                            }          
                            $action = $avtiveEvents. '<a href="'.url($this->Prefix . '/events/view-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="' .url($this->Prefix . '/events/delete-event/' .Helper::encrypt($modal->id)). '" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('vendor.events.list');
    }


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

            $today = date('Y-m-d');


            $data = Event::where('start_date_time','>=', $weekStartDate)->where('end_date_time','<=', $weekEndDate)->where('user_id', Auth::user()->id)->get();
        
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {       
                            if($modal->start_date_time > Carbon::now()){
                                $avtiveEvents = '<a href="'.url($this->Prefix . '/events/edit-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp';
                            } else{
                                $avtiveEvents = '';
                            }          
                            $action = $avtiveEvents. '<a href="'.url($this->Prefix . '/events/view-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="' .url($this->Prefix . '/events/delete-event/' .Helper::encrypt($modal->id)). '" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('vendor.events.weekly');
    }


    public function pastEvents(Request $request){

        if ($request->ajax()) {

            $today = date('Y-m-d');


            $data = Event::where('end_date_time','<', $today)->where('user_id', Auth::user()->id)->get();
        
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {       
                            if($modal->start_date_time > Carbon::now()){
                                $avtiveEvents = '<a href="'.url($this->Prefix . '/events/edit-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp';
                            } else{
                                $avtiveEvents = '';
                            }          
                            $action = $avtiveEvents. '<a href="'.url($this->Prefix . '/events/view-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="' .url($this->Prefix . '/events/delete-event/' .Helper::encrypt($modal->id)). '" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('vendor.events.past');
    }


    public function todayEvents(Request $request){

        if ($request->ajax()) {

            $today = date('Y-m-d');

            $data = Event::where('start_date_time','<=', $today)->where('end_date_time','>=', $today)->where('user_id', Auth::user()->id)->get();
        
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {       
                            if($modal->start_date_time > Carbon::now()){
                                $avtiveEvents = '<a href="'.url($this->Prefix . '/events/edit-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp';
                            } else{
                                $avtiveEvents = '';
                            }          
                            $action = $avtiveEvents. '<a href="'.url($this->Prefix . '/events/view-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="' .url($this->Prefix . '/events/delete-event/' .Helper::encrypt($modal->id)). '" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('vendor.events.todays');
    }


    /**
     * @method:      create
     * @params:      
     * @createdDate: 28-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Create event form
     */
    public function create(){
        die('vendorók');
        $payment = Payment::where([['user_id', Auth::user()->id], ['validity', 'Purchased']])->first();
    	$categories = Category::get();
        $paymentAmount = PaymentInfo::first();
    	return view('vendor.events.create', compact('categories', 'payment', 'paymentAmount'));
    }

    /**
     * @method:      payment
     * @params:      Request $request
     * @createdDate: 13-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To vendor create an event (Free/Paid) with payment pay to Admin
     */
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
                            ];
                Payment::create($chargeData);
            }
            return back()->withSuccess('Payment successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 28-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validator(Request $request, $id = null){
        $rules = [
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

    /**
     * @method:      store
     * @params:      Request $request
     * @createdDate: 28-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Add event
     */
    public function store(Request $request){
        $this->validator($request);
        try{
            $reqData = $request->all();
            // print_r($reqData); die;
            $reqData['user_id'] = Auth::user()->id;
            Event::create($reqData);
            Payment::where([['user_id', Auth::user()->id], ['validity', 'Purchased']])->update(['validity' => 'Expire']);
            return redirect($this->Prefix . '/events/')->withSuccess('Event has been created successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      edit
     * @params:      $encryptId
     * @createdDate: 28-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To edit event
     */
    public function edit($prefix, $encryptId){
        $id = Helper::decrypt($encryptId);
        $data = Event::findOrFail($id);
        $categories = Category::get();
        try{
            return view('vendor.events.edit', compact('data', 'categories'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      update
     * @params:      Request $request, $encryptId
     * @createdDate: 28-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To update event
     */
    public function update(Request $request, $prefix, $encryptId){
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



            /*$reqData['user_id'] = Auth::user()->id;*/
            Event::where('id', $id)->update($reqData);
            return redirect($this->Prefix . '/events/')->withSuccess('Event has been update successfully');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      destroy
     * @params:      $encryptId
     * @createdDate: 28-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To soft delete event
     */
    public function destroy($prefix, $encryptId){
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

    public function view($prefix,$encryptId)
    {
         $id = Helper::decrypt($encryptId);
         //print_r($id);
        $data = Event::findOrFail($id);
        try{
            return view('vendor.events.view', compact('data'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }


    public function changeState($id , $status){

        $event =  Event::where('id' ,$id)->first();

        if(!empty($event)){

            $event->update([ 'status' => $status]);

            return back()->withSuccess('Event status updated');
       }

    }

}
