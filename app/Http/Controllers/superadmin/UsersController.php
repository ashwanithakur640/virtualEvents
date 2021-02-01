<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Helper;
use App\User;
use App\VendorDetail;
use App\Event;
use App\Session;
use App\EventParticipant;
use App\attendedConference;
use DataTables;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class UsersController extends Controller
{
    /**
     * @method:      index
     * @params:      Request $request
     * @createdDate: 15-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     View list of vendors
     */


    public function eventParticipants(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role_id', User::ROLE_PARTICIPANTS)
                                ->get();
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {  
                            
                                         
                            $action = '<a href="'.url("superadmin/vendors/show-vendor/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp
                                <a href="'.url('superadmin/vendors/edit-vendor/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp
                                <a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="'.url('superadmin/vendors/delete-vendor/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
         
        }
        return view('superadmin.events.eventsParticipants');
    }



    public function index(Request $request){
        if ($request->ajax()) {
            $data = User::where('role_id', '1')->get();
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {  
                            $additional = '';
                            if($modal->status == 'Not-verified'){
                               $additional = '<a href="'.url("superadmin/verify-user-account/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Verify Email"><i class="fa fa-check-square"></i></a>&nbsp';
                            }              
                            $action = '<a href="'.url("superadmin/vendors/show-vendor/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp
                                <a href="'.url('superadmin/vendors/edit-vendor/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp
                                <a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="'.url('superadmin/vendors/delete-vendor/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>'.$additional;
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('superadmin.vendors.index');
    }

    /**
     * @method:      create
     * @params:      
     * @createdDate: 15-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     create vendor form
     */
    public function create(){
    	return view('superadmin.vendors.create');
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
                    'email' => 'required|email|max:40|regex:/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i|unique:users,email',
                    'password' => 'required|min:8|max:16',
                    'image' => 'mimes:jpg,jpeg,png',
                    //'short_video' => 'required|mimes:mp4,3gp|max:100040',
                    'mobile' => 'required',
                    'confirm_password' => 'required|min:8|max:16',
                    'company_name' => 'required|min:3|max:50',
                    'company_city_location' => 'required|max:30',
                    'state' => 'required|max:20',
                    'country' => 'required|max:20',
                    'company_business_domain' => 'required|alpha|max:100',
                ];
        if($id) {
            $rules = array_merge($rules, [
                'email' => 'required|email|max:40|regex:/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i|unique:users,email,'.$id,
                'password' => 'nullable',
                'confirm_password' => 'nullable'
                //,'short_video' => 'nullable|mimes:mp4,3gp|max:100040',

            ]);
        }
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
                        'company_name.required' => 'Please enter company name.',
                        'company_name.max' => 'Company name may not be greater than 30 characters.',
                        'company_city_location.required' => 'Please enter company city location.',
                        'state.required' => 'Please enter state.',
                        'country.required' => 'Please enter country.',
                        'company_business_domain' => 'Please enter company business domain.',
                    ];
        $request->validate($rules,$messages);
    }

    /**
     * @method:      store
     * @params:      Request $request
     * @createdDate: 15-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     create vendor
     */
    public function store(Request $request){
        $this->validator($request);
        try{
            $reqData = $request->all();
            $reqData['first_name'] = ucfirst(strtolower($reqData['first_name']));
            $reqData['middle_name'] = ucfirst(strtolower($reqData['middle_name']));
            $reqData['last_name'] = ucfirst(strtolower($reqData['last_name']));
            $reqData['email'] = strtolower($reqData['email']);
            $reqData['password'] = Hash::make($reqData['password']);
            $reqData['salutation'] = $reqData['salutation'];
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

             $prefix = $reqData['company_business_domain'];
            unset($reqData['short_video'], $reqData['company_business_domain']);
            $user = User::create($reqData);
            if(!empty($vendorDetailInput)){
                $vendorDetailInput['user_id'] = $user->id;
                VendorDetail::create($vendorDetailInput);
            }

            /* Start sent welcome email to vendor */

            $subject = 'Welcome';            
            $templateName = 'emails.welcome';

            

            $mailData = [    
                            'name' => $user->first_name .' '. $user->middle_name.' '. $user->last_name,
                            'message' => 'Welcome to Virtual Events',
                            'email' => $user->email,
                            'password' => $request['password'],
                            'url' => url($prefix.'/signin'),
                        ];
            $toEmail = $user->email;
            Helper::sendMail($subject, $templateName, $mailData, $toEmail);

            /* End sent welcome email to vendor */

            return redirect('/superadmin/vendors')->withSuccess('Vendor has been created successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      verifyAccount
     * @params:      Request $request
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Verify user account by admin on email
     */
    public function verifyAccount($securityToken){
        $verifyTokenData = User::where('verify_token', $securityToken)->first();
        if($verifyTokenData){

            $ddd = User::where('email', $verifyTokenData->email)->first();
            User::where('email', $verifyTokenData->email)
                ->update(['status' => 'Active' ,'verify_token' => Null, 'email_verified_at' => Carbon::now()]);

                $prefix = Helper::prefix($ddd);
            /* Start sent account verify email to vendor */
            $subject = 'Welcome';            
            $templateName = 'emails.accountApproved';
            $mailData = [    
                            'name' => $verifyTokenData->first_name .' '. $verifyTokenData->middle_name.' '. $verifyTokenData->last_name,
                            'message' => 'Your Account Verify successfully',
                            'email' => $verifyTokenData->email,
                            'url' => url($prefix.'/signin'),
                        ];
            $toEmail = $verifyTokenData->email;
            Helper::sendMail($subject, $templateName, $mailData, $toEmail);
            /* End sent account verify email to vendor */
            return redirect('/superadmin/vendors')->withSuccess('Account has been verify successfully.');
        }else{
            return redirect('/superadmin/vendors')->withError('Link has been expired.');
        }
    }

    /**
     * @method:      verifyAccount
     * @params:      Request $request
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Verify user account by admin on vendors listing
     */
    public function verifyUserAccount($encryptId){
        $id = Helper::decrypt($encryptId);
        $data = User::findOrFail($id);
        try{
            if($data->status == "Not-verified"){
                User::where('email', $data->email)
                    ->update(['status' => 'Active' ,'verify_token' => Null, 'email_verified_at' => Carbon::now()]);
                /* Start sent account verify email to vendor */
                $subject = 'Welcome';            
                $templateName = 'emails.accountApproved';
                $mailData = [    
                                'name' => $data->first_name .' '. $data->middle_name.' '. $data->last_name,
                                'message' => 'Your Account Verify successfully',
                                'email' => $data->email,
                                'url' => url('vendor/login'),
                            ];
                $toEmail = $data->email;
                Helper::sendMail($subject, $templateName, $mailData, $toEmail);
                /* End sent account verify email to vendor */
                return back()->withSuccess('Account has been verify successfully.');
            }
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }    
    }

    /**
     * @method:      show
     * @params:      $encryptId
     * @createdDate: 17-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     View vendor detail
     */
    public function show($encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = User::with('vendor_details')->findOrFail($id);
        try{
            return view('superadmin.vendors.view', compact('data'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      edit
     * @params:      $encryptId
     * @createdDate: 17-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To edit vendor
     */
    public function edit($encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = User::with('vendor_details')->findOrFail($id);
        try{
            return view('superadmin.vendors.edit', compact('data'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      update
     * @params:      Request $request, $encryptId
     * @createdDate: 17-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To update vendor
     */
    public function update(Request $request, $encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $this->validator($request, $id);
        try{
            $reqData = $request->except(['_token', '_method']);
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
            User::where('id', $id)->update($reqData);
            if(!empty($vendorDetailInput)){
                VendorDetail::where('user_id', $id)->update($vendorDetailInput);
            }
            return redirect('superadmin/vendors/')->withSuccess('Vendor has been update successfully');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      destroy
     * @params:      $encryptId
     * @createdDate: 16-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To soft delete vendor
     */
    public function destroy($encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = User::findOrFail($id);
        try{
            User::where('id', $data->id)->update(['deleted_is_admin' => 1]);
            User::where('id', $data->id)->delete();
            VendorDetail::where('user_id', $data->id)->delete();
            Event::where('user_id', $data->id)->delete();
            Session::where('user_id', $data->id)->delete();
            return back()->withSuccess('Vendor has been deleted successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      customerIndex
     * @params:      
     * @createdDate: 16-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     View list of customers
     */
    public function customerIndex(Request $request){

        if ($request->ajax()) {
            $data = User::where('role_id', User::ROLE_END_USER)->get();
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {                
                             $action = '<a href="'.url("superadmin/customers/show-customer/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp
                                <a href="'.url('superadmin/customers/edit-customer/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp
                                <a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="'.url('superadmin/customers/delete-customer/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('superadmin.customers.index');
    }

    /**
     * @method:      show
     * @params:      $encryptId
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     View customer detail
     */
    public function customerShow($encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = User::findOrFail($id);
        try{

            $count = EventParticipant::where('user_id' , $id)->count();
            return view('superadmin.customers.view', compact('data' , 'count'));

        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      edit
     * @params:      $encryptId
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To edit customer
     */
    public function customerEdit($encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = User::findOrFail($id);
        try{
            return view('superadmin.customers.edit', compact('data'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validatorRuleCheck(Request $request, $id = null){
        $rules = [
                    'first_name' => 'required|alpha|min:3|max:10',
                    'middle_name' => 'nullable|alpha|min:3|max:10',
                    'last_name' => 'nullable|alpha|min:3|max:10',
                    'email' => 'required|email|max:40|regex:/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i|unique:users,email,'.$id,
                    'mobile' => 'required',
                    'image' => 'mimes:jpg,jpeg,png',
                    'company_name' => 'min:3|max:50',
                    'company_city_location' => 'max:30',
                    'state' => 'max:20',
                    'country' => 'max:20',
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
                        'company_name.max' => 'Company name may not be greater than 30 characters.',
                        'state.max' => 'Please enter state.',
                        'country.max' => 'Please enter country.',
                    ];
        $request->validate($rules,$messages);
    }

    /**
     * @method:      update
     * @params:      Request $request, $encryptId
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To update customer
     */
    public function customerUpdate(Request $request, $encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $this->validatorRuleCheck($request, $id);
        try{
            $reqData = $request->except(['_token', '_method']);
            $reqData['first_name'] = ucfirst(strtolower($reqData['first_name']));
            $reqData['middle_name'] = ucfirst(strtolower($reqData['middle_name']));
            $reqData['last_name'] = ucfirst(strtolower($reqData['last_name']));
            $reqData['email'] = strtolower($reqData['email']);
            /* Start upload customer image */
            if(isset($reqData['image']) && !empty($reqData['image'])){
                $profilePicName = time() . '_' . $reqData['image']->getClientOriginalName();
                $path = public_path('/assets/images/profile_pic');
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                Helper::uploadImage($reqData['image'], $path, $profilePicName);
                $reqData['image'] = $profilePicName;
            }
            /* End upload customer image */
            $reqData['role_id'] = User::ROLE_END_USER;
            User::where('id', $id)->update($reqData);
            return redirect('superadmin/customers/')->withSuccess('Customer has been update successfully');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      destroy
     * @params:      $encryptId
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To soft delete customer
     */
    public function customerDestroy($encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = User::findOrFail($id);
        try{
            User::where('id', $data->id)->update(['deleted_is_admin' => 1]);
            User::where('id', $data->id)->delete();
            return back()->withSuccess('Customer has been deleted successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function changeState($id){

       $user =  User::where('id' ,$id)->first();

       if(!empty($user)){

            if($user->status == 'Active'){

                $user->update([ 'status' => 'Inactive']);

            }else{
                if($user->status == 'Inactive'){

                    $user->update([ 'status' => 'Active']);
                }
            }

            return back()->withSuccess('User status updated');
       }

    }


    public function payState($id){

       $user =  User::where('id' ,$id)->first();

       if(!empty($user)){

            if($user->can_pay_offline ==  User::PAY_OFFLINE){

                $user->update([ 'can_pay_offline' => User::PAY_ONLINE]);

            }else{
                if($user->can_pay_offline == User::PAY_ONLINE){

                    $user->update([ 'can_pay_offline' => User::PAY_OFFLINE]);
                }
            }

            return back()->withSuccess('User status updated');
       }

    }

    public function customersEnrolled(Request $request){

        if ($request->ajax()) {
            
            $data = EventParticipant::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                        ->addIndexColumn()       
                        ->make(true);

        }
        return view('superadmin.reports.event-enrollment');

     }


    public function attendeesReport(Request $request){

        if ($request->ajax()) {
            
           $data = attendedConference::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                        ->addIndexColumn()       
                        ->make(true);

        }

        return view('superadmin.reports.attendees-report');

     } 




}
