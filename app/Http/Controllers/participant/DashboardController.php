<?php

namespace App\Http\Controllers\participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Event;
use App\Helpers\Helper;
use App\EveParticipant;
use DB;
class DashboardController extends Controller
{
    /**
     * @method:      index
     * @params:      
     * @createdDate: 14-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Admin dashboard
     */
    public function index(){
        //die('dsf');
    	return view('participant.dashboard' );
    }

    /**
     * @method:      editProfile
     * @params:      
     * @createdDate: 15-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Edit admin profile
     */
    public function editProfile(){
        $data = User::where('id', Auth::user()->id)->first();
        return view('participant.editProfile', compact('data'));
    }

    //my Events

    public function myEvents(){
        
        $data = EveParticipant::where('user_id', Auth::user()->id)->get();

        return view('participant.details', compact('data'));

    }


    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 15-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validator(Request $request, $id = null){
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
     * @method:      updateProfile
     * @params:      Request $request
     * @createdDate: 15-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Update admin profile
     */
    public function updateProfile(Request $request){
        $this->validator($request, Auth::user()->id);
        try{
            /*$reqData = $request->all();*/
            $reqData = $request->except(['_token']);
            $reqData['first_name'] = ucfirst(strtolower($reqData['first_name']));
            $reqData['middle_name'] = ucfirst(strtolower($reqData['middle_name']));
            $reqData['last_name'] = ucfirst(strtolower($reqData['last_name']));
            $reqData['email'] = strtolower($reqData['email']);
            if(isset($reqData['image']) && !empty($reqData['image'])){
                $profilePicName = time() . '_image.png';
                $path = public_path('/assets/images/profile_pic');
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                Helper::uploadImage($reqData['image'], $path, $profilePicName);
                $reqData['image'] = $profilePicName;
                User::where('id', Auth::user()->id)->update(['image' => $profilePicName]);
            }
            User::where('id', Auth::user()->id)->update($reqData);
            return back()->withSuccess('Profile has been updated successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      editPassword
     * @params:      
     * @createdDate: 15-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Edit admin password
     */
    public function editPassword(){
        return view('participant.changePassword');
    }

    /**
     * @method:      ValidationCheck
     * @params:      Request $request
     * @createdDate: 15-09-2020 (dd-mm-yyyy)
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
     * @createdDate: 15-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Update admin password
     */
    public function updatePassword($Prefix , Request $request){
        $this->ValidationCheck($request);
        try{
            $reqData = $request->all();
            if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
                /* The passwords not matches */
                return redirect()->back()->withError("Your current password does not matches with the password you provided. Please try again.");
            }
            /* validate that the new password is same as old one */
            if(strcmp($request->get('current_password'), $request->get('new_password')) == 0){
                /* Current password and new password are same */
                return redirect()->back()->withError("New Password cannot be same as your current password. Please choose a different password.");
            }
            /* Change Password */
            $admin = Auth::user();
            $admin->password = Hash::make($reqData['new_password']);
            $admin->save();
            Auth::guard('participant')->logout();
            return redirect($Prefix.'/signin')->withSuccess('Password has been changed successfully');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
