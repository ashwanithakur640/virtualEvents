<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\videoConference;
use Storage;
use Illuminate\Support\Str;
use Session as SS;
use Auth;
use Illuminate\Contracts\Filesystem\Filesystem;
use App\conferenceViews;
use App\videoStreaming;
use App\Session;
use App\likeable;
use App\conferenceInvite;
use App\conferenceMessage;
class videoController extends Controller
{
    //
	function index(Request $request)
	{
		if(Auth::user()->role !='2')
		{
		Session::flash('message', 'You are not authorize to do this!'); 
        Session::flash('alert-class', 'alert-danger');
	    return redirect('my-profile'); 	
		}
		if (Auth::user()->can('create', videoConference::class)) {
		return view('user.add-video');	
		}
		else{
			$permium = ['2','5','3','6'];
			if(in_array(Auth::user()->membership,$permium))
			{
			SS::flash('message', 'Your monthly limit reached to upload the videos.'); 
            SS::flash('alert-class', 'alert-danger'); 	
			}
			else{
		   SS::flash('message', 'You are not authorized to do this please upgrade your plan'); 
           SS::flash('alert-class', 'alert-danger'); 
			}		
        return redirect('my-profile');		
		}
	}
	
	function uploadVideo(Request $request)
	{
		if(Auth::user()->role !='2')
		{
		Session::flash('message', 'You are not authorize to do this!'); 
        Session::flash('alert-class', 'alert-danger');
	    return redirect('my-profile'); 	
		}
		if (Auth::user()->can('create', videoConference::class)) {
	  $request->validate([
        'title' => 'required|max:255|regex:/^[a-zA-Z0-9_ ]*$/',
        'category' => 'required|integer',
		'date' => 'required|date_format:Y-m-d',
		'author' => 'required|string|regex:/^[a-zA-Z0-9_ ]*$/',
		'video' => 'required|mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi,application/pdf|max:256000',
		'description' => 'required|max:200|regex:/^[a-zA-Z0-9_ ]*$/'
         ],['video.mimetypes' => 'Please upload MP4,Mov,AVI video formats','description.regex' => 'In Description only letter and numbers are allowed']);

       if($request->hasFile('video')){
        
			 $file = $request->file('video');
             $filename = time().'_.'.$file->getClientOriginalExtension();
			 $path = '/'.$filename;
			 
             //$upload_path = Storage::disk('s3')->putFileAs($path, $file, $filename);
			 $upload_path = Storage::disk('s3')->put($path,file_get_contents($file),'public');
			 $video = new videoConference();
			 $video->title = $request->title;
			 $video->category = $request->category;
			 $video->date = $request->date;
			 $video->author = $request->author;
			 $video->video = $filename;
			  $video->user_id = Auth::id();
			 $video->description = $request->description;
			 $video->video_id = Str::uuid().'-'.time();
			 if($video->save())
			 {
				 if($file->getClientOriginalExtension()=='pdf')
				 {
			SS::flash('message', 'Pdf uploaded successfuly!');
				 }
				 else
				 {
			SS::flash('message', 'Video uploaded successfuly!');
				 } 
            SS::flash('alert-class', 'alert-success'); 
			return redirect('all-videos');
			 }
			 else{
			SS::flash('message', 'Something went wrong'); 
            SS::flash('alert-class', 'alert-danger'); 	 
			return redirect()->back();	 
			 }
			
        }		 
		}
		else{
			
		Session::flash('message', 'You are not authorized to do this please upgrade your plan'); 
        Session::flash('alert-class', 'alert-danger'); 	 
        return redirect('my-profile');		
		}
	}
	
	function allVideos(Request $request)
	{
		if (Auth::user()->can('viewAny', videoConference::class)) {
			if(!empty($request->get('date')))
			{
			$date = $request->get('date');	
			}
			else{
			$date = $request->date;	
			}
		
			if(!empty($request->get('search')))
			{
				$search = $request->get('search');
			}
			else{
			$search = $request->text;	
			}
		
		$videos = videoConference::when(!empty($date),function($query) use ($date){
			
				return $query->where('date',$date);
		})->when(!empty($search),function($query) use ($search){
			
				return $query->where('title','LIKE','%'.$search.'%');
		})->orderBy('created_at','DESC')->paginate(9);
		if($request->ajax())
		{
		$view = view("common.videos",compact('videos'))->render();
        return response()->json(['success'=>true,'html'=>$view]);	
			
		}
		else{
		return view('user.video-listing',compact('videos'));
		}
		}
		else{
			
		SS::flash('message', 'You are not authorized to do this please upgrade your plan'); 
        SS::flash('alert-class', 'alert-danger'); 	 
        return redirect('my-profile');			
		}
		
	}
	
