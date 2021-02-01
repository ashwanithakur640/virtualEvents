<?php

namespace App\Http\Controllers\vendors;

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
use App\EveParticipant;
use DataTables;
use Carbon\Carbon;
use DB;

class UsersController extends Controller
{
    /**
     * @method:      index
     * @params:      Request $request
     * @createdDate: 15-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     View list of vendors
     */
    public function index(Request $request){
               if ($request->ajax()) {

            $data = User::where('role_id', User::ROLE_PARTICIPANTS)->where('vendor_id' , Auth::id() )->get();

            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {  
                            $additional = '';

                            // if($modal->status == 'Not-verified'){
                            //    $additional = '<a href="'.url("admin/verify-user-account/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Verify Email"><i class="fa fa-check-square"></i></a>&nbsp';
                            // }

                            $action = '<a href="'.url($this->Prefix."/show-participant/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp
                                <a href="'.url($this->Prefix.'/edit-participant/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp
                                <a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="'.url($this->Prefix.'/delete-participant/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>'.$additional;
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('vendors.participants.index');
    }

    /**
     * @method:      create
     * @params:      
     * @createdDate: 15-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     create vendor form
     */
    public function create(){
    	return view('vendors.participants.create');
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
                    'email' => 'required|email|max:40|regex:/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i',
                    'password' => 'required|min:8|max:16',
                     'image' => 'mimes:jpg,jpeg,png',
                    // 'short_video' => 'required|mimes:mp4,3gp|max:100040',
                    'mobile' => 'required',
                    'confirm_password' => 'required|min:8|max:16',
                    'company_name' => 'required|min:3|max:50',
                    'company_city_location' => 'required|max:30',
                    'state' => 'required|max:20',
                    'country' => 'required|max:20',
                    //'company_business_domain' => 'required|max:100',
                ];
        if($id) {
            $rules = array_merge($rules, [
                'email' => 'required|email|max:40|regex:/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i',
                'password' => 'nullable',
                'confirm_password' => 'nullable',
                //'short_video' => 'nullable|mimes:mp4,3gp|max:100040',

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
                       //'company_business_domain' => 'Please enter company business domain.',
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

            $user = User::where('email', $reqData['email'])->where('role_id' , User::ROLE_PARTICIPANTS )->where('vendor_id' , Auth::id() )->first();


            if(empty($user)){
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
               
                $reqData['role_id'] = User::ROLE_PARTICIPANTS;
                $reqData['vendor_id'] = Auth::id() ;

                $user = User::create($reqData);
                /* Start sent welcome email to vendor */
                $subject = 'Welcome';            
                $templateName = 'emails.welcome';
                $mailData = [    
                                'name' => $user->first_name .' '. $user->middle_name.' '. $user->last_name,
                                'message' => 'Welcome to Virtual Events',
                                'email' => $user->email,
                                'password' => $request['password'],
                                'url' => url($this->Prefix.'/participant-signin'),
                            ];
                $toEmail = $user->email;
                Helper::sendMail($subject, $templateName, $mailData, $toEmail);
                /* End sent welcome email to vendor */
                return redirect($this->Prefix. '/participants')->withSuccess('Participant has been created successfully.');
            }else{
                return redirect($this->Prefix.'/create-participant')->withError('Participant already exists');
            }


            
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
            User::where('email', $verifyTokenData->email)
                ->update(['status' => 'Active' ,'verify_token' => Null, 'email_verified_at' => Carbon::now()]);
            /* Start sent account verify email to vendor */
            $subject = 'Welcome';            
            $templateName = 'emails.accountApproved';
            $mailData = [    
                            'name' => $verifyTokenData->first_name .' '. $verifyTokenData->middle_name.' '. $verifyTokenData->last_name,
                            'message' => 'Your Account Verify successfully',
                            'email' => $verifyTokenData->email,
                            'url' => url($this->Prefix.'/participant/login'),
                        ];
            $toEmail = $verifyTokenData->email;
            Helper::sendMail($subject, $templateName, $mailData, $toEmail);
            /* End sent account verify email to vendor */
            return redirect($this->Prefix. '/participants')->withSuccess('Account has been verify successfully.');
        }else{
            return redirect($this->Prefix . '/participants')->withError('Link has been expired.');
        }
    }

    public function verifyVendorAccount($domain , $securityToken){

        $verifyTokenData = User::where('verify_token', $securityToken)->first();
        if($verifyTokenData){

            $ddd = User::where('email', $verifyTokenData->email)->first();
            User::where('email', $verifyTokenData->email)
                ->update(['status' => 'Active' ,'verify_token' => Null, 'email_verified_at' => Carbon::now()]);

           
            /* Start sent account verify email to vendor */
            $subject = 'Welcome';            
            $templateName = 'emails.accountApproved';
            $mailData = [    
                            'name' => $verifyTokenData->first_name .' '. $verifyTokenData->middle_name.' '. $verifyTokenData->last_name,
                            'message' => 'Your Account Verify successfully',
                            'email' => $verifyTokenData->email,
                            'url' => url($domain.'/signin'),
                        ];
            $toEmail = $verifyTokenData->email;
            Helper::sendMail($subject, $templateName, $mailData, $toEmail);
            /* End sent account verify email to vendor */
            return redirect($domain.'/signin')->withSuccess('Account has been verify successfully.');
        }else{
            return redirect($domain.'/signin')->withError('Link has been expired.');
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
                                'url' => url($this->Prefix.'/participant/login'),
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
    public function show($prefix ,$encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = User::with('vendor_details')->findOrFail($id);

        $events = EveParticipant::with('event')->where('user_id',$data->id)->get();

        try{
            return view('vendors.participants.view', compact('data', 'events'));
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
    public function edit($prefix , $encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = User::findOrFail($id);
        try{
            return view('vendors.participants.edit', compact('data'));
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
    public function update($prefix ,Request $request, $encryptId)
    {

       // print_r()
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
            
            $reqData['role_id'] = User::ROLE_PARTICIPANTS;
            $reqData['vendor_id'] = Auth::id() ;
            User::where('id', $id)->update($reqData);
            
            return redirect($this->Prefix . '/participants')->withSuccess('Participant has been update successfully');
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
    public function destroy($prefix , $encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = User::findOrFail($id);
        try{
            User::where('id', $data->id)->update(['deleted_is_admin' => 1]);
            User::where('id', $data->id)->delete();
            
            Event::where('user_id', $data->id)->delete();
            Session::where('user_id', $data->id)->delete();
            return back()->withSuccess('Participant has been deleted successfully.');
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
    public function endUser(Request $request){

        if ($request->ajax()) {
            $data = User::where('role_id', User::ROLE_END_USER)->where('vendor_id' , Auth::id() )->get();
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {                
                             $action = '<a href="'.url($this->Prefix."/show-customer/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp
                                <a href="'.url($this->Prefix.'/edit-customer/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp
                                <a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="'.url($this->Prefix.'/delete-customer/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('vendors.customers.index');
    }

    /**
     * @method:      show
     * @params:      $encryptId
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     View customer detail
     */
    public function customerShow($prefix , $encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = User::findOrFail($id);
        try{


            $count = EventParticipant::where('user_id' , $id)->count();

            return view('vendors.customer.view', compact('data' , 'count'));
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
    public function customerEdit($prefix ,  $encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = User::findOrFail($id);
        try{
            return view('vendors.customers.edit', compact('data'));
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
    public function customerUpdate($prefix ,  Request $request, $encryptId)
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
            $reqData['vendor_id'] = Auth::id() ;


            User::where('id', $id)->update($reqData);
            return redirect($this->Prefix.'/end-user/')->withSuccess('Customer has been update successfully');
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
    public function customerDestroy($prefix , $encryptId)
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

    public function videoContent(){
        
        $getDetail = VendorDetail::where('user_id', Auth::id())->first();
        if(!$getDetail){
            Flash::error('Details not found');
            return view('vendors.profile.details');
        }
        
        return view('vendors.details')->with('getDetail', $getDetail)->with('param' , $this->Prefix);
    }

    public function saveContent(Request $request){

        $this->validate($request,
            ['welcome_text' => 'required|min:3',
            'description' => 'required',

            'case_study' => 'required_without:case_study_link',
            'case_study_link' => 'required_without:case_study',

            'resource' => 'required_without:resource_link',
            'resource_link' => 'required_without:resource',

            'presentation' => 'required_without:presentation_link',
            'presentation_link' => 'required_without:presentation',
            ]);
        $input = $request->all();
        $id = Auth::id();

        //upload short video for details.
        $vendorDetails = VendorDetail::where('user_id', $id)->first();        
        if ($request->hasFile('short_video')) {
            if ($request->file('short_video')->isValid()) {
                //
                $validated = $request->validate([
                    //'name' => 'string|max:40',
                    'short_video' => 'mimes:mp4,3gp|max:100040',
                ]);
                $filename = \Carbon\Carbon::now()->timestamp;
                $extension = $request->short_video->extension();
                $destinationPath = public_path('/sample_video');

                //Check if file exist unlink the file and upload new
                if($vendorDetails){


                    $filePath = public_path('/sample_video/').$vendorDetails->short_video;
                    if(file_exists($filePath) && $vendorDetails->short_video != NULL){
                        unlink($filePath);
                    }

                    $request->short_video->move($destinationPath, $filename.'.'.$extension);
                    $details['short_video'] = $filename.'.'.$extension; 
                }
            }
        }
     
        //upload document for details.
        if ($request->hasFile('document')) {
            if ($request->file('document')->isValid()) {
                    //
                    $validated = $request->validate([
                        'document' => 'mimes:pdf|max:100040',
                    ]);
                    $filename = 'vendor_document'.\Carbon\Carbon::now()->timestamp;
                    $extension = $request->document->extension();
                    $destinationPath = public_path('/assets/images/document');

                    //Check if file exist unlink the file and upload new
                    if($vendorDetails){
                        $filePath = public_path('/assets/images/document').$vendorDetails->document;
                        if(file_exists($filePath) && $vendorDetails->document != NULL){
                            unlink($filePath);
                        }

                    $request->document->move($destinationPath, $filename.'.'.$extension);
                    $details['document'] = $filename.'.'.$extension; 
                }
            }
        }
          
        //upload case_study for details.
        if ($request->hasFile('case_study')) {
            if ($request->file('case_study')->isValid()) {
                    //
                    // $validated = $request->validate([
                    //     'document' => 'mimes:pdf|max:100040',
                    // ]);
                    $filename = 'case_study'.\Carbon\Carbon::now()->timestamp;
                    $extension = $request->case_study->extension();
                    $destinationPath = public_path('/assets/images/document');

                    //Check if file exist unlink the file and upload new
                    if($vendorDetails){
                        $filePath = public_path('/assets/images/document').$vendorDetails->case_study;
                        if(file_exists($filePath) && $vendorDetails->case_study != NULL){
                            unlink($filePath);
                        }

                    $request->case_study->move($destinationPath, $filename.'.'.$extension);
                    $details['case_study'] = $filename.'.'.$extension; 
                }
            }
        }
          
        //upload resource for details.
        if ($request->hasFile('resource')) {
            if ($request->file('resource')->isValid()) {
                    //
                    // $validated = $request->validate([
                    //     'document' => 'mimes:pdf|max:100040',
                    // ]);
                    $filename = 'resource'.\Carbon\Carbon::now()->timestamp;
                    $extension = $request->resource->extension();
                    $destinationPath = public_path('/assets/images/document');

                    //Check if file exist unlink the file and upload new
                    if($vendorDetails){
                        $filePath = public_path('/assets/images/document').$vendorDetails->resource;
                        if(file_exists($filePath) && $vendorDetails->resource != NULL){
                            unlink($filePath);
                        }

                    $request->resource->move($destinationPath, $filename.'.'.$extension);
                    $details['resource'] = $filename.'.'.$extension; 
                }
            }
        }
          
        //upload presentation for details.
        if ($request->hasFile('presentation')) {
            if ($request->file('presentation')->isValid()) {
                    //
                    // $validated = $request->validate([
                    //     'document' => 'mimes:pdf|max:100040',
                    // ]);
                    $filename = 'presentation'.\Carbon\Carbon::now()->timestamp;
                    $extension = $request->presentation->extension();
                    $destinationPath = public_path('/assets/images/document');

                    //Check if file exist unlink the file and upload new
                    if($vendorDetails){
                        $filePath = public_path('/assets/images/document').$vendorDetails->presentation;
                        if(file_exists($filePath) && $vendorDetails->presentation != NULL){
                            unlink($filePath);
                        }

                    $request->presentation->move($destinationPath, $filename.'.'.$extension);
                    $details['presentation'] = $filename.'.'.$extension; 
                }
            }
        }
     
        $details['welcome_text'] = $input['welcome_text'];
        $details['description'] = $input['description'];
        // $details['presentation_link'] = $input['presentation_link'];
        // $details['resource_link'] = $input['resource_link'];
        // $details['case_study_link'] = $input['case_study_link'];

        $vendorDetails = VendorDetail::where('id', $vendorDetails->id)
                            ->update($details);

        return redirect($this->Prefix. '/video-content')->withSuccess('Data has been created successfully.');
        //Flash::success('Details updated successfully.');
        return redirect(route('video-content'));

    }

    public function indexEmp(Request $request){
               if ($request->ajax()) {

            $data = User::where('role_id', User::ROLE_EMPLOYEE)->where('vendor_id' , Auth::id() )->get();

            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {  
                            $additional = '';

                            // if($modal->status == 'Not-verified'){
                            //    $additional = '<a href="'.url("admin/verify-user-account/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Verify Email"><i class="fa fa-check-square"></i></a>&nbsp';
                            // }

                            $action = '<a href="'.url($this->Prefix."/show-employee/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp
                                <a href="'.url($this->Prefix.'/edit-employee/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp
                                <a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="'.url($this->Prefix.'/delete-employee/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>'.$additional;
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('vendors.employee.index');
    }

    /**
     * @method:      create
     * @params:      
     * @createdDate: 15-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     create vendor form
     */
    public function createEmp(){
        return view('vendors.employee.create');
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 15-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
   

    /**
     * @method:      store
     * @params:      Request $request
     * @createdDate: 15-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     create vendor
     */
    public function storeEmp(Request $request){
        $this->validator($request);
        //try{


            $reqData = $request->all();

             $user = User::where('email', $reqData['email'])->where('role_id' , User::ROLE_EMPLOYEE )->where('vendor_id' , Auth::id() )->first();

              if(empty($user)){
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
           
            $reqData['role_id'] = User::ROLE_EMPLOYEE;
            $reqData['vendor_id'] = Auth::id() ;

            $user = User::create($reqData);
            /* Start sent welcome email to vendor */
            $subject = 'Welcome';            
            $templateName = 'emails.welcome';
            $mailData = [    
                            'name' => $user->first_name .' '. $user->middle_name.' '. $user->last_name,
                            'message' => 'Welcome to Virtual Events',
                            'email' => $user->email,
                            'password' => $request['password'],
                            'url' => url($this->Prefix.'/employee/login'),
                        ];
            $toEmail = $user->email;
            Helper::sendMail($subject, $templateName, $mailData, $toEmail);
            /* End sent welcome email to vendor */
            return redirect($this->Prefix. '/employee')->withSuccess('Employee has been created successfully.');

            }else{
                return redirect($this->Prefix.'/create-employee')->withError('Employee already exists');
            }
        


        // } catch(\Exception $e) {
        //     return back()->withError($e->getMessage());
        // }
    }


    public function showEmp($prefix ,$encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = User::findOrFail($id);

    //    $events = EveParticipant::with('event')->where('user_id',$data->id)->get();

        try{
            return view('vendors.employee.view', compact('data'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }



     public function editEmp($prefix , $encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = User::findOrFail($id);
        try{
            return view('vendors.employee.edit', compact('data'));
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
    public function updateEmp($prefix ,Request $request, $encryptId)
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
            
            $reqData['role_id'] = User::ROLE_EMPLOYEE;
            $reqData['vendor_id'] = Auth::id() ;
            User::where('id', $id)->update($reqData);
            
            return redirect($this->Prefix. '/employee')->withSuccess('Employee has been update successfully');
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
    public function destroyEmp($prefix , $encryptId)
    {
        $id = Helper::decrypt($encryptId);
        $data = User::findOrFail($id);
        try{
            
            User::where('id', $data->id)->delete();
            
            // Event::where('user_id', $data->id)->delete();
            // Session::where('user_id', $data->id)->delete();
            return back()->withSuccess('Employee has been deleted successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }


    private function validatorEmployee(Request $request, $id = null){
        $rules = [
                    'first_name' => 'required',
                   
                    'email' => 'required|email|max:40|regex:/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i',
                    'mobile' => 'required',
                    
                ];
        if($id) {
            $rules = array_merge($rules, [
                'email' => 'required|email|max:40|regex:/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i,'
                //'short_video' => 'nullable|mimes:mp4,3gp|max:100040',

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
                       //'company_business_domain' => 'Please enter company business domain.',
                    ];
        $request->validate($rules,$messages);
    }

    public function customersEnrolled(Request $request){

        if ($request->ajax()) {

            $eventsId = Event::where('user_id' ,  Auth::id() )->pluck('id')->toArray();

            if(!empty($eventsId)){

                $data = EventParticipant::whereIn('event_id' , $eventsId)->get();

                return DataTables::of($data)
                            ->addIndexColumn()       
                            ->make(true);

            }
        }

        return view('vendors.reports.index');

     }

     // public function customersEnrolled(Request $request){

     //    if ($request->ajax()) {
            
     //        $data = EventParticipant::orderBy('id', 'desc')->get();

     //        return DataTables::of($data)
     //                    ->addIndexColumn()       
     //                    ->make(true);

     //    }
     //    return view('superadmin.reports.event-enrollment');

     // }

}
