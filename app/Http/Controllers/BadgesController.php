<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BadgesController extends Controller
{
    /**
     * @method:      index
     * @params:      
     * @createdDate: 06-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view badges page
     */
    public function index(){
    	return view('customer.badges');
    }
}