	function videoView(Request $request)
	{
		$video = videoConference::where('video_id',$request->id)->first();
		
		$conf_view = conferenceViews::where('conf_id',$video->id)->where('user_id',Auth::id())->where('type','2')->first();
			 if(empty($conf_view))
			 {
				 $view = new conferenceViews();
				 $view->conf_id = $video->id;
				 $view->user_id = Auth::id();
				  $view->type = '2';
				 $view->save();
			videoConference::where('id',$video->id)->update(['views'=>($video->views+1)]); 
			 }
		
		if($request->ajax())
		{
		$view = view("common.video-player",compact('video'))->render();
        return response()->json(['success'=>true,'html'=>$view]);		
		}
	}


	function videoViewRecording(Request $request)
	{

		$video = videoStreaming::find($request->id);
		if($this->checkUserAurthorized($video->conference_id))
		{
			$conf_view = conferenceViews::where('conf_id',$video->id)->where('user_id',Auth::id())->where('type','3')->first();
				if(empty($conf_view)){

					$view = new conferenceViews();
					$view->conf_id = $video->id;
					$view->user_id = Auth::id();
					$view->type = '3';
					$view->save();
					videoStreaming::where('id',$video->id)->update(['views'=>($video->views+1)]); 
				 }
				 
			$conference = Session::where('id',$video->conference_id)->first();
			$conference_messages = conferenceMessage::where('conf_id',$video->conference_id)->first();
			
			if($request->ajax())
			{
			
				$view = view("common.video-player-recording",compact('video','conference'))->render();
		        return response()->json(['success'=>true,'html'=>$view]);		
			}
			else{
		     return  view("common.video-player-recording-view",compact('video','conference','conference_messages'))->render();	
			}
		}
		else{
			Session::flash('message', 'You are not authorized to access this'); 
	        Session::flash('alert-class', 'alert-danger'); 	 
	        return redirect('home');		
		}
	}
	
	function getConferenceRecording($prefix , $cname)
	{
		$videos = videoStreaming::where('cname',$cname)->where('s3_file', '<>', '')->orderBy('id', 'desc')->limit('1')->get();
		print_r($videos);
		if(!empty($videos[0])){
		if( $this->checkUserAurthorized($videos[0]->conference_id))
		{
		foreach($videos as $video){
			$Conference = Session::where('u_id',$video->cname)->first();
			// print_r($Conference);
			$video->title = $Conference->title;
			$video->image = $Conference->image;
			
		}
		//print_r($videos);

		
		return view('my-recordings',compact('videos'));
	}
	else
	{
		SS::flash('message', 'You are not authorized to access this'); 
        SS::flash('alert-class', 'alert-danger'); 	 
        return redirect('home');	
	}
	}
	else
	{

		//echo $cname;

		$Conference = Session::where('u_id',$cname)->first();

		//dd($Conference);
		if($Conference->user_id == Auth::id())
		{
			return view('my-recordings',compact('videos'));
		}
		else{
			SS::flash('message', 'Nothing found'); 
			SS::flash('alert-class', 'alert-danger'); 	 
			return redirect('home');
		}		
		}
	} 
	
	public function ajaxRequest(Request $request){


		$video = videoStreaming::find($request->id);
		$check_previous = likeable::where('user_id',Auth::id())->where('post_id',$request->id)->first();
		if($request->type=='1')
		{
			if(empty($check_previous))
			{
              $like = likeable::updateOrCreate(
				['post_id' => $request->id, 'user_id' => Auth::id()],
				['like' => '1']
			);
			}
			else
			{
				likeable::where('user_id',Auth::id())->where('post_id',$request->id)->update(['like' => '1']);	
			}
		}
		elseif($request->type=='2')
		{
			if(!empty($check_previous))
			{
				likeable::where('user_id',Auth::id())->where('post_id',$request->id)->delete();
			}
		}
		elseif($request->type=='3')
		{
			if(empty($check_previous))
			{
				$like = likeable::updateOrCreate(
					['post_id' => $request->id, 'user_id' => Auth::id()],
					['like' => '0']
				);
			}
			else{
				likeable::where('user_id',Auth::id())->where('post_id',$request->id)->update(['like' => '0']);	
			}
		}
		elseif($request->type=='4')
		{
			if(!empty($check_previous))
			{
				likeable::where('user_id',Auth::id())->where('post_id',$request->id)->delete();
			}
			
		}
		else{

		}

		$view = view("common.video-recording-likes",compact('video'))->render();
        return response()->json(['success'=>true,'html' => $view]);
	}
	
	function checkUserAurthorized($conf_id)
	{
		$conference = Session::where('id',$conf_id)->first();
		if($conference->type=='1')
		{
			return true;
		}
		else
		{
			/* Check user is authorized user */
		   if($conference->user_id==Auth::id())
		   {
			   return true;
		   }
		   else
		   {
			$invites = conferenceInvite::where('conference_id',$conference->id)->first();
			if(!empty($invites))
			{
			   $invite_emails = json_decode($invites->invities,true);
			   if(in_array(Auth::user()->email,$invite_emails))
			   {
				   return true;
			   }
			   else
			   {
				   return false;   
			   }
			}
			else{
				return false;
			}
		   }
		}
	}
	
}
