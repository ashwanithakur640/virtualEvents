<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conference;
use App\conferenceInvite;
use Image;
use App\Mail\conferenceInvite as inviteMail;
use App\Mail\cancelConference as cancelInvite;
use App\Mail\confupdate as updateInvite;
use App\Mail\publicConference;
use Mail;
use Storage;
use Auth;
use Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use App\conferenceCategory;
use App\Jobs\SendConferenceEmail;
use App\conferenceJoinee;
use App\requestAccess;
use Illuminate\Support\Facades\URL;
use App\Mail\acceptAccess;
use App\User;
use App\Mail\accessDeclined;
use App\Mail\translatorInvitation;
use App\Jobs\sendUpdateInvite;
use App\translatorBooking;
use App\translator;
use App\userSubscription;
use App\Mail\translatorUpdate;
use App\Mail\presenterEmail;
use App\groupUser;
class ConferenceController extends Controller
{
    //
	
	function conference(Request $request)
	{
	
	 if(Auth::user()->role !='2')
	 {
		 Session::flash('message', 'You are not authorize to do this!'); 
            Session::flash('alert-class', 'alert-danger');
			  return redirect('my-profile'); 
	 }
		
		if (Auth::user()->can('create', Conference::class)) {
		return view('user.create-conference');
		}
		else{
			$permium = ['2','5'];
			if(in_array(Auth::user()->membership,$permium))
			{
			if(env('PAYMENTS_INACTIVE'))
		   {
			Session::flash('message', 'Your monthly limit reached to create conferences.'); 
            Session::flash('alert-class', 'alert-danger');
               return redirect('my-conferences');	     
		   }
		   else{
			Session::flash('message', 'Your monthly limit reached to create conferences.'); 
            Session::flash('alert-class', 'alert-danger');
               return redirect('my-subscription');				
		   }
			}
			else{

			if(env('PAYMENTS_INACTIVE'))
		   {
			 Session::flash('message', 'Your monthly limit reached to create conferences.'); 
            Session::flash('alert-class', 'alert-danger');
             return redirect('my-conferences');	    
			   
		   }
		   else{
		    Session::flash('message', 'Your monthly limit is reached'); 
            Session::flash('alert-class', 'alert-danger');
			  return redirect('my-subscription');	
		   }			
			}
	    return redirect('my-profile');		
		}
	}

