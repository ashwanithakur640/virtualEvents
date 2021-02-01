<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Carbon\Carbon;
use App\Event;
use App\User;
use App\EventParticipant;
class DashboardController extends Controller
{
    /**
     * @method:      index
     * @params:      
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view user welcome
     */
    public function index(){
        return view('welcome');
    }

    /**
     * @method:      index
     * @params:      
     * @createdDate: 28-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To edit user profile
     */
    public function editProfile(){
        $data = User::where('id', Auth::user()->id)->first();
        $event = EventParticipant::where('user_id', Auth::user()->id)->pluck('event_id')->toArray();

       // print_r($event);
        // $i = isset($events) ?  '1' :  '2';
        // echo $i;


        if(!empty($event)){
            
            $events = Event::whereIn('id' , $event)->where('status', 'Active')->latest()->get();
            
            return view('customer.editProfile', compact('data', 'events' , 'event'));
        }
        return view('customer.editProfile', compact('data', 'event' ));
    }

    /**
     * @method:      validatorRules
     * @params:      Request $request
     * @createdDate: 28-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validatorRules(Request $request, $id = null){
        $rules = [
                    'first_name' => 'required|alpha|min:3|max:10',
                    'middle_name' => 'nullable|alpha|max:10',
                    'last_name' => 'nullable|alpha|max:10',
                    'email' => 'required|email|max:40|regex:/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i|unique:users,email,'.$id,
                    'image' => 'mimes:jpg,jpeg,png',
                    'mobile' => 'required',
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
                    ];
        $request->validate($rules,$messages);
    }

    /**
     * @method:      index
     * @params:      Request $request
     * @createdDate: 29-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To update user profile
     */
    public function updateProfile(Request $request){
        $this->validatorRules($request, Auth::user()->id);
        try{
            $reqData = $request->except(['_token']);
            $reqData['first_name'] = ucfirst(strtolower($reqData['first_name']));
            $reqData['middle_name'] = ucfirst(strtolower($reqData['middle_name']));
            $reqData['last_name'] = ucfirst(strtolower($reqData['last_name']));
            $reqData['email'] = strtolower($reqData['email']);
            /* Start upload user image */
            if(isset($reqData['image']) && !empty($reqData['image'])){
                $profilePicName = time() . '_' . $reqData['image']->getClientOriginalName();
                $path = public_path('/assets/images/profile_pic');
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                Helper::uploadImage($reqData['image'], $path, $profilePicName);
                $reqData['image'] = $profilePicName;
            }
            /* End upload user image */
            $reqData['role_id'] = User::ROLE_END_USER;
            $reqData['salutation'] = $reqData['salutation'];
            User::where('id', Auth::user()->id)->update($reqData);
            $notification=array(
                'message' => 'Profile has been updated successfully.',
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
     * @method:      ValidationCheck
     * @params:      Request $request
     * @createdDate: 29-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function ValidationCheck(Request $request){
        $rules = [
                    'current_password' => 'required|min:8|max:16',
                    'new_password' => 'required|min:8|max:16',
                    'confirm_password' => 'required|min:8|max:16',
                ];
        $messages = [
                        'current_password.required' => 'Current password field is required.',
                        'current_password.min' => 'Current password must be at least 8 characters.',
                        'current_password.max' => 'Current password may not be greater than 16 characters.',
                        'new_password.required' => 'New password field is required.',
                        'new_password.min' => 'New password must be at least 8 characters.',
                        'new_password.max' => 'New password may not be greater than 16 characters.',
                        'confirm_password.required' => 'Confirm password field is required.',
                        'confirm_password.min' => 'Confirm password must be at least 8 characters.',
                        'confirm_password.max' => 'Confirm password may not be greater than 16 characters.',
                    ];
        $request->validate($rules, $messages);
    }

    /**
     * @method:      updatePassword
     * @params:      Request $request
     * @createdDate: 29-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Update customer password
     */
    public function updatePassword(Request $request){
        $this->ValidationCheck($request);
        try{
            $reqData = $request->all();
            if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
                /* The passwords not matches */
                $notification=array(
                    'message' => 'Your current password does not matches with the password you provided. Please try again.',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
            /* validate that the new password is same as old one */
            if(strcmp($request->get('current_password'), $request->get('new_password')) == 0){
                /* Current password and new password are same */
                $notification=array(
                    'message' => 'New Password cannot be same as your current password. Please choose a different password.',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
            /* Change Password */
            $customer = Auth::user();
            $customer->password = Hash::make($reqData['new_password']);
            $customer->save();
            Auth::logout();
            $notification=array(
                'message' => 'Password has been changed successfully.',
                'alert-type' => 'success'
            );
            return redirect('/')->with($notification);
        } catch(\Exception $e) {
            $notification=array(
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            );
            return back()->with($notification);
        }
    }

    public function myEvents(){

    }

}
