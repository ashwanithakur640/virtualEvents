<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\User;
use App\Helpers\Helper;
use App\PasswordReset;
use App\VendorDetail;

class LoginController extends Controller
{
    /**
     * @method:      loginForm
     * @params:      
     * @createdDate: 17-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Show vendor login form
     */
    public function loginForm(){
    	return view('vendor.loginForm');
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
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
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Authendicate vendor credentials
     */
    public function login(Request $request){
        $this->validator($request);
        $vendor = User::withTrashed()->where([['email', $request->email], ['role_id', '1']])->first();
        if(!empty($vendor)){
            if($vendor->deleted_is_admin == '1'){
                return back()->withInput($request->only('email'))->withError('Your account has been deleted by Admin ! please contact to Admin.');
            }
            if($vendor->status == 'Inactive'){
                return back()->withInput($request->only('email'))->withError('Your account has been Inactive ! please contact to Admin.');
            }
            if($vendor->status == 'Not-verified'){
                return back()->withInput($request->only('email'))->withError('Your account has been Not-verified ! please contact to Admin.');
            }
            if(Auth::guard('vendor')->attempt(['email' => $request->email, 'password' => $request->password, 'role_id'=> '1'])) {
                $this->user= Auth::guard('vendor')->user();
                $prefix = Helper::prefix($this->user);
                $this->Prefix = $prefix;
                return redirect($this->Prefix . '/dashboard')->withSuccess('You are login successfully.');
            }
        }
        return back()->withInput($request->only('email'))->withError('Email and password do not match with our records.');
    }

    /**
     * @method:      registerForm
     * @params:      
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Show vendor register form
     */
    public function registerForm(){
        return view('vendor.signupForm');
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validatorRules(Request $request){
        $rules = [
                    'first_name' => 'required|alpha|min:3|max:10',
                    'middle_name' => 'nullable|alpha|min:3|max:10',
                    'last_name' => 'nullable|alpha|min:3|max:10',
                    'email' => 'required|email|max:40|regex:/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i|unique:users,email',
                    'password' => 'required|min:8|max:16',
                    'image' => 'mimes:jpg,jpeg,png',
                    'short_video' => 'required|mimes:mp4,3gp|max:100040',
                    'mobile' => 'required',
                    'confirm_password' => 'required|min:8|max:16',
                    'company_business_domain' => 'required|max:100',
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
                        'email.unique' => 'Email already exists in our records.',
                        'mobile.required' => 'Please enter your mobile.',
                        'password.required' => 'Please enter your password.',
                        'password.min' => 'Password must be at least 8 characters.',
                        'password.max' => 'Password may not be greater than 16 characters.',
                        'confirm_password.required' => 'Confirm password field is required.',
                        'confirm_password.min' => 'Confirm password must be at least 8 characters.',
                        'confirm_password.max' => 'Confirm password may not be greater than 16 characters.',
                        'company_business_domain' => 'Please enter company business domain.',
                    ];
        $request->validate($rules,$messages);
    }

    /**
     * @method:      register
     * @params:      Request $request
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     register vendor
     */
    public function register(Request $request){
         $this->validatorRules($request);
        try{
            $reqData = $request->all();
            $reqData['first_name'] = ucfirst(strtolower($reqData['first_name']));
            $reqData['middle_name'] = ucfirst(strtolower($reqData['middle_name']));
            $reqData['last_name'] = ucfirst(strtolower($reqData['last_name']));
            $reqData['email'] = strtolower($reqData['email']);
            $reqData['password'] = Hash::make($reqData['password']);
            /* Start upload vendor image */
            if(isset($reqData['image']) && !empty($reqData['image'])){
                $profilePicName = time() . '_' . $reqData['image']->getClientOriginalName();
                $path = public_path('/assets/images/profile_pic');
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                Helper::uploadImage($reqData['image'], $path, $profilePicName);
                $reqData['image'] = $profilePicName;
            }
            /* End upload vendor image */
            /* Start upload vendor short_video */
            $vendorDetailInput['company_business_domain'] = $reqData['company_business_domain'];
            if(isset($reqData['short_video']) && !empty($reqData['short_video'])){
                $shortVideoName = time() . '_' . $reqData['short_video']->getClientOriginalName();
                $path = public_path('/assets/short_videos');
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                Helper::uploadVideo($reqData['short_video'], $path, $shortVideoName);
                $vendorDetailInput['short_video'] = $shortVideoName;
            }
            /* End upload vendor short_video */
            $newSecurityToken = md5(time());
            $reqData['role_id'] = '1';
            $reqData['status'] = 'Not-verified';
            $reqData['verify_token'] = $newSecurityToken;
            unset($reqData['short_video']);
            $user = User::create($reqData);
            if(!empty($vendorDetailInput)){
                $vendorDetailInput['user_id'] = $user->id;
                VendorDetail::create($vendorDetailInput);
            }
            /* Start sent vefification email to admin */
            $admin = User::where('role_id', '0')->first();
            $subject = 'verification Account';            
            $templateName = 'emails.verifyAccount';
            $mailData = [    
                            'name' => $user->first_name .' '. $user->middle_name.' '. $user->last_name,
                            'email' => $user->email,
                            'message' => 'Verify Vendor Account',
                            'url' => url('admin/verify-account/'.$newSecurityToken)
                        ];
            $toEmail = $admin->email;
            Helper::sendMail($subject, $templateName, $mailData, $toEmail);
            /* End sent vefification email to admin */
            return redirect('/vendor/login')->withSuccess('Your account has been created successfully ! Account verification email sent to your email address.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      ValidationCheck
     * @params:      Request $request
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
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
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Forgot vendor password
     */
    public function forgotPassword(Request $request){
        if ($request->isMethod('post')) {
            $this->ValidationCheck($request);
            $requestData = $request->all();
            $vendor = User::withTrashed()->where([['email', $requestData['email']], ['status', 'Active'], ['role_id', '1']])->first();
            if(!empty($vendor)) {
                if($vendor->deleted_is_admin == '1'){
                    return back()->withInput($request->only('email'))->withError('Your account has been deleted by Admin.');
                }
                if($vendor->status == 'Inactive'){
                    return back()->withInput($request->only('email'))->withError('Your account has been Inactive.');
                }
                $emailData = PasswordReset::where('email', $requestData['email'])->first();
                if(!empty($emailData)) {
                    PasswordReset::where('email', $requestData['email'])->delete();
                }
                $newSecurityToken = md5(time());
                $data = [
                            'email' => $vendor->email,
                            'token' => $newSecurityToken 
                        ];
                PasswordReset::create($data); 
                $subject = 'Reset Password';            
                $templateName = 'emails.resetPassword';
                $mailData = [    
                                'name' => $vendor->first_name .' '. $vendor->middle_name.' '. $vendor->last_name,
                                'message' => url('vendor/reset-password/'.$newSecurityToken)
                            ];
                $toEmail = $vendor->email;
                Helper::sendMail($subject, $templateName, $mailData, $toEmail);
                return redirect('/vendor/forgot-password')->withSuccess('Your reset password link sent on your email.');
            } else {
                return redirect('/vendor/forgot-password')->withError('Email do not match with our records.');
            }
        }
        return view('vendor.forgotPassword');
    }

    /**
     * @method:      ValidationCheckRule
     * @params:      Request $request
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
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
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Reset vendor password
     */
    public function resetPassword(Request $request, $securityToken){
        if($request->isMethod('post')) {
            $this->ValidationCheckRule($request);
            $requestData = $request->all();
            $tokenData = PasswordReset::where('token', $securityToken)->first();
            if($tokenData){
                User::where('email', $tokenData->email)->update(['password' => Hash::make($requestData['password'])]);
                PasswordReset::where('email', $tokenData->email)->delete();
                return redirect('/vendor/login')->withSuccess('Your password has been updated successfully.');
            }else{
                return redirect('/vendor/login')->withError('Your link has been expired.');
            }
        }
        $checkToken = PasswordReset::where('token', $securityToken)->first();
        if(!empty($checkToken)){
            return view('vendor.resetPassword', compact('securityToken'));
        }else{
            return redirect('/vendor/login')->withError('Your link has been expired.');
        }
    }

    /**
     * @method:      logout
     * @params:      
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Vendor logout
     */
    public function logout(){
        Auth::guard('vendor')->logout();
        return redirect("vendor/login")->withSuccess('You are logout successfully.');
    }

}
