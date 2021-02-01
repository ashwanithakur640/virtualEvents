<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\PageContent;
use App\ContactUs;
use App\User;
use App\Faq;

class FrontendController extends Controller
{
    
    /**
     * @method:      aboutUs
     * @params:      
     * @createdDate: 21-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Frontend AboutUs page 
     */
    public function aboutUs(){
        $data = PageContent::where('page_id', 1)->first();
        return view('aboutUs', compact('data'));  
    }
    
    /**
     * @method:      privacyPolicy
     * @params:      
     * @createdDate: 21-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Frontend Privacy & Policy page 
     */
    public function privacyPolicy(){
        $data = PageContent::where('page_id', 2)->first();
        return view('privacyPolicy', compact('data'));  
    }

    /**
     * @method:      contactUs
     * @params:      
     * @createdDate: 21-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Frontend Contact Us page 
     */
    public function contactUs(){
        $data = PageContent::where('page_id', 3)->first();
        return view('contactUs', compact('data'));  
    }

    /**
     * @method:      help
     * @params:      
     * @createdDate: 21-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Frontend Help page 
     */
    public function help(){
        $data = PageContent::where('page_id', 4)->first();
        return view('help', compact('data'));  
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 12-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validator(Request $request){
        $rules = [
                    'first_name' => 'required|alpha|min:3|max:10',
                    'middle_name' => 'nullable|alpha|max:10',
                    'last_name' => 'nullable|alpha|max:10',
                    'email' => 'required|email|regex:/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i|max:40',
                    'mobile' => 'required',
                    'description' => 'required',
                ];

        $messages = [
                        'first_name.required' => 'Please enter your name.',
                        'first_name.alpha' => 'First name may only contain letters.',
                        'first_name.max' => 'First name may not be greater than 10 characters.',
                        'middle_name.alpha' => 'Middle name may only contain letters.',
                        'middle_name.max' => 'Middle name may not be greater than 10 characters.',
                        'last_name.alpha' => 'Last name may only contain letters.',
                        'last_name.max' => 'Last name may not be greater than 10 characters.',
                        'email.required' => 'Please enter your email.',
                        'email.max' => 'Email may not be greater than 40 characters.',
                        'mobile.required' => 'Please enter your mobile.',
                        'description.required' => 'Please enter your message.',
                    ];
        $request->validate($rules,$messages);
    }

    /**
     * @method:      store
     * @params:      Request $request
     * @createdDate: 12-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Add contant us message 
     */
    public function store(Request $request){
        $this->validator($request);
        try{
            $reqData = $request->all();
            $reqData['user_id'] = Auth::user()->id;
            ContactUs::create($reqData);
            /* Start sent Contact Us Report email to admin */
            $admin = User::where('role_id', '0')->first();
            $subject = 'Contact Us';            
            $templateName = 'emails.reportPage';
            $mailData = [    
                            'name' => $reqData['first_name'] .' '. $reqData['middle_name'].' '. $reqData['last_name'],
                            'email' => $reqData['email'],
                            'message' => $reqData['description'],
                            'url' => url('admin/dashboard'),
                        ];
            $toEmail = $admin->email;
            Helper::sendMail($subject, $templateName, $mailData, $toEmail);
            /* End sent Contact Us Report email to admin */
            $notification=array(
                'message' => 'Message sent successfully.',
                'alert-type' => 'success'
            );
            return back()->with($notification);
        } catch(\Exception $e) {
            $notification=array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 13-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validatorRule(Request $request){
        $rules = [
                    'question' => 'required',
                ];

        $messages = [
                        'question.required' => 'Please enter your question.',
                    ];
        $request->validate($rules,$messages);
    }

    /**
     * @method:      storeFaq
     * @params:      Request $request
     * @createdDate: 13-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Add faq question  
     */
    public function storeFaq(Request $request){
        $this->validatorRule($request);
        try{
            $reqData = $request->all();
            $reqData['user_id'] = Auth::user()->id;
            Faq::create($reqData);
            /* Start sent FAQ Report email to admin */
            $admin = User::where('role_id', '0')->first();
            $subject = 'Faq';            
            $templateName = 'emails.reportPage';
            $mailData = [    
                            'name' => Auth::user()->first_name .' '. Auth::user()->middle_name.' '. Auth::user()->last_name,
                            'email' => Auth::user()->email,
                            'message' => $reqData['question'],
                            'url' => url('admin/dashboard'),
                        ];
            $toEmail = $admin->email;
            Helper::sendMail($subject, $templateName, $mailData, $toEmail);
            /* End sent FAQ Report email to admin */
            $notification=array(
                'message' => 'Question add successfully.',
                'alert-type' => 'success'
            );
            return back()->with($notification);
        } catch(\Exception $e) {
            $notification=array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

}
