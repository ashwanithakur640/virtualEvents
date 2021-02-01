<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\ContactUs;
use App\User;
use App\Faq;

class FrontendController extends Controller
{
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
                            'url' => url('admin/change-password'),
                        ];
            $toEmail = $admin->email;
            Helper::sendMail($subject, $templateName, $mailData, $toEmail);
            /* End sent Contact Us Report email to admin */
            return back()->withSuccess('Message sent successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
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
                            'url' => url('admin/change-password'),
                        ];
            $toEmail = $admin->email;
            Helper::sendMail($subject, $templateName, $mailData, $toEmail);
            /* End sent FAQ Report email to admin */
            return back()->withSuccess('Question add successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
    
}