	function createConference(Request $request)
	{
		
		if (Auth::user()->can('create', Conference::class)) {
		$request->validate([
        'title' => 'required|max:255|regex:/^[a-zA-Z0-9_ ]*$/',
        'category' => 'required',
		'date' => 'required|date_format:Y-m-d',
		'time' => 'required|date_format:H:i',
		'description_conference' => 'required|string',
		'type' => 'required|integer',
		'author' => 'required|string|regex:/^[a-zA-Z0-9_ ]*$/',
		'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
		'invities' => (!empty($request->type) && $request->type !='1' && empty($request->group_id))?'required':'',
		'group_id' => (!empty($request->type) && $request->type !='1' && empty($request->invities))?'required':'',
		'translator' => (!empty($request->translation_services) && $request->translation_services ='1')?'required':'',
		'from_translator' => (!empty($request->translation_services) && $request->translation_services ='1')?'required':''
		//'invities_message' => (!empty($request->type) && $request->type =='3')?'required|regex:/^[a-zA-Z0-9_ ]*$/':''
    ],['author.required' => 'Please enter the author or organizer name','description_conference.required'=>'Please add some description','invities.required' =>'Please add some Invitees or select group','translator.required'=> 'Please select desired translations','group_id.required'=> 'Please either add invitee emails or select group that you want to invite']);
	
	if (strpos($request->description_conference,'<script>') != false) {
		 Session::flash('message', 'you can use script tag in description'); 
         Session::flash('alert-class', 'alert-danger'); 
		 return redirect()->back()->withInput();
	}
	$date_time = $this->convertTimeZone($request->date.' '.$request->time.":00",$request->tz);
	if($date_time['date'] < date('Y-m-d') || ($date_time['date']==date('Y-m-d') && $date_time['time'] < date('H:i')))
	{
	Session::flash('message', "You can't create conference of past date and time"); 
    Session::flash('alert-class', 'alert-danger'); 
	return redirect()->back()->withInput();	
	}
	if($this->checkConference(Auth::id(),$date_time['date'],$date_time['time']))
	{
	Session::flash('message', "You already created the conference of same date and time"); 
    Session::flash('alert-class', 'alert-danger'); 
	return redirect()->back()->withInput();		
	}
	if(!empty($request->speakers) && count($request->speakers) > 4)
	{
	Session::flash('message', "Maximum 4 speakers are allowed"); 
    Session::flash('alert-class', 'alert-danger'); 
	return redirect()->back()->withInput();		
	}
	$fileName = '';
	 if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream(); // <-- Key point

            //dd();
            Storage::disk('public')->put('conference-images'.'/'.$fileName, $img, 'public');
            }
		//print_r($request->all());	
		$from_translation = [];
		$to_translation = [];
		if(!empty($request->organizer_language) && !empty($request->translator_organizer))
		{
			if($request->organizer_language != $request->translator_organizer){
			$from_translation[] = array(Auth::id() => $request->organizer_language);
			$to_translation[] = array(Auth::id() => $request->translator_organizer);	
			}
		}
	if(!empty($request->speakers) && !empty($request->from_translator) && !empty($request->translator))
	{
		foreach($request->speakers as $index => $speaker)
		{
		// echo $index+1;
		$location = 'presenter_'.($index+1);
		if(!empty($request->from_translator[$location]) &&  !empty($request->translator[$location]))
		if($request->from_translator[$location] != $request->translator[$location])
		{
		 $from_translation[] = array($speaker => $request->from_translator[$location]);
		 $to_translation[] = array($speaker => $request->translator[$location]);
		}
		}
	}
	
	$conference = new Conference();
	$conference->title =  $request->title;
	$conference->description =  $this->scriptStripper($request->description_conference);
	$conference->conf_date =  $date_time['date'];
	$conference->category = json_encode(array_filter($request->category));
	$conference->time =  $date_time['time'];
	$conference->u_id = Str::uuid()."-".time();
	$conference->type =  $request->type;
	$conference->author =  (!empty($request->author))?$request->author:Auth::user()->name;
	$conference->user_id =  Auth::id();
	$conference->speakers = (!empty($request->speakers))?json_encode($request->speakers):'';
	$conference->image =  $fileName;
	$conference->admin_approval = ($request->type=='1' || $request->type=='2')?'0':'1';
	$conference->admin_decline = ($request->type=='1' || $request->type=='2')?'1':'2';
	$conference->invited_groups = (!empty($request->group_id))?json_encode($request->group_id):null;
	if(!empty($request->translation_services))
	{
	$conference->translator_required = '1';	
	$conference->translation_lang = json_encode($from_translation);
    $conference->from_translation_lang = json_encode($to_translation);	
	}
	$conference->save();
	

	if($conference->admin_approval=='0')
	{
	Mail::to('sukhmandeep.singh@itechnolabs.tech')->send(new publicConference($conference));	
	//$this->dispatch(new SendConferenceEmail($conference,$conference->category));
	}
	
	/* Assign translator for private conference */
	if($conference->admin_approval == '1')
	{
     $userSubscription = userSubscription::where('status','1')->where('user_id',Auth::id())->first();
				if(!empty($userSubscription))
				{
				if($userSubscription->paln_id=='3')
				{
				 $minutes = \Config::get('constants.conference_time');
				}
			   elseif($userSubscription->paln_id=='4')
				{
				 $minutes = \Config::get('constants.conference_time');
				}
			  else{
			   $minutes = \Config::get('constants.conference_time');
				 }		
	$selectedTime = $date_time['time'].":00";
	$endTime = strtotime("+".$minutes." minutes", strtotime($selectedTime));
	if($conference->translator_required)
	{
	//$this->assignTranslator($date_time['time'].":00",date('H:i:s', $endTime),$date_time['date'],$conference,json_decode($conference->translation_lang),json_decode($conference->from_translation_lang,true));
	}
				}
	}
	/* End assign conference for private conference */
	
	if(($conference->type !='1' && !empty($request->invities)) || ($conference->type !='1' && !empty($request->group_id)) )
	{
		$group = [];
		if(!empty($request->invities))
		{
			$group = explode("," ,strtolower($request->invities));	
		}
		if(!empty($request->group_id))
		{
			foreach($request->group_id as $group_id)
			{
				$users_group = groupUser::where('group_id',$group_id)->where('status','1')->get();

				foreach($users_group as $user_group)
				{
					array_push($group,$user_group->email);
				}
			}
		}
		
		$invite = new conferenceInvite();
		$invite->conference_id = $conference->id;
		$invite->invities = json_encode($group);
		$invite->link = url('conference')."/".$conference->u_id;
		$invite->invitation_message = $request->invities_message;
		$invite->save();
		
		if($invite->save() && !empty(json_decode($invite->invities)))
		{
			/* Setting passwords */
			$pass = [];
			foreach (json_decode($invite->invities) as $password) {
				$pass[] = Str::random(6);
			}
			conferenceInvite::where('id',$invite->id)->update(['user_password' => json_encode($pass)]);
			foreach (json_decode($invite->invities) as $index => $recipient) {
				$invities = conferenceInvite::where('id',$invite->id)->first();
				$passwords = json_decode($invities->user_password);
				if($conference->admin_approval !='0'){
                 Mail::to($recipient)->send(new inviteMail($invite->invitation_message, $invite->link,$conference,$passwords[$index]));
				 
				 if(!empty($conference->speakers))
				{
				$users = json_decode($conference->speakers,true);	
				foreach($users as $user)
				{
				$presenter_user = User::find($user);	
				 Mail::to($presenter_user->email)->send(new presenterEmail('Invite as presenter',$conference)); 
				}
				}
				}
             }
		}
	}
	Session::flash('message', 'Conference added successfuly!'); 
      Session::flash('alert-class', 'alert-success'); 
	  return redirect('conference-detail/'.$conference->u_id);
	  
		}
		else{
			if(env('PAYMENTS_INACTIVE'))
		   {
			Session::flash('message', 'Your monthly limit reached'); 
      Session::flash('alert-class', 'alert-danger'); 
	  return redirect('my-profile');	   
		   }
		   else{
	  Session::flash('message', 'You are not authorize to do this please upgrade your plan!'); 
      Session::flash('alert-class', 'alert-danger'); 
	  return redirect('my-subscription');	
		   }
			
		}
	}
	
	function myConferences(Request $request)
	{
		if($request->get('sort')){
		$sort = $request->get('sort');
		}
		else{
			
		$sort = $request->sort;	
		}
		if($request->get('search')){
		$search = $request->search;	
		}
		else{
			$search = $request->text;
		}
		
		$conferences = Conference::leftJoin('conference_invites','conferences.id','=','conference_invites.conference_id')->where('conferences.user_id',Auth::id())->when(!empty($search),function($query) use ($search){
		     return $query->where('conferences.title','LIKE','%'.$search.'%');	
		     })
		->when(!empty($sort),function($query) use ($sort){
			if($sort=='title')
			{
				return $query->orderBy('conferences.title','ASC');
			}
	        if($sort=='date' || $sort=='all')
			{
				return $query->orderBy('conferences.conf_date','DESC');
			}
		})->when(empty($sort),function($query){
		     return $query->orderBy('conferences.conf_date','DESC');	
		     })->select('conferences.*','conference_invites.invities as invities')->orderBY('conferences.time','ASC')->paginate(10);
	        if($request->ajax())
			{
			$view = view("common.my-conference-search",compact('conferences'))->render();
            return response()->json(['success'=>true,'html'=>$view]);	
			}
			else{
		return view('user.my-conferences',compact('conferences'));
			}
	}
	
	function searchConference(Request $request)
	{
		$search = $request->text;
		$conferences = Conference::leftJoin('conference_invites','conferences.id','=','conference_invites.conference_id')->where('conferences.user_id',Auth::id())->when(!empty($search),function($query) use ($search){
		     return $query->where('conferences.title','LIKE','%'.$search.'%');	
		     })->orderBY('conferences.conf_date','ASC')->orderBY('conferences.time','ASC')->paginate(10);
			 
			  $view = view("common.my-conference-search",compact('conferences'))->render();
        return response()->json(['success'=>true,'html'=>$view]);
		
	}
	
	function editConference(Request $request)
	{
	$conference_edit = Conference::where('u_id',$request->id)->first();	
	if (Auth::user()->can('update', $conference_edit)) {	
	 $conference = Conference::leftJoin('conference_invites','conferences.id','=','conference_invites.conference_id')->where('conferences.u_id',$request->id)->first();
	 return view('user.edit-conference',compact('conference'));
	}
	else{
	  Session::flash('message', 'You are not authorize to do this!'); 
      Session::flash('alert-class', 'alert-danger'); 
	  return redirect('my-conferences');		
	}
	}
	
	function updateConference(Request $request)
	{
		$conference_edit = Conference::where('u_id',$request->id)->first();	
	if (Auth::user()->can('update', $conference_edit)) {
		if($conference_edit->type=='3'){
		$request->validate([
        'title' => 'required|max:255|regex:/^[a-zA-Z0-9_ ]*$/',
        'category' => 'required',
		'date' => 'required|date_format:Y-m-d',
		'time' => 'required|date_format:H:i',
		'description_conference' => 'required|string',
		'author' => 'required|string|regex:/^[a-zA-Z0-9_ ]*$/',
		'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
		
    ],['author.required' => 'Please enter the author or organizer name','description_conference.required'=>'Please add some description']);	
		}
		else{
		$request->validate([
		'date' => 'required|date_format:Y-m-d',
		'time' => 'required|date_format:H:i'
		
    ]);
		}
	$date_time = $this->convertTimeZone($request->date.' '.$request->time.":00",$request->tz);
	if($date_time['date'] < date('Y-m-d') || ($date_time['date']==date('Y-m-d') && $date_time['time'] < date('H:i')))
	{
	Session::flash('message', "You can't create conference of past date and time"); 
    Session::flash('alert-class', 'alert-danger'); 
	return redirect()->back()->withInput();	
	}
	if (strpos($request->description_conference,'<script>') != false) {
		 Session::flash('message', 'you can use script tag in description'); 
         Session::flash('alert-class', 'alert-danger'); 
		 return redirect()->back()->withInput();
	}
		$fileName = $request->old_image;
	       if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());

            $img->stream(); // <-- Key point

            //dd();
            Storage::disk('public')->put('conference-images'.'/'.$fileName, $img, 'public');
            }
			if($conference_edit->type=='3')
			{
				$conf_update = Conference::find($conference_edit->id);
			
	           $conf_update->title =  $request->title;
	              $conf_update->description =  $this->scriptStripper($request->description_conference);
	              $conf_update->conf_date =  $date_time['date'];
	              $conf_update->category = json_encode(array_filter($request->category));
	              $conf_update->time =  $date_time['time'];
				  $conf_update->author =  $request->author;
				  $conf_update->image =  $fileName;
				    $conference = $conf_update->save();
			}
			else{
			$conference = Conference::where('u_id', $request->update_id)->update(['conf_date' => $date_time['date'],'time' => $date_time['time']]);
			}
			if($conference)
			{
				$conference_update = Conference::where('u_id',$request->id)->first();	
				$invite = conferenceInvite::where('conference_id',$conference_update->id)->first();
			if(!empty($invite))
			{
			foreach (json_decode($invite->invities) as $recipient) {
            Mail::to($recipient)->send(new updateInvite($conference_update));
             }
			}
			if($conference_update->type=='1')
			{
			$this->dispatch(new sendUpdateInvite($conference_update,$conference_update->category,'Conference has been updated','conference updated'));	
			}
			
			/* Assign new translators */
			if($conference_edit->conf_date != $date_time['date'] || $conference_edit->time != $date_time['time'])
			{
				
			$userSubscription = userSubscription::where('status','1')->where('user_id',Auth::id())->first();
				if(!empty($userSubscription))
				{
				if($userSubscription->paln_id=='3')
				{
				 $minutes = 45;
				}
			   elseif($userSubscription->paln_id=='4')
				{
				 $minutes = 60;
				}
			  else{
			   $minutes = 60;
				 }
                  /* get old translators and send cancelled email */
                  $old_translator = translatorBooking::where('conf_id',$conference_update->id)->where('status','1')->get();
				  if(!empty($old_translator))
				  {
				  $this->sendTranslatorUpdate($old_translator,'Conference reschedule and this conference is no longer active for translation','Conference update');	  
				  translatorBooking::where('conf_id',$conference_update->id)->where('status','1')->update(['status'=>'0']);
				  }
                 /* End get old translators */			   
				$selectedTime = $date_time['time'].":00";
				$endTime = strtotime("+".$minutes." minutes", strtotime($selectedTime));
				if($conference_update->translator_required)
				{
				//$this->assignTranslator($date_time['time'].":00",date('H:i:s', $endTime),$date_time['date'],$conference_update,json_decode($conference_update->translation_lang),json_decode($conference_update->from_translation_lang,true));
				}
				}	
			}
			
			
			
	        Session::flash('message', 'Conference updated successfully!'); 
            Session::flash('alert-class', 'alert-success'); 
			}
			else{
				 Session::flash('message', 'something went wrong!'); 
           Session::flash('alert-class', 'alert-danger'); 
				
			}
			return redirect('my-conferences');
	}
	else{
	  Session::flash('message', 'You are not authorize to do this!'); 
      Session::flash('alert-class', 'alert-danger'); 
	  return redirect('my-conferences');	
	}
	}
	
	function deleteConference(Request $request)
	{
		$conference_edit = Conference::where('u_id',$request->id)->first();	
	if (Auth::user()->can('delete', $conference_edit)) {
		$conference = Conference::where('u_id',$request->id)->update(['status'=>'0']);
		if($conference)
		{
			$conference_update = Conference::where('id',$conference_edit->id)->first();	
			$invite = conferenceInvite::where('conference_id',$conference_edit->id)->first();
			if(!empty($invite))
			{
			foreach (json_decode($invite->invities) as $recipient) {
            Mail::to($recipient)->send(new cancelInvite($conference_update));
             }
			}
			if($conference_update->type=='1')
			{
			$this->dispatch(new sendUpdateInvite($conference_update,$conference_update->category,'Conference has been cancelled','conference cancelled'));	
			}
			
			  $old_translator = translatorBooking::where('conf_id',$conference_update->id)->where('status','1')->get();
				  if(!empty($old_translator))
				  {
				  $this->sendTranslatorUpdate($old_translator,'Conference Cancelled','Conference Cancelled');	  
				  translatorBooking::where('conf_id',$conference_update->id)->where('status','1')->update(['status'=>'0']);
				  }
			Session::flash('message', 'Conference is canceled!'); 
           Session::flash('alert-class', 'alert-success'); 
	      return redirect('my-conferences');	
		}
	}
	else{
	 Session::flash('message', 'You are not authorize to do this!'); 
      Session::flash('alert-class', 'alert-danger'); 
	  return redirect('my-conferences');		
		
	}
		
	}
	
	function conferenceDetail(Request $request)
	{
		$conference = Conference::where('u_id',$request->id)->first();
		if (Auth::user()->can('update', $conference)) {
		$invite =  conferenceInvite::where('conference_id',$conference->id)->first();
		
		$invities = 0;
	  if(!empty($invite))
	  {
		 $invities = count(json_decode($invite->invities));
	  }
		 return view('user.conference-detail',compact('conference','invities'));
	}
	else{
	 Session::flash('message', 'You are not authorize to do this!'); 
      Session::flash('alert-class', 'alert-danger'); 
	  return redirect('my-profile');		
		
	}
	}
	
	/* get conferences show on home page */
	function homeConference(Request $request)
	{
		
		
		$search =  $request->text;
		$category = $request->get('category');
		$sort = $request->get('sort');
		//DB::enableQueryLog();
		$ongoing = Conference::leftJoin('conference_invites','conferences.id','=','conference_invites.conference_id')->where('conferences.type','1')->where('conferences.conf_date',Carbon::now()->format('Y-m-d'))->where('conferences.conf_status','1')->where('admin_approval','1')->when(!empty($search),function($query) use ($search){
		     return $query->where('conferences.author','LIKE','%'.$search.'%');	
		     })
			 ->when((!empty($category)&& $category!='all' ),function($query) use ($category){
		     return $query->where('conferences.category',$category);	
		     })->when(!empty($sort),function($query) use ($sort){
			if($sort=='title')
			{
				return $query->orderBy('conferences.title','ASC');
			}
	        if($sort=='date' || $sort=='all')
			{
				return $query->orderBy('conferences.conf_date','DESC');
			}
		})->where('conferences.status','1')->orderBy('conferences.time','ASC')->paginate(3);
		//dd(DB::getQueryLog());
		$upcoming = Conference::leftJoin('conference_invites','conferences.id','=','conference_invites.conference_id')->whereIn('conferences.type',['1','2'])->where('conferences.conf_date','>=',Carbon::now()->format('Y-m-d'))->where('conferences.conf_status','0')->where('conferences.admin_approval','1')->where('conferences.admin_decline','2')->when(!empty($search),function($query) use ($search){
		     return $query->where('conferences.author','LIKE','%'.$search.'%');	
		     })->when((!empty($category)&& $category!='all' ),function($query) use ($category){
		     return $query->where('conferences.category',$category);	
		     })->when(!empty($sort),function($query) use ($sort){
			if($sort=='title')
			{
				return $query->orderBy('conferences.title','ASC');
			}
	        if($sort=='date' || $sort=='all')
			{
				return $query->orderBy('conferences.conf_date','DESC');
			}
		})->where('conferences.status','1')
		->when(empty($sort),function($query) use ($sort){
			return $query->orderBy('conferences.conf_date','ASC');
		})->paginate(3);
		
		$old = Conference::leftJoin('conference_invites','conferences.id','=','conference_invites.conference_id')->where('conferences.type','1')->where('conferences.conf_date','<=',Carbon::now()->format('Y-m-d'))->Where('conferences.conf_status','2')->where('admin_approval','1')->when(!empty($search),function($query) use ($search){
		     return $query->where('conferences.author','LIKE','%'.$search.'%');	
		     })->when((!empty($category)&& $category!='all' ),function($query) use ($category){
		     return $query->where('conferences.category',$category);	
		     })->when(!empty($sort),function($query) use ($sort){
			if($sort=='title')
			{
				return $query->orderBy('conferences.title','ASC');
			}
	        if($sort=='date' || $sort=='all')
			{
				return $query->orderBy('conferences.conf_date','DESC');
			}
		})->where('conferences.status','1')->when(empty($sort),function($query) use ($sort){
			return $query->orderBy('conferences.conf_date','DESC');
		})->orderBy('conferences.time','DESC')->select('conferences.*','conference_invites.invities as invities')->paginate(3);
		
		$trending = Conference::leftJoin('conference_invites','conferences.id','=','conference_invites.conference_id')->whereIn('conferences.type',['1','2'])->where('conferences.conf_date','>=',Carbon::now()->format('Y-m-d'))->where('conferences.admin_approval','1')->where('conferences.admin_decline','2')->when(!empty($search),function($query) use ($search){
		     return $query->where('conferences.author','LIKE','%'.$search.'%');	
		     })->when((!empty($category)&& $category!='all' ),function($query) use ($category){
		     return $query->where('conferences.category',$category);	
		     })->when(!empty($sort),function($query) use ($sort){
			if($sort=='title')
			{
				return $query->orderBy('conferences.title','ASC');
			}
	        if($sort=='date' || $sort=='all')
			{
				return $query->orderBy('conferences.conf_date','DESC');
			}
		})->where('conferences.conf_status','0')->orderBy('conferences.views','DESC')->where('conferences.status','1')->paginate(5);
		
		if($request->ajax())
		{
		$view = view("common.conference",compact('ongoing','upcoming','old','trending'))->render();
        return response()->json(['success'=>true,'html'=>$view]);	
			
		}
		return view('home-login',compact('ongoing','upcoming','old','trending'));
	}
	
	/* function on going conferences */
	
	function ongoingConference(Request $request)
	{
		
	    $search =  $request->text;
		$category = $request->get('category');
		$sort = $request->get('sort');
		$conferences = Conference::leftJoin('conference_invites','conferences.id','=','conference_invites.conference_id')->where('conferences.type','1')->where('conferences.conf_date',Carbon::now()->format('Y-m-d'))->where('conferences.conf_status','1')->where('admin_approval','1')->when(!empty($search),function($query) use ($search){
		     return $query->where('conferences.author','LIKE','%'.$search.'%');	
		     })
			 ->when((!empty($category)&& $category!='all' ),function($query) use ($category){
		     return $query->where('conferences.category',$category);	
		     })->when(!empty($sort),function($query) use ($sort){
			if($sort=='title')
			{
				return $query->orderBy('conferences.title','ASC');
			}
	        if($sort=='date' || $sort=='all')
			{
				return $query->orderBy('conferences.conf_date','DESC');
			}
		})->where('conferences.status','1')->paginate(10);
		
		$title = '<h2> <span class="color-text font-weight-bold">Ongoing</span> Conferences</h2>';
		$join = true;
		$rating = false;
		if($request->ajax())
		{
		$view = view("common.conference-type",compact('conferences','title','join','rating'))->render();
        return response()->json(['success'=>true,'html'=>$view]);		
		}
		return view('conference-type',compact('conferences','title','join','rating'));
		
	}
	
	/* Upcoming conference */
	function upcomingConference(Request $request)
	{
		
		$search =  $request->text;
		$category = $request->get('category');
		$sort = $request->get('sort');
		$conferences = Conference::leftJoin('conference_invites','conferences.id','=','conference_invites.conference_id')->whereIn('conferences.type',['1','2'])->where('conferences.conf_date','>=',Carbon::now()->format('Y-m-d'))->where('conferences.conf_status','0')->where('conferences.admin_approval','1')->where('conferences.admin_decline','2')->when(!empty($search),function($query) use ($search){
		     return $query->where('conferences.author','LIKE','%'.$search.'%');	
		     })->when((!empty($category)&& $category!='all' ),function($query) use ($category){
		     return $query->where('conferences.category',$category);	
		     })->when(!empty($sort),function($query) use ($sort){
			if($sort=='title')
			{
				return $query->orderBy('conferences.title','ASC');
			}
	        if($sort=='date' || $sort=='all')
			{
				return $query->orderBy('conferences.conf_date','DESC');
			}
		})->when(empty($sort),function($query) use ($sort){
			return $query->orderBy('conferences.conf_date','ASC');
		})->where('conferences.status','1')->paginate(10);
		$title = '<h2> <span class="color-text font-weight-bold">Upcoming</span> Conferences</h2>';
		$join = false;
		$rating = false;
		if($request->ajax())
		{
		$view = view("common.conference-type",compact('conferences','title','join','rating'))->render();
        return response()->json(['success'=>true,'html'=>$view]);		
		}
		return view('conference-type',compact('conferences','title','rating','join'));
	}
	
	/* previous conference */
	function previousConference(Request $request)
	{
		
		$search =  $request->text;
		$category = $request->get('category');
		$sort = $request->get('sort');
		$conferences = Conference::leftJoin('conference_invites','conferences.id','=','conference_invites.conference_id')->where('conferences.type','1')->where('conferences.conf_date','<=',Carbon::now()->format('Y-m-d'))->Where('conferences.conf_status','2')->where('admin_approval','1')->when(!empty($search),function($query) use ($search){
		     return $query->where('conferences.author','LIKE','%'.$search.'%');	
		     })->when((!empty($category)&& $category!='all' ),function($query) use ($category){
		     return $query->where('conferences.category',$category);	
		     })->when(!empty($sort),function($query) use ($sort){
			if($sort=='title')
			{
				return $query->orderBy('conferences.title','ASC');
			}
	        if($sort=='date' || $sort=='all')
			{
				return $query->orderBy('conferences.conf_date','DESC');
			}
		})->where('conferences.status','1')->when(empty($sort),function($query) use ($sort){
			return $query->orderBy('conferences.conf_date','DESC');
		})->orderBy('conferences.time','DESC')->select('conferences.*','conference_invites.invities as invities')->paginate(10);
		
		
		
		$title = '<h2> <span class="color-text font-weight-bold">Previous</span> Conferences</h2>';
		$join = false;
		$rating = true;
		$completed =  true;
		if($request->ajax())
		{
		$view = view("common.conference-type",compact('conferences','title','join','rating','completed'))->render();
        return response()->json(['success'=>true,'html'=>$view]);		
		}
		return view('conference-type',compact('conferences','title','join','rating','completed'));
		
	}
	
	
	/* function on going conferences */
	
	function trendingConference(Request $request)
	{
		
	    $search =  $request->text;
		$category = $request->get('category');
		$sort = $request->get('sort');
		$conferences = Conference::leftJoin('conference_invites','conferences.id','=','conference_invites.conference_id')->whereIn('conferences.type',['1','2'])->where('conferences.conf_date','>=',Carbon::now()->format('Y-m-d'))->where('conferences.conf_status','0')->where('conferences.admin_approval','1')->where('conferences.admin_decline','2')->when(!empty($search),function($query) use ($search){
		     return $query->where('conferences.author','LIKE','%'.$search.'%');	
		     })
			 ->when((!empty($category)&& $category!='all' ),function($query) use ($category){
		     return $query->where('conferences.category',$category);	
		     })->when(!empty($sort),function($query) use ($sort){
			if($sort=='title')
			{
				return $query->orderBy('conferences.title','ASC');
			}
	        if($sort=='date' || $sort=='all')
			{
				return $query->orderBy('conferences.conf_date','DESC');
			}
		})->where('conferences.status','1')->select('conferences.*','conference_invites.invities as invities')->orderBy('conferences.views','DESC')->paginate(10);
		
		$title = '<h2> <span class="color-text font-weight-bold">Trending</span> Conferences</h2>';
		$join = false;
		$rating = false;
		if($request->ajax())
		{
		$view = view("common.conference-type",compact('conferences','title','join','rating'))->render();
        return response()->json(['success'=>true,'html'=>$view]);		
		}
		return view('conference-type',compact('conferences','title','join','rating'));
		
	}
	
	
	function confCategory(Request $request)
	{
		$search =  $request->text;
		$category = $request->get('category');
		$sort = $request->get('sort');
		
	$conferences = Conference::leftJoin('conference_invites','conferences.id','=','conference_invites.conference_id')->whereRaw('json_contains(conferences.category, \'["' . $request->id . '"]\')')->where('conferences.admin_approval','1')->where('conferences.admin_decline','2')->when(!empty($search),function($query) use ($search){
		     return $query->where('conferences.author','LIKE','%'.$search.'%');	
		     })
			 ->when((!empty($category)&& $category!='all' ),function($query) use ($category){
		     return $query->where('conferences.category',$category);	
		     })->when(!empty($sort),function($query) use ($sort){
			if($sort=='title')
			{
				return $query->orderBy('conferences.title','ASC');
			}
	        if($sort=='date' || $sort=='all')
			{
				return $query->orderBy('conferences.conf_date','DESC');
			}
		})->where('conferences.status','1')->where('conferences.conf_date','>=',Carbon::now()->format('Y-m-d'))->whereIn('conferences.type',['1','2'])->where('conferences.conf_status',['0','1'])->select('conferences.*','conference_invites.invities as invities')->orderBy('conferences.conf_date','ASC')->paginate(10);	
	    $category = conferenceCategory::where('id',$request->id)->first();
		$category_name = $category->category_name;
		return view('conference-category',compact('conferences','category_name'));
		
	}
	
	
	function convertTimeZone($dateTime,$from)
	{
	   $datetime = $dateTime;
       $tz_from = $from;
         $tz_to = 'UTC';

       $dt = new \DateTime($datetime, new \DateTimeZone($tz_from));
       $dt->setTimeZone(new \DateTimeZone($tz_to));
       $date = $dt->format('Y-m-d');
	   $time = $dt->format('H:i');
	   
	   return array('date' => $date, 'time'=>$time);
		
	}
	function scriptStripper($input)
{
    return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $input);
}

