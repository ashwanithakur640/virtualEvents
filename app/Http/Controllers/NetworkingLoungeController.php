<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NetworkingLoungeController extends Controller
{
    /**
     * @method:      index
     * @params:      
     * @createdDate: 061-0-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view networking lounge page
     */
    public function index(){
    	return view('customer.networkingLounge');
    }
}
