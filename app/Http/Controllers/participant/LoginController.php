<?php

namespace App\Http\Controllers\participant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\User;
use App\VendorDetail;
use App\Helpers\Helper;
use App\PasswordReset;

class LoginController extends Controller
{
    
    /**
     * @method:      loginForm
     * @params:      
     * @createdDate: 14-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Show admin login form
     */
    public function loginForm(Request $request){
        //dd($request->domain);
    	return view('participant.loginForm')->with('param' , $request->domain);
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 14-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */

    private function validator(Request $request){
	    $rules = [
        	        'email' => 'required|email|max:40|regex:/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i|exists:users',
        	        'password' => 'required|min:8|max:16',
        	    ];
	    $messages = [
                        'email.required' => 'Email field is required.',
                        'email.max' => 'Email may not be greater than 40 characters.',
                        'email.exists' => 'Email do not match with our records.',
                        'password.required' => 'Password field is required.',
                        'password.min' => 'Password must be at least 8 characters.',
                        'password.max' => 'Password may not be greater than 16 characters.',
                    ];
	    $request->validate($rules,$messages);
	}

    /**
     * @method:      login
     * @params:      Request $request
     * @createdDate: 14-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Authendicate admin credentials
     */
    public function login(Request $request){

    	$this->validator($request);

        // print_r($request->param);

        // die();

        $vendordetail = VendorDetail::where('company_business_domain' , $request->param )->first();

        $vendor = User::where([['email', $request->email], ['role_id', User::ROLE_PARTICIPANTS]])->first();

        //dd($vendor);

        if(!empty($vendor)){

            if($vendor->vendor_id != $vendordetail->user_id){
                 return back()->withInput($request->only('email'))->withError('Your are not allowed to login at this url');
            }

            if($vendor->deleted_is_admin == '1'){
                return back()->withInput($request->only('email'))->withError('Your account has been deleted by Superadmin ! please contact to Superadmin.');
            }
            if($vendor->status == 'Inactive'){
                return back()->withInput($request->only('email'))->withError('Your account has been Inactive ! please contact to Superadmin.');
            }
            // if($vendor->status == 'Not-verified'){
            //     return back()->withInput($request->only('email'))->withError('Your account has been Not-verified ! please contact to Superadmin.');
            // }
            // dd(Auth::guard());
            if(Auth::guard('participant')->attempt(['email' => $request->email, 'password' => $request->password, 'role_id'=> User::ROLE_PARTICIPANTS])) {
                $this->user = Auth::guard('participant')->user();
                // $prefix = $request->param;
                // $this->Prefix = $prefix;
               // dd($this->user);

                $prefix = Helper::prefixs($this->user);
                $this->Prefixx = $prefix;

                return redirect($this->Prefixx . '/participant-dashboard')->withSuccess('You are login successfully.');
            }
        }else{
            return back()->withError('Invalid login url');
        }
        return back()->withInput($request->only('email'))->withError('Email and password do not match with our records.');
    }

    /**
     * @method:      ValidationCheck
     * @params:      Request $request
     * @createdDate: 14-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function ValidationCheck(Request $request){
        $rules = ['email' => 'required|email|regex:/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i|max:40|exists:users'];
        $messages = [
                        'email.required' => 'Email field is required.',
                        'email.max' => 'Email may not be greater than 40 characters.',
                        'email.exists' => 'Email do not match with our records.',
                    ];
        $request->validate($rules, $messages);
    }

    /**
     * @method:      forgotPassword
     * @params:      Request $request
     * @createdDate: 14-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Forgot admin password
     */
    public function forgotPassword($Prefix , Request $request){
        if ($request->isMethod('post')) {

            //die('dcf'.$Prefix);

            $this->ValidationCheck($request);
            $requestData = $request->all();
            $admin = User::where([['email', $requestData['email']], ['status', 'Active'], ['role_id', User::ROLE_VENDOR]])->first();
            if(!empty($admin)) {
                $emailData = PasswordReset::where('email', $requestData['email'])->first();
                if(!empty($emailData)) {
                    PasswordReset::where('email', $requestData['email'])->delete();
                }
                $newSecurityToken = md5(time());
                $data = [
                            'email' => $admin->email,
                            'token' => $newSecurityToken 
                        ];
                PasswordReset::create($data); 
                $subject = 'Reset Password';            
                $templateName = 'emails.resetPassword';
                $mailData = [    
                                'name' => $admin->first_name .' '. $admin->middle_name.' '. $admin->last_name,
                                'message' => url($Prefix.'/reset-password/'.$newSecurityToken)
                            ];
                $toEmail = $admin->email;
                Helper::sendMail($subject, $templateName, $mailData, $toEmail);
                return redirect($Prefix.'/forgot-password')->withSuccess('Your reset password link sent on your email.');
            } else {
                return redirect($Prefix.'/forgot-password')->withError('Email do not match with our records.');
            }
        }
        return view('vendors.forgotPassword')->with('param' , $request->domain);
    }

    /**
     * @method:      ValidationCheckRule
     * @params:      Request $request
     * @createdDate: 14-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function ValidationCheckRule(Request $request){
        $rules = ['password' => 'required|min:8|max:16'];
        $request->validate($rules);
    }

    /**
     * @method:      forgotPassword
     * @params:      Request $request
     * @createdDate: 14-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Reset admin password
     */
    public function resetPassword($Prefix , Request $request, $securityToken){
        if($request->isMethod('post')) {
            $this->ValidationCheckRule($request);
            $requestData = $request->all();
            $tokenData = PasswordReset::where('token', $securityToken)->first();
            if($tokenData){
                User::where('email', $tokenData->email)->update(['password' => Hash::make($requestData['password'])]);
                PasswordReset::where('email', $tokenData->email)->delete();
                return redirect($this->Prefix.'/login')->withSuccess('Your password has been updated successfully.');
            }else{
                return redirect($this->Prefix.'/login')->withError('Your link has been expired.');
            }
        }
        $checkToken = PasswordReset::where('token', $securityToken)->first();
        if(!empty($checkToken)){
            return view('admin.resetPassword', compact('securityToken'));
        }else{
            return redirect($this->Prefix.'/signin')->withError('Your link has been expired.');
        }
    }

    /**
     * @method:      logout
     * @params:      
     * @createdDate: 14-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Admin logout
     */
    public function logout(){
        Auth::guard('vendor')->logout();
        return redirect($this->Prefix."/signin")->withSuccess('You are logout successfully.');
    }

}
