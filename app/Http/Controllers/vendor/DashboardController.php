<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Helper;
use App\User;
use App\Event;
use App\Session;
use App\VendorDetail;

class DashboardController extends Controller
{
    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 05-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validator(Request $request, $id = null){
        $rules = [
                    'company_name' => 'required|max:50',
                    'company_city_location' => 'required|max:30',
                    'state' => 'required|max:20',
                    'country' => 'required|max:20',
                ];
        $messages = [
                        'company_name.required' => 'Please enter your name.',
                        'company_name.max' => 'Company name may not be greater than 50 characters.',
                        'company_city_location.required' => 'Please enter company city location.',
                        'company_city_location.max' => 'Company city location may not be greater than 30 characters.',
                        'state.required' => 'Please enter State.',
                        'state.max' => 'State may not be greater than 20 characters.',
                        'country.required' => 'Please enter Country.',
                        'country.max' => 'Country may not be greater than 20 characters.',
                    ];
        $request->validate($rules,$messages);
    }

    /**
     * @method:      addProfile
     * @params:      Request $request
     * @createdDate: 05-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Add Vendor profile
     */
    public function addProfile(Request $request){
        $this->validator($request);
        try{
            $reqData = $request->except(['_token']);
            User::where('id', Auth::user()->id)->update($reqData);
            return back()->withSuccess('Profile has been added successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      index
     * @params:      
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Vendor dashboard
     */
    public function index(){
        $data = User::where('id', Auth::user()->id)->first();

        $today = date('Y-m-d');

        $userEvents = Event::where([['status', 'Active'], ['user_id', Auth::user()->id]])->count();
        $userSession = Session::where([['status', 'Active'], ['user_id', Auth::user()->id]])->count();

        /* other data  */

        $todayEvents = Event::where('start_date_time','<=', $today)->where('end_date_time','>=', $today)->where('user_id','=', Auth::user()->id)->count();

        $todayEventsDetails = Event::where('start_date_time','<=', $today)->where('end_date_time','>=', $today)->where('user_id','=', Auth::user()->id)->get();

        $holdEvents = Event::where('status','=', 'Onhold')->where('user_id','=', Auth::user()->id)->count();

        $rescheduledEvents = Event::where('rescheduled','=', Event::RESCHEDULED)->where('user_id','=', Auth::user()->id)->count();

        $currentWeekEvents = Event::count();
        $futureEvents = Event::where('start_date_time','>', $today)->where('user_id','=', Auth::user()->id)->count();
        
        /* current weeks date */

        $signupweek = date('Y-m-d');
        /*start day*/
        for($i = 0; $i <7 ; $i++){

            $date = date('Y-m-d', strtotime("-".$i."days", strtotime($signupweek)));
            $dayName = date('D', strtotime($date));
            if($dayName == "Sun"){
                $weekStartDate =  $date ;
            }
        }
        /*end day*/
        for($i = 0; $i <7 ; $i++){
            $date = date('Y-m-d', strtotime("+".$i."days", strtotime($signupweek)));
            $dayName = date('D', strtotime($date));
            if($dayName == "Sat"){
               $weekEndDate = $date;
            }
        }

        $weekEvents = Event::where('start_date_time','>=', $weekStartDate)->where('end_date_time','<=', $weekEndDate)->where('user_id','=', Auth::user()->id)->count();

        $pastEvents = Event::where('end_date_time','<', $today)->where('user_id','=', Auth::user()->id)->count();


        /* end other data */


    	return view('vendor.dashboard', compact('userEvents', 'userSession', 'data' ,  'todayEvents' , 'futureEvents' , 'weekEvents' , 'todayEventsDetails', 'pastEvents' , 'holdEvents' , 'rescheduledEvents'));
    }

    /**
     * @method:      editProfile
     * @params:      
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Edit Vendor profile
     */
    public function editProfile(){
        $data = User::with('vendor_details')->where('id', Auth::user()->id)->first();
        return view('vendor.editProfile', compact('data'));
    }

    /**
     * @method:      validatorRules
     * @params:      Request $request
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
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
                    'short_video' => 'mimes:mp4,3gp|max:100040',
                    'company_name' => 'required|min:3|max:50',
                    'company_city_location' => 'required|max:30',
                    'state' => 'required|max:20',
                    'country' => 'required|max:20',
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
                        'company_name.required' => 'Please enter company name.',
                        'company_name.max' => 'Company name may not be greater than 30 characters.',
                        'company_city_location' => 'Please enter company city location.',
                        'state' => 'Please enter state.',
                        'country' => 'Please enter country.',
                        'company_business_domain' => 'Please enter company business domain.',
                    ];
        $request->validate($rules,$messages);
    }

    /**
     * @method:      updateProfile
     * @params:      Request $request
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Update Vendor profile
     */
    public function updateProfile(Request $request){
        $this->validatorRules($request, Auth::user()->id);
        try{
            $reqData = $request->except(['_token']);
            $reqData['first_name'] = ucfirst(strtolower($reqData['first_name']));
            $reqData['middle_name'] = ucfirst(strtolower($reqData['middle_name']));
            $reqData['last_name'] = ucfirst(strtolower($reqData['last_name']));
            $reqData['email'] = strtolower($reqData['email']);
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
            $reqData['role_id'] = '1';
            unset($reqData['short_video'], $reqData['company_business_domain']);
            User::where('id', Auth::user()->id)->update($reqData);
            
            if(!empty($vendorDetailInput)){
                VendorDetail::where('user_id', Auth::user()->id)->update($vendorDetailInput);
            }
            return back()->withSuccess('Profile has been updated successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      editPassword
     * @params:      
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Edit Vendor password
     */
    public function editPassword(){
        return view('vendor.changePassword');
    }

    /**
     * @method:      ValidationCheck
     * @params:      Request $request
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
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
     * @createdDate: 18-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Update Vendor password
     */
    public function updatePassword(Request $request){
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
            $vendor = Auth::user();
            $vendor->password = Hash::make($reqData['new_password']);
            $vendor->save();
            Auth::guard('vendor')->logout();
            return redirect('vendor/login')->withSuccess('Password has been changed successfully');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }
}
