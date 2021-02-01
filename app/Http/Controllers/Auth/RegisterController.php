<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Helpers\Helper;
use DB;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Http\Request;

class RegisterController extends Controller  //implements Authenticatable
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
     use AuthenticableTrait;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    /*protected $redirectTo = RouteServiceProvider::HOME;*/
    protected $redirectTo = "/welcome";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    // public function register(Request $request)
    // {
    //     $this->validator($request->all())->validate();

    //     event(new Registered($user = $this->create($request->all())));

    //     $this->guard()->login($user);

    //     return $this->registered($request, $user)
    //         ?: redirect($this->redirectPath());
    // }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|alpha|min:3|max:10',
            'middle_name' => 'nullable|alpha|min:3|max:10',
            'last_name' => 'nullable|alpha|min:3|max:10',
            'email' => 'required|email|max:40|regex:/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i',
            /*'password' => ['required', 'string', 'min:8', 'confirmed'],*/
            'password' => 'required|min:8|max:16',
            'confirm_password' => 'required|min:8|max:16',
            'mobile' => 'required',
            'image' => 'mimes:jpg,jpeg,png',
        ], 
        [
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
            'password.confirmed' => 'Password and confirm password does not match.',
            'confirm_password.required' => 'Confirm password field is required.',
            'confirm_password.min' => 'Confirm password must be at least 8 characters.',
            'confirm_password.max' => 'Confirm password may not be greater than 16 characters.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

protected function registeration(Request $data)
    {

  $user = User::where('email', $data['email'])->where('role_id' , User::ROLE_END_USER)->first();


     
        if(!empty($user)){
            return redirect('/login')->withError('Customer with this email address already exists.');
        }
        else{

          //  die('sdfs');
            $data1['first_name'] = ucfirst(strtolower($data['first_name']));
            $data1['middle_name'] = ucfirst(strtolower($data['middle_name']));
            $data1['last_name'] = ucfirst(strtolower($data['last_name']));
            $data1['email'] = strtolower($data['email']);
            $data1['password'] = Hash::make($data['password']);
            /* Start upload vendor image */
            if(isset($data['image']) && !empty($data['image'])){
                /*$profilePicName = time() . '_image.png';*/
                $profilePicName = time() . '_' . $data['image']->getClientOriginalName();
                $path = public_path('/assets/images/profile_pic');
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                Helper::uploadImage($data['image'], $path, $profilePicName);
                $data1['image'] = $profilePicName;
            }
            /* End upload vendor image */
            $data1['salutation'] = $data['salutation'];
            $data1['role_id'] = User::ROLE_END_USER;

            User::create($data1);


            if(Auth::guard('web')->attempt(['email' => $data->email, 'password' => $data->password, 'role_id'=> User::ROLE_END_USER])) {
                $this->user= Auth::guard('web')->user();
                
                
                return redirect('/welcome')->withSuccess('You are login successfully.');
            }


        }

        
    }

/* 

if(Auth::guard('vendor')->attempt(['email' => $request->email, 'password' => $request->password, 'role_id'=> '1'])) {
                $this->user= Auth::guard('vendor')->user();
                $prefix = Helper::prefix($this->user);
                $this->Prefix = $prefix;
                
                return redirect($this->Prefix . '/dashboard')->withSuccess('You are login successfully.');
            }


*/



    protected function create(array $data)
    {
//DB::enableQueryLog();
  $user = User::where('email', $data['email'])->where('role_id' , User::ROLE_END_USER)->first();

//dd(DB::getQueryLog());
      
print_r($user);
     
        if(!empty($user)){
            return redirect('/')->withError('Customer with this email address already exists.');

            
        }
        else{

          //  die('sdfs');
            $data['first_name'] = ucfirst(strtolower($data['first_name']));
            $data['middle_name'] = ucfirst(strtolower($data['middle_name']));
            $data['last_name'] = ucfirst(strtolower($data['last_name']));
            $data['email'] = strtolower($data['email']);
            $data['password'] = Hash::make($data['password']);
            /* Start upload vendor image */
            if(isset($data['image']) && !empty($data['image'])){
                /*$profilePicName = time() . '_image.png';*/
                $profilePicName = time() . '_' . $data['image']->getClientOriginalName();
                $path = public_path('/assets/images/profile_pic');
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                Helper::uploadImage($data['image'], $path, $profilePicName);
                $data['image'] = $profilePicName;
            }
            /* End upload vendor image */
            $data['salutation'] = $data['salutation'];
            $data['role_id'] = User::ROLE_END_USER;
            return User::create($data);
        }

        
    }
}
