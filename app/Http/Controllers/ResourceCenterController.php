<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\EventParticipant;
use Carbon\Carbon;
use App\Session;
use App\Event;
use App\Payment;
use Stripe\{Stripe, Charge, Customer};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Request as Req;


class ResourceCenterController extends Controller
{
    /**
     * @method:      index
     * @params:      
     * @createdDate: 06-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view resource center page
     */
    public function index(){

        $upcomingEvents = Event::where([['status', 'Active'], ['end_date_time', '>=', Carbon::now()]])->orderBy('start_date_time')->get();

    	return view('customer.resourceCenter', compact('upcomingEvents', 'upcomingEvents'));
    }


    //$returnHTML = view('candidates.search.candidate-search')->with('candidate', $candidates)->render();


     public function pastEvents(Request $request){

        if($request->type == 'past'){
            $upcomingEvents = Event::where([['status', 'Active'], ['end_date_time', '<', Carbon::now()]])->orderBy('start_date_time')->get();
         }else{
            $upcomingEvents = Event::where([['status', 'Active'], ['end_date_time', '>=', Carbon::now()]])->orderBy('start_date_time')->get();
         }

       

        $returningData = array();

        $returningData['left'] = view('customer.left')->with('upcomingEvents', $upcomingEvents)->render();

        $returningData['right'] = view('customer.right')->with('upcomingEvents', $upcomingEvents)->render();

        return json_encode($returningData);
    }



    /**
     * @method:      participateIntoEvent
     * @params:      Request $request
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To customer participate in event and pay to Admin
     */
    public function participateIntoEvent(Request $request){

        //dd(env('SECRET_KEY'));
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
            return back()->with($notification);
        } catch(\Exception $e) {
            DB::rollback();
            $notification=array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

}
