<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PaymentInfo;

class PaymentInfoController extends Controller
{
    /**
     * @method:      setPaymentAmount
     * @params:      Request $request
     * @createdDate: 08-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To set payment amount for vendor to create an event
     */
    public function setPaymentAmount(){ 
    	$data = PaymentInfo::first();
    	return view('admin.paymentInfo', compact('data'));
    }

    /**
     * @method:      updatePaymentAmount
     * @params:      Request $request
     * @createdDate: 08-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To update payment amount
     */
    public function updatePaymentAmount(Request $request){  
    	$this->validate($request ,['amount' => 'required|min:0|not_in:0|regex:/^\d*(\.\d{2})?$/']);
    	try{
    		$data = PaymentInfo::first();
        	if(!empty($data)){
        		$reqData = $request->except(['_token', '_method']);
            	PaymentInfo::where('id', $data->id)->update($reqData);
            	return back()->withSuccess('Amount has been updated successfully.');
            }else{
            	$reqData = $request->all();
            	PaymentInfo::create($reqData);
            	return back()->withSuccess('Amount has been added successfully.');
            }
    	} catch(\Exception $e) {
        	return back()->withError($e->getMessage());
        }
    }
}
