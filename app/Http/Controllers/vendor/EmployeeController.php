<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Carbon\Carbon;
use App\Employee;
use App\User;
use DataTables;
use App\Event;
use Illuminate\Support\Str;
use Mail;
use App\Mail\presenterEmail;


class EmployeeController extends Controller
{
    /**
     * @method:      index
     * @params:      
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     View session list
     */
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Employee::where('vendor_id', Auth::user()->id)->get();
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {  
                            
      
                    $avtiveSession =  '<a href="'.url($this->Prefix . '/confe/'.$modal->u_id).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp';
   
                    $avtiveSession .= '<a href="'.url($this->Prefix . '/session/edit-session/'.Helper::encrypt($modal->id)). '" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp';
  

                    $action = $avtiveSession. '<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="'.url($this->Prefix . '/employee/delete-employee/'.Helper::encrypt($modal->id)).'-'.strtotime("$modal->date $modal->start_time") .'" method="POST" style="display: none;">  
                                <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                             return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('vendor.employee.index');
    }

    /**
     * @method:      create
     * @params:      
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Create session form
     */
    public function create(){

        $phoneCode = config('teliphone.code');

        return view('vendor.employee.create')->with('phoneCode', $phoneCode);

    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validator(Request $request, $id = null){
        $rules = [
                    'name' => 'required|max:30',
                    // 'event_id' => 'required|unique:session,event_id,NULL,id,user_id,'.Auth::user()->id,
                    'email' => 'required',
                    'phone' => 'required',
                    //'country_code' => 'required'
                ];
        
        $messages = [
            'name.required' => 'Required',
            'email.required' => 'Required',
            'phone.required' => 'Required',
            //'country_code.required' => 'Required'
        ];
                    
        $request->validate($rules,$messages);
    }

    /**
     * @method:      store
     * @params:      Request $request
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Add session
     */
    public function store(Request $request){

        $this->validator($request);

        //try{

            $input = $request->all();
        
            //Add phone code in phone no
           
        $employeeData = array();
        $input['phone_code'] = '+91';
        //if($input['phone']){
            $employeeData['phone'] = '+'.$input['phone_code'].'-'.$input['phone'];
        //}   


        $employeeData['email'] = $input['email'];
        $employeeData['name'] = $input['name'];
        $employeeData['vendor_id'] = \Auth::id();
        // create user also for this person 

        $employee = Employee::create($employeeData);


        // $userData['email'] = $input['email']; 
        // $userData['name'] = $input['name'] ;
        // $userData['email_verified_at'] = \Carbon\Carbon::now();
        
        // $password = rand(99999999,10000000);
        // $userData['password'] = Hash::make($password);

        // $userData['vendor_id'] = \Auth::id();

        // $userData['role'] = USER::ROLE_STAFF;

        // $user = User::create($userData);

        // $updated['user_id'] = $user->id;
        // $businessCard = $this->businessCardRepository->update($updated , $businessCard->id ); 

        // if($user){

            

        //     //send welcome mail
        //     $userData['password'] = $password;
        //     $userData['email'] = $user->email;
        //     $userData['name'] = $user->name;

        //     $generated = array();

        //     $detail['email'] = $userData['email'];

        //     $email = $userData['email'];
        //     $password = $userData['password'];

        //     $fContent = EmailContent::where('type_id', EmailContent::WELCOME_EMAIL_TEMPLATE)->first();

        //     $generated['title'] = htmlspecialchars($fContent->title);
        //     $generated['title'] = str_replace('$email',$email,$generated['title']); 
        //     $generated['title'] = str_replace('$password',$password,$generated['title']); 
        //     $generated['title'] = htmlspecialchars_decode($generated['title']);
        //     $generated['title'] = strip_tags($generated['title']);

        //     $generated['body'] = htmlspecialchars($fContent->email_body);
        //     $generated['body'] = str_replace('$email',$email,$generated['body']); 
        //     $generated['body'] = str_replace('$password',$password,$generated['body']); 
        //     $generated['body'] = htmlspecialchars_decode($generated['body']);
        //     $generated['body'] = strip_tags($generated['body']);

        //     Mail::to($userData['email'])->send(new WelcomeMail($generated));

            
        //     //end mail

        //     $details['user_id'] = $user->id;

       
        // }

   

        // // end creating a user for this vendor 

        // Flash::success('');
           

            return redirect($this->Prefix . '/employee/')->withSuccess('Employee has been created successfully.');
        // } catch(\Exception $e) {
        //     return back()->withError($e->getMessage());
        // }
    }

    /**
     * @method:      edit
     * @params:      $encryptId
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To edit session
     */
    public function edit($prefix, $encryptId){
        $id = Helper::decrypt($encryptId);
        $data = Session::findOrFail($id);
        try{
            $events = Event::where([['status', 'Active'], ['start_date_time', '>', Carbon::now()]])->whereIn('user_id', [1, Auth::user()->id])->latest()->get();

            $user = User::where('status' , 'Active')->get();
            $usersIdArr = json_decode($data->speakers);

            return view('vendor.session.edit', compact('data', 'events' , 'user' , 'usersIdArr' ));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      update
     * @params:      Request $request, $encryptId
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To update session
     */
    public function update(Request $request, $prefix, $encryptId){
        $id = Helper::decrypt($encryptId);
        $this->validator($request, $id);
        try{
            $reqData = $request->except(['_token', '_method']);
            /* Start upload session image */
            if(isset($reqData['image']) && !empty($reqData['image'])){
                $profilePicName = time() . '_' . $reqData['image']->getClientOriginalName();
                $path = public_path('/assets/images/session');
                if(!is_dir($path)){
                    mkdir($path, 0777, true);
                }
                Helper::uploadImage($reqData['image'], $path, $profilePicName);
                $reqData['image'] = $profilePicName;
            }
            /* End upload session image */
            $reqData['user_id'] = Auth::user()->id;
            Session::where('id', $id)->update($reqData);
            return redirect($this->Prefix . '/session/')->withSuccess('Session has been update successfully');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      destroy
     * @params:      $encryptId
     * @createdDate: 24-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To soft delete session
     */
    public function destroy($prefix, $encryptId){
       //  dd($encryptId);
        $id = Helper::decrypt($encryptId);


        $data = Employee::findOrFail($id);
        try{
            Employee::where('id', $data->id)->delete();
            return back()->withSuccess('Session has been deleted successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

}
