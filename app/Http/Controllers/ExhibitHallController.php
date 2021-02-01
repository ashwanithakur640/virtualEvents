<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use Carbon\Carbon;
use App\Category;
use App\Session;
use App\Event;
use App\User;
use App\EventParticipant;
use App\EveParticipant;
use App\Payment;
use Stripe\{Stripe, Charge, Customer};
use Illuminate\Support\Facades\DB;
use Request as Req;

class ExhibitHallController extends Controller
{
    /**
     * @method:      index
     * @params:      
     * @createdDate: 06-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view exhibit hall page with events
     */
    public function index(Request $request){
        $data = User::where([['status', 'Active'], ['role_id', '1']])->paginate(7);
        if($request->isMethod('post')){
            return view('customer.exhibitHall.pagination', compact('data'));
        }
    	return view('customer.exhibitHall.exhibitHall', compact('data'));
    }

    /**
     * @method:      viewAllEvents
     * @params:      
     * @createdDate: 07-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view all events
     */
    public function viewAllEvents(){
        $data = Event::with('category')->where([['status', 'Active'], ['end_date_time', '>=', Carbon::now()]])->orderBy('start_date_time')->get();
        return view('customer.exhibitHall.viewAllEvents', compact('data'));
    }

    /**
     * @method:      eventDatail
     * @params:      $encryptId
     * @createdDate: 12-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view event detail
     */
    public function eventDatail($encryptId){


        $id = Helper::decrypt($encryptId);
        $data = Event::with(['category', 'session'])->where('id', $id)->first();
        $participated = EventParticipant::where([['user_id', Auth::user()->id], ['event_id', $id]])->pluck('id')->first();
        return view('customer.exhibitHall.eventDetail', compact('data', 'participated'));
    }


    public function detail($encryptId){

        $id = Helper::decrypt($encryptId);
        $data = User::with('vendor_details')->where('id', $id)->first();
        //$participated = EventParticipant::where([['user_id', Auth::user()->id], ['event_id', $id]])->pluck('id')->first();
        return view('customer.exhibitHall.detail', compact('data'));
    }

    // /vendorAbout

    public function vendorAbout($encryptId){
        $id = Helper::decrypt($encryptId);
        $data = User::with('vendor_details')->where('id', $id)->first();
        //$participated = EventParticipant::where([['user_id', Auth::user()->id], ['event_id', $id]])->pluck('id')->first();
        return view('customer.exhibitHall.vendor-about', compact('data'));
    }

    //documents

    public function documents($encryptId){

        $id = Helper::decrypt($encryptId);

        $count = 0 ;

        $required = new EveParticipant();

        $data = User::where('vendor_id',$id)->where('role_id' , User::ROLE_PARTICIPANTS)->get();

        $ids = User::where('vendor_id',$id)->where('role_id' , User::ROLE_PARTICIPANTS)->pluck('id')->toArray();

        $arr = array();


        if(!empty($data)){

            foreach($data as $d){
                $arr[$d->id] = $d->first_name ; 
            }



            $arr = array_unique($arr);

            $required = EveParticipant::with('user')->whereIn('user_id' , $ids )->get();

            

            if(!empty($required)){

                $count = 1 ;

            }
        }


        return view('customer.exhibitHall.documents', compact('data' , 'count' , 'required' , 'arr' ));
    }

    //

    /**
     * @method:      participateIntoEvent
     * @params:      Request $request
     * @createdDate: 12-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To customer participate in event and pay to Admin
     */
    public function participateIntoEvent(Request $request){
        DB::beginTransaction();

        $SECRET_KEY =  env('SECRET_KEY'); //"sk_test_51HZAf7LkM2YxAR2prI5a9GKpLCllYqNgJwrXEL1W95sfEDvgxN8mCxglt7xScYDXDu8mrHyAA1yGwZQxvz9lpwtn002OynqxWX" ; 
$PUBLISHABLE_KEY = env('PUBLISHABLE_KEY'); //"pk_test_51HZAf7LkM2YxAR2pEechQp2WSVjNvFT4JZuSImLAqwwo44mi8fdoNtITfU9qmOlWsweLStJKS5qYf2RlRhOtBLqg00ifkQkkMB"; 


        try{
            $reqData = $request->all();
            if(isset($reqData['stripeToken']) && !empty($reqData['stripeToken'])){
                Stripe::setApiKey($SECRET_KEY);
                /*Create charge*/
                $data = Charge::create([
                                'source' => $reqData['stripeToken'],
                                'amount' => number_format(($reqData['amount']*100) , 0, '', ''),
                                'currency' => 'inr'
                            ]);
                $chargeData = [
                                'user_id' => Auth::user()->id,
                                'event_id' => $reqData['event_id'],
                                'charge_id' => $data->id,
                                'amount' => $data->amount/100,
                                'currency' => $data->currency,
                                'balance_transaction' => $data->balance_transaction,
                                'validity' => 'Expire',
                                'type_id' => Payment::TYPE_CUSTOMER_EVENT_PAYMENT
                            ];
                Payment::create($chargeData);
            }
            $eventParticipant = [
                                    'user_id' => Auth::user()->id,
                                    'event_id' => $reqData['event_id'],
                                    'ip' => Req::ip()
                                ];
            EventParticipant::create($eventParticipant);
            DB::commit();
            $notification=array(
                'message' => 'Participated successfully.',
                'alert-type' => 'success'
            );
            return redirect('session-hall')->with($notification);
        } catch(\Exception $e) {
            DB::rollback();
            $notification=array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return redirect('session-hall')->with($notification);
        }
    }
    
}
