<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(){
        return view('home');
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 21-09-2020 (dd-mm-yyyy)
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
     * @createdDate: 21-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Authendicate user credentials
     */
    public function login(Request $request){
        $this->validator($request);
        $user = User::withTrashed()->where([['email', $request->email],['role_id', User::ROLE_END_USER ]])->first();
        if(!empty($user)){
            if($user->deleted_is_admin == '1'){
                return back()->withInput($request->only('email'))->withError('Your account has been deleted by Admin.');
            }
            if($user->status == 'Inactive'){
                return back()->withInput($request->only('email'))->withError('Your account has been Inactive.');
            }
            if(Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password, 'role_id'=> User::ROLE_END_USER])) {
                /*return redirect('/welcome')->withSuccess('You are login successfully.');*/
                $notification=array(
                    'message' => 'You are login successfully.',
                    'alert-type' => 'success'
                );
                return redirect('/welcome')->with($notification);
            }
        }
        /*return back()->withInput($request->only('email'))->withError('Email and password do not match with our records.');*/
        $notification=array(
            'message' => 'Email and password do not match with our records.',
            'alert-type' => 'error'
        );
        return back()->withInput($request->only('email'))->with($notification);
    }
    
}
