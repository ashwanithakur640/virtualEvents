<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Category;
use DataTables;
use App\Event;
use App\Session;
use Carbon\Carbon;
use DB;
class EventController extends Controller
{
    /**
     * @method:      index
     * @params:      
     * @createdDate: 21-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view list of events
     */
    public function index(Request $request){
        if ($request->ajax()) {
            if(!empty($request->start_date)  & empty($request->end_date) ){
                $from = date($request->start_date);
                $data = Event::whereDate('start_date_time','>=', $from)->get();
            }
            else if(empty($request->start_date)  & !empty($request->end_date)){
                $to = date($request->end_date);
                $data = Event::whereDate('end_date_time','<=', $to)->get();
            }
            else if(!empty($request->start_date)  & !empty($request->end_date)){
                $from = date($request->start_date);
                $to = date($request->end_date);
                $data = Event::whereDate('start_date_time','>=', $from)->whereDate('end_date_time','<=', $to)->get();
            }
            else{
                if(empty($request->start_date)  & empty($request->end_date)){
                    $data = Event::with('category')->get();
                }
            }
            return DataTables::of($data)
                        ->addIndexColumn()
                        ->addColumn('action', function ($modal) {        
                            if($modal->start_date_time > Carbon::now()){
                                $avtiveEvents = '<a href="'.url('admin/events/edit-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp';
                            } else{
                                $avtiveEvents = '';
                            }
                            $action = $avtiveEvents. '<a href="'.url("admin/events/show-event/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                                <form action="'.url('admin/events/delete-event/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>';
                            return $action;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        return view('admin.events.index');
    }


    /**
     * @method:      todayEvents
     * @params:      
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view today events
     */
    public function todayEvents(Request $request){
        if ($request->ajax()) {
            $today = date('Y-m-d');
            $data = Event::where('start_date_time','<=', $today)->where('end_date_time','>=', $today)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  
                    $action = '<a href="'.url("admin/events/show-event/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                        <form action="'.url('admin/events/delete-event/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.events.todays');
    }

    /**
     * @method:      pastEvents
     * @params:      
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view past events
     */
    public function pastEvents(Request $request){
        if ($request->ajax()) {
            $today = date('Y-m-d');
            $data = Event::where('end_date_time','<', $today)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  
                    $action = '<a href="'.url("admin/events/show-event/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                        <form action="'.url('admin/events/delete-event/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.events.past');
    }

    /**
     * @method:      weeklyEvents
     * @params:      
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view weekly events
     */
    public function weeklyEvents(Request $request){
        if ($request->ajax()) {
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
            $data = Event::where('start_date_time','>=', $weekStartDate)->where('end_date_time','<=', $weekEndDate)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  
                    if($modal->start_date_time > Carbon::now()){
                        $avtiveEvents = '<a href="'.url('admin/events/edit-event/'.Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>&nbsp';
                    } else{
                        $avtiveEvents = '';
                    }
                    $action = $avtiveEvents. '<a href="'.url("admin/events/show-event/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                        <form action="'.url('admin/events/delete-event/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                        <input type="hidden" name="_token" value="'. csrf_token() .'">
                        <input type="hidden" name="_method" value="DELETE">
                    </form>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.events.weekly');
    }

    /**
     * @method:      futureEvents
     * @params:      
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view future events
     */
    public function futureEvents(Request $request){
         if ($request->ajax()) {
            $today = date('Y-m-d');
            $data = Event::whereDate('start_date_time','>', $today)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  
                    $action = '<a href="'.url("admin/events/show-event/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                        <form action="'.url('admin/events/delete-event/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.events.list');
    }

    /**
     * @method:      rescheduledEvents
     * @params:      
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view rescheduled events
     */
    public function rescheduledEvents(Request $request){
        if ($request->ajax()) {
            $data = Event::where('rescheduled','=', Event::RESCHEDULED)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  
                    $action = '<a href="'.url("admin/events/show-event/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                        <form action="'.url('admin/events/delete-event/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.events.rescheduled');
    }

    /**
     * @method:      holdEvents
     * @params:      
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To view hold events
     */
    public function holdEvents(Request $request){
        if ($request->ajax()) {
            $data = Event::where('status','=', 'Onhold')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($modal) {  
                    $action = '<a href="'.url("admin/events/show-event/".Helper::encrypt($modal->id)).'" data-toggle="tooltip" title="View"><i class="fa fa-eye"></i></a>&nbsp<a class="deleteRecord" data-toggle="tooltip" title="Delete" style="color: #4e73df;"><i class="fa fa-trash"></i></a>
                        <form action="'.url('admin/events/delete-event/'.Helper::encrypt($modal->id)).'" method="POST" style="display: none;">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
            }
            return view('admin.events.hold');
    }

    /**
     * @method:      create
     * @params:      
     * @createdDate: 21-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Create event form
     */
    public function create(){
        $categories = Category::get();
    	return view('admin.events.create', compact('categories'));
    }

    /**
     * @method:      validator
     * @params:      Request $request
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Check validator rules
     */
    private function validator(Request $request, $id = null){
        $rules = [
                    /*'name' => 'required|max:30|unique:events,name,NULL,id,deleted_at,NULL',*/
                    'name' => 'required|max:30',
                    'title' => 'required|max:50',
                    'category_id' => 'required',
                    'start_date_time' => 'required',
                    'end_date_time' => 'required',
                    'type' => 'required',
                    'amount' => 'required_if:type,==,Paid',
                    'description' => 'required',
                ];
        $messages = [
                        'name.required' => 'Please enter event name.',
                        /*'name.unique' => 'Event name already has been taken.',*/
                        'name.max' => 'Event name may not be greater than 30 characters.',
                        'title.required' => 'Please enter event title.',
                        'category_id.required' => 'Please select category.',
                        'start_date_time.required' => 'Please select event start date & time.',
                        'end_date_time.required' => 'Please select event end date & time.',
                        'type.required' => 'Please select a type.',
                        'amount.required_if' => 'Please enter an amount.',
                        'description.required' => 'Please enter event description.',
                    ];
        $request->validate($rules,$messages);
    }

    /**
     * @method:      store
     * @params:      Request $request
     * @createdDate: 21-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     Add event
     */
    public function store(Request $request){
        $this->validator($request);
        try{
        	$reqData = $request->all();
            $reqData['user_id'] = Auth::user()->id;
        	Event::create($reqData);
        	return redirect('/admin/events/')->withSuccess('Event has been created successfully.');
    	} catch(\Exception $e) {
        	return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      edit
     * @params:      $encryptId
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To edit event
     */
    public function edit($encryptId){
        $id = Helper::decrypt($encryptId);
        $data = Event::findOrFail($id);
        $categories = Category::get();
        try{
            return view('admin.events.edit', compact('data', 'categories'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      update
     * @params:      Request $request, $encryptId
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To update event
     */
    public function update(Request $request, $encryptId){
        $id = Helper::decrypt($encryptId);
        $this->validator($request, $id);
        try{
            $reqData = $request->except(['_token', '_method']);
            $data = Event::findOrFail($id);
            $comments = $data->reschedule_comments;
            if($data->start_date_time  != $request->start_date_time){
                $comments .= '<br>Start Time has been changed from '.$data->start_date_time.' to '.$request->start_date_time;
                $reqData['rescheduled'] = 1;
            }
            if($data->end_date_time  != $request->end_date_time){
                $comments .= '<br>Start Time has been changed from '.$data->end_date_time.' to '.$request->end_date_time;
                $reqData['rescheduled'] = 1;
            }
            $reqData['reschedule_comments'] = $comments;
            Event::where('id', $id)->update($reqData);
            return redirect('admin/events/')->withSuccess('Event has been update successfully');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      destroy
     * @params:      $encryptId
     * @createdDate: 22-09-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To soft delete event
     */
    public function destroy($encryptId){
        $id = Helper::decrypt($encryptId);
        $data = Event::findOrFail($id);
        try{
            Event::where('id', $data->id)->delete();
            Session::where('event_id', $data->id)->delete();
            return back()->withSuccess('Event has been deleted successfully.');
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      eventShow
     * @params:      $encryptId
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To show event detail
     */
    public function eventShow($encryptId){
        $id = Helper::decrypt($encryptId);
        $data = Event::findOrFail($id);
        try{
            return view('admin.events.view', compact('data'));
        } catch(\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    /**
     * @method:      changeState
     * @params:      $id
     * @createdDate: 26-10-2020 (dd-mm-yyyy)
     * @developer:   
     * @purpose:     To change event status
     */
    public function changeState($id , $status){
        $event =  Event::where('id' ,$id)->first();
        if(!empty($event)){
            $event->update([ 'status' => $status]);
            return back()->withSuccess('Event status has been updated successfully.');
        }
    }

}