function checkConference($user_id,$date,$time)
{
	return Conference::where('user_id',$user_id)->where('conf_date',$date)->where('time',$time)->where('status','1')->where('conf_status','0')->first();
	
}


function addConfLog(Request $request)
{
	$ip = $request->ip();
	$user_id = $request->user_id;
	$conf_id = $request->conf_id;
	
	$joinee = conferenceJoinee::updateOrCreate(
    ['conf_id' => $conf_id, 'user_id' => $user_id],
    ['visitor' => $ip] );
	if($joinee){
	return response()->json(array('success' => true,'msg' =>'joined'));
	}
	else{
	return response()->json(array('success' => false,'msg' =>'something went wrong'));	
	}
	
}

function askAccess(Request $request)
{
	
	$request->validate([
        'conference_id' => 'required',
    ]);
	
    $conference = Conference::where('u_id',$request->conference_id)->where('conf_status','0')->first();
	
	if(!empty($conference))
	{
		$organizer = User::find($conference->user_id);
		$access = requestAccess::where('user_id',Auth::id())->where('conf_id',$conference->id)->first();
		if(empty($access))
		{
		$request_access = new requestAccess();
		$request_access->user_id = Auth::id();
		$request_access->conf_id = $conference->id;
		if($request_access->save())
		{
		$url =  URL::signedRoute('accept-access', ['user' => $conference->user_id,'access' => $request_access->id ]);
		$reject_access =  URL::signedRoute('reject-access', ['user' => $conference->user_id,'access' => $request_access->id ]);
         Mail::to($organizer->email)->send(new acceptAccess($conference,Auth::user()->email,$url,$reject_access));	
         Session::flash('message', 'Your request sent to the organizer. You will recieve an email if organizer accept your request'); 
         Session::flash('alert-class', 'alert-success'); 
         return redirect()->back();			 
		}
		}
		else{
		 Session::flash('message', 'You already request the access'); 
         Session::flash('alert-class', 'alert-danger'); 
         return redirect()->back(); 		 
		}
	}
	else
	{
	     Session::flash('message', 'Request can not be completed'); 
         Session::flash('alert-class', 'alert-danger'); 
         return redirect()->back();	
	}
  }

	function acceptAccess(Request $request)
	{
	
	$access =  requestAccess::find($request->access);
	if(!empty($access))
	{
		if($access->status=='1')
		{
		if($request->ajax())
		{
        return response()->json(array('success' => false,'msg' => 'you already given access'));
		}
         else{		
		 Session::flash('message', 'You already give access to this user!'); 
         Session::flash('alert-class', 'alert-success'); 
	     return redirect('my-conferences');
		 }		 
		}
		$access->status = '1';
		if($access->save())
		{
			$user = User::find($access->user_id);
			$conference_invite = $this->updateConferenceInvite($access->conf_id,$user->email);
			if($conference_invite)
			{
			if($request->ajax())
		     {
             return response()->json(array('success' => true,'msg' => 'Access provided'));
		    }
		else{
	      Session::flash('message', 'Invitee request accepted'); 
          Session::flash('alert-class', 'alert-success'); 
	      return redirect('my-conferences');
		}		  
			}
		}
		else{
			if($request->ajax())
		     {
             return response()->json(array('success' => false,'msg' => 'Something went wrong'));
		    }
			else{
		Session::flash('message', 'Something went wrong!'); 
      Session::flash('alert-class', 'alert-danger'); 
	  return redirect('home');
			}	  
		}
	}
	else{
		if($request->ajax())
		     {
             return response()->json(array('success' => false,'msg' => 'Something went wrong'));
		    }
			else{
	  Session::flash('message', 'Something went wrong!'); 
      Session::flash('alert-class', 'alert-danger'); 
	  return redirect('home');			
			}
	}
}
    function rejectAccess(Request $request)
	{
	
	$access =  requestAccess::find($request->access);
	if(!empty($access))
	{
		if($access->status=='2' || $access->status=='1')
		{
			if($request->ajax())
		     {
             return response()->json(array('success' => false,'msg' => 'You already taken action on this'));
		    }
			else{
		 Session::flash('message', 'You already taken action on this!'); 
         Session::flash('alert-class', 'alert-success'); 
	     return redirect('my-conferences');	
			}
		}
		$access->status = '2';
		if($access->save())
		{
			$user = User::find($access->user_id);
			if(!empty($user))
			{
			$conference_updated = conference::where('id',$access->conf_id)->first();
			Mail::to($user->email)->send(new accessDeclined($conference_updated));
			}
			if($request->ajax())
		     {
             return response()->json(array('success' => true,'msg' => 'Request rejected'));
		    }
			else{
		 Session::flash('message', 'Invitee request rejected'); 
          Session::flash('alert-class', 'alert-success'); 
	      return redirect('my-conferences');
			}
		}
		else{
			if($request->ajax())
		     {
             return response()->json(array('success' => false,'msg' => 'Something went wrong'));
		    }
			else{
		Session::flash('message', 'Something went wrong!'); 
      Session::flash('alert-class', 'alert-danger'); 
	  return redirect('home');	
			}	  
		}
	}
	else{
		if($request->ajax())
		     {
             return response()->json(array('success' => false,'msg' => 'Something went wrong'));
		    }
			else{
	  Session::flash('message', 'Something went wrong!'); 
      Session::flash('alert-class', 'alert-danger'); 
	  return redirect('home');			
			}
	}
}

function updateConferenceInvite($conf_id,$email)
{
	$conference = conferenceInvite::where('conference_id',$conf_id)->first();
	$user = User::where('email',$email)->first();
	if(!empty($conference) && !empty($user))
	{
		$invites = json_decode($conference->invities);
		$password = json_decode($conference->user_password);
		$genrate_password = Str::random(6);
		array_push($invites,$email);
		array_push($password,$genrate_password);
		$update = conferenceInvite::where('conference_id',$conf_id)->update(['invities' => json_encode($invites) , 'user_password' => json_encode($password)]);
		if($update)
		{
		$conference_updated = conference::where('id',$conf_id)->first();
		Mail::to($email)->send(new inviteMail($conference->invitation_message, $conference->link,$conference_updated,$genrate_password));	
		}
		return true;
	}
	return false;
	}

	function myAllinvites(Request $request)
	{
		
	 $conferences = Conference::join('conference_invites','conferences.id','conference_invites.conference_id')->where('conferences.status','1')->whereIn('conferences.conf_status',['0','1'])->whereRaw('json_contains(conference_invites.invities, \'["' . Auth::user()->email . '"]\')')->select('conferences.*','conference_invites.invities as invites','conference_invites.user_password as passwords')->paginate(10);	
	 
	 return view('user.my-invites',compact('conferences'));
	}
	
	function myAlltranslatorinvites(Request $request)
	{
		if(Auth::user()->role=='3'){
		
	  $conferences = Conference::join('translator_bookings','conferences.id','translator_bookings.conf_id')->where('conferences.status','1')->whereIn('conferences.conf_status',['0','1'])->where('translator_bookings.user_id',Auth::id())->select('conferences.*')->paginate(10);	
	 
	  return view('translator.my-invites',compact('conferences'));
		}
		else
		{
		Session::flash('message', 'Something went wrong!'); 
       Session::flash('alert-class', 'alert-danger'); 
	   return redirect('home');			
		}
	}
	
	function getConferenceInvitees(Request $request)
	{
		$request->validate([
		'conf_id' => 'required',
	    ]);
		
		$conferences = Conference::join('conference_invites','conferences.id','conference_invites.conference_id')->where('conferences.status','1')->where('conferences.u_id',$request->conf_id)->select('conference_invites.*','conferences.title as title')->first();
	   DB::enableQueryLog();
       $external_invites = Conference::Join('request_accesses','conferences.id','request_accesses.conf_id')->where('conferences.status','1')->where('request_accesses.status','0')->where('conferences.u_id',$request->conf_id)->select('request_accesses.*','conferences.title as title')->get();	
     
          $returnHTML = view('common.list-invitees',compact('conferences','external_invites'))->render();
	      return response()->json(array('success' => true, 'html'=>$returnHTML));
	}
	
	function checkAvailability($type,$start_time,$end_time,$date,$from_lang)
	{
		$users = array();
	
		$translator = translator::where('translators.language',$type)
		->whereIn('translators.from_language',$from_lang)
		->where('translators.start_time','<=',$start_time)
		->where('translators.end_time','>=',$end_time)
		->where('status','1')
		->get();

         if(!empty($translator))
		 {
			foreach($translator as $trans) 
			{
			$booking =  translatorBooking::where('user_id',$trans->user_id)->where('conf_date','=',$date)->whereBetween('end_time',[strtotime($start_time),strtotime($end_time)])->where('status','1')->first();	
			if(empty($booking))
			{
			$users[] = $trans->user_id;	
			}
			}
			 
		 }
		 return $users;
	}
	
	function assignTranslator($start_time,$end_time,$date,$conf,$translator_lang,$from_lang)
	{
		$array = $translator_lang;
		foreach($array as $lang)
		{
			$user = $this->checkAvailability($lang,$start_time,$end_time,$date,$from_lang);
			if(!empty($user))
			{
				
				$translator_booking = new translatorBooking();
				$translator_booking->user_id = $user[array_rand($user)];
				$translator_booking->conf_id = $conf->id;
				$translator_booking->start_time = strtotime($start_time);
				$translator_booking->end_time = strtotime($end_time);
				$translator_booking->conf_date = $date;
				if($translator_booking->save())
				{
				$user_details = User::find($translator_booking->user_id);
				Mail::to($user_details->email)->send(new translatorInvitation($conf,$user_details));		
				}
				
			}
			
		}
		
	}
	
	function sendTranslatorUpdate($users,$reason='Conference time changed',$subject)
	{
	foreach($users as $user)
	{
     $translator = User::find($user->user_id);
	 $conference = Conference::find($user->conf_id);
	 if(!empty($translator->email))
				{
				//Mail::to($translator->email)->send(new translatorUpdate($conf,$reason,$subject));
                 $user_details = User::find($translator->id);				
                Mail::to($user_details->email)->send(new translatorUpdate($conference,$reason,$subject));					
				}
	 

	}	
		
	}

	function socialShare(Request $request)
	{
	$conference = Conference::where('u_id',$request->id)->first();	
	return view('social-share.social-share',compact('conference'));
	}

}
