<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>LOGYTalks </title>	
<link rel="canonical" href="{{url('conference')}}/{{optional($conference)->u_id}}" />
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="article" />
<meta property="og:title" content="{{optional($conference)->title}}" />
<meta property="og:description" content="@php echo strip_tags(optional($conference)->description); @endphp" />
<meta property="og:url" content="{{url('conference')}}/{{optional($conference)->u_id}}" />
<meta property="og:site_name" content="LogyTalks" />
 @if(!empty(optional($conference)->image))
<meta property="og:image" content="{{URL::asset('storage/conference-images')}}/{{optional($conference)->image}}" />
@else
<meta property="og:image" content="{{URL::asset('images/detail-img.jpg')}}" />
@endif

<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="{{optional($conference)->title}}"/>
<meta name="twitter:description" content="@php echo strip_tags(optional($conference)->description); @endphp"/>
 @if(!empty(optional($conference)->image))
<meta name="twitter:image" content="{{URL::asset('storage/conference-images')}}/{{optional($conference)->image}}" />
@else
<meta name="twitter:image" content="{{URL::asset('images/detail-img.jpg')}}" />
@endif

    <link href="https://fonts.googleapis.com/css?family=Lato:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('agora/css/aos.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link rel="stylesheet" href="{{ asset('agora/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('agora/css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('agora/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('agora/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('agora/css/responsive.css') }}">
    <script> var base_url = "{{asset('/')}}"; </script>
    <style>
#send_rtm_channel_message {
    background-color: #0057D9;
    font-weight: 600;
    padding: 10px 10px;
    border-radius: 40px;
    border: none;
    float: right;
    color: #FFF !important;
    margin-top: 10px;
    width: 50px;
    height: 50px;
}
#send_rtm_channel_message img {
    width: 25px;
}
#send_rtm_channel_message:hover{
background-color: #444;
color:#fff;
}

@media (min-width:801px)  { 
.remote_video_panel{
    height: 74px;
overflow: hidden;
}
}

.body-conference-call {
    margin-left: 158px;
}
    </style>
</head>

<body class="home-body">

<div class="body-conference-call">

  <header class="home-header call-screen-header">
        <nav class="navbar navbar-expand-lg nav-custom d-flex innerpage-header">
            <div class="container-fluid">
                <a class="navbar-brand logo" href="/home"> <img class="logo-custom" src="{{ asset('agora/images/v-logo.png')}}" alt="logo"> </a>

                <p  id="message"></p><span id="organizer_status">@if($organizer->id!=$user->id) Waiting For Organizer</span> @endif

                <!-- <ul class="max-min-screen">
                  <li><a href="#"><i class="fa fa-window-minimize cus-mini" aria-hidden="true"></i></a></li>
                  <li><a href="#"><i class="fa fa-window-maximize cus-max" aria-hidden="true"></i></a></li>
                  <li><a href="#"><i class="fa fa-times cus-cross" aria-hidden="true"></i></a></li>
                </ul> -->
            </div>
        </nav>
    </header>
   

   <section class="virtual-outer video-conference-outer">
   	  <div class="container-fluid">
   	     <div class="row">
<!--    	        <div class="col-md-3 col-sm-12">
                <div class="video-left">
                    <div class="participant-title"><img src="{{ asset('agora/images/p1.png') }}">Participants</div>
                    <ul class="video-list" id="participants">                     
  
                    </ul>
                    {{-- <div class="mute-bts">
                        <a href="javascript:muteAll()">Mute All</a>
                        <a href="javascript:unmuteAll()">Unmute All</a>
                    </div> --}}
                </div>
            </div> -->
           
            <div class="col-md-9 col-sm-12">
                <div class="video-left video-left-call">

                   

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                          <div class="video-icon-place" id="local_stream_container">
                            <div class="video-call-center"  id="local_stream">
                                <!-- <img src="{{ asset('agora/images/man.jpg') }}" alt=""> -->
<!--                                 <iframe width="100%" height="700" src="https://www.youtube.com/embed/tgbNymZ7vqY">
                                </iframe> -->
                                {{-- <video width="100%" height="300" controls>
                                  <source src="agora/movie.mp4" type="video/mp4">
                                  <source src="movie.ogg" type="video/ogg">
                                  Your browser does not support the video tag.
                                </video> --}}
                            </div>
                            <div class="share-screen-center video-call-center"  id="local_stream_screen" style="height:0">
                      
                            </div>
                            <ul class="video-upper-btns">
                               
                                  <li>
                                    <a class="active" id="mute-audio" data-userid="{{$user->id}}" href="#"  data-toggle="tooltip" title="Mute Mic"><img src="{{ asset('agora/images/mute.png') }}"></a>
                                    <a class="" id="unmute-audio" href="#"  style="display: none;" data-toggle="tooltip" title="Unmute Mic"><img src="{{ asset('agora/images/unmute.png') }}"></a>
                                  </li>
                             
              
                                  <li>
                                    <a class="active" id="mute-video" data-userid="{{$user->id}}" href="#"  data-toggle="tooltip" title="Mute Video"><img src="{{ asset('agora/images/video-i-c.png') }}"></a>
                                    <a class="" id="unmute-video" href="#" style="display: none;"  data-toggle="tooltip" title="Unmute Video"><img src="{{ asset('agora/images/video-i.png') }}"></a>
                                  </li>
                                  <li class="camera-switch" style="display:none;"><a href="javascript:switchCamera()" data-toggle="tooltip" title="Switch Camera" class="camera-switch"><img src="{{ asset('agora/images/camera.png') }}"></a></li>
                 

                                 
                                  <li>
                                    <a id="sharescreen" href="#" data-id="{{$user->id}}"  data-toggle="tooltip" title="Share Screen"><img src="{{ asset('agora/images/share-i.png') }}"></a>
                                    <a class="active" id="sharescreenclose" href="#"  style="display: none;"  data-toggle="tooltip" title="Close Share Screen"><img src="{{ asset('agora/images/share-i.png') }}"></a>
                                  </li>
              
                               
                  
              
                 
              
                 
                  
                                  <li class="shared_screen_presenter" id="share_screen_user_{{$user->id}}" style="display:none">
                                    <a id="sharescreen" href="#" data-id="{{$user->id}}" data-toggle="tooltip" title="Share Screen"><img src="{{ asset('agora/images/share-i.png') }}"></a>
                                    <a class="active" id="sharescreenclose" href="#"  style="display: none;" data-toggle="tooltip" title="Stop Share Screen"><img src="{{ asset('agora/images/share-i.png') }}"></a>
                                  </li>
              
                                 
                
                                  <li class="call-end"><a href="javascript:void()" id="leave" data-toggle="tooltip" title="End Call"><img src="{{ asset('agora/images/endi.png') }}"></a></li>
            
            
                    
            
                            </ul>
                            <div class="confrnc-title">{{$conference->title}}</div><div id="timer"></div>
                            <a class="max-icon-img" href="javascript:toggleFullScreen(document.body)"><img src="{{ asset('agora/images/max.png') }}"></a>
                          
                            
                             <div class="record-div">
                              <a href="#" id="start-recording"><span class="red-record green-record"></span><span class="record-text">Record</span></a>
                              <a id="start-recording-wait" style="display:none;"><span class="red-record green-record"></span><span class="record-text">Processing...</span></a>
                              <a href="#" id="stop-recording-wait" style="display:none;"><span class="red-record"></span><span class="record-text">Processing...</span></a>
                            
                              <a href="#" id="stop-recording" style="display:none;"><span class="red-record"></span><span class="record-text">Recording</span></a>
                            </div>
							
							
							
                           
                            <div class="share-screen-out" id="share-screen-out">
                              {{-- <img src="/storage/conference-images/1589986285.jpg" alt=""> --}}
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="meeting-title">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                     <!-- <div class="row" id="remote_stream">

                                     </div> -->
<div class="flexslider carousel" id="slider">
 <ul class="slides flex-ul-custom" id="remote_stream">
  <li class="slide1">
      <div class="col-sm-12">
        <div class="testimonial-outr">
            <div class="testimonial-content1">
                {{-- <video width="100%" height="250" controls>
                    <source src="movie.mp4" type="video/mp4">
                    <source src="movie.ogg" type="video/ogg">
                    Your browser does not support the video tag.
                  </video> --}}
                <div class="slider-bottom">
                <h4> </h4>
                </div>
            </div>
            <div class="testimonial-lower-outr">
              <a href="#"><i class="fa fa-thumb-tack" aria-hidden="true"></i></a>
            </div>
          </div>
        </div>
    </li>

    <!-- items mirrored twice, total of 12 -->
  </ul>
</div>
<div class="custom-navigation">
  <a href="#" class="flex-prev" title="Previous">Prev</a>
  <div class="custom-controls-container"></div>
  <a href="#" class="flex-next" title="Next">Next</a>
</div>
                                    </div>
                                    </div>
                            </div>
                        </div>
                    </div>
<!--                     <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="meeting-title">
                            
                                <div class="row">
                                    <div class="col-md-10 offset-md-1 col-sm-12">
                                    <div class="row multiple-items video-slider" id="remote_stream">

                                        {{-- <div class="col-sm-4">
                                            <div class="testimonial-content">
                                                <video width="100%" height="120" controls>
                                                            <source src="agora/movie.mp4" type="video/mp4">
                                                            <source src="movie.ogg" type="video/ogg">
                                                            Your browser does not support the video tag.
                                                          </video>
                                                <div class="slider-bottom">
                                                <h4> John Doe </h4>
                                                <a href="#"><i class="fa fa-microphone-slash" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div> --}}
                                                 

                                                </div>
                                                <div class="slide-arrow">
                                                    <a href="#" class="prev slick-arrow" style=""><i class="fa fa-angle-left" aria-hidden="true"></i> </a>
                                                    <a href="#" class="next slick-arrow" style=""><i class="fa fa-angle-right" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                    </div>
                                    </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="video-left call-right-screen-chat">
                      <div class="organizer-div video-else-img">
                        <div class="organiser-img" id="organiser-img">
                          <div class="organiser-img-outer org-video-els-img">
                          @if($organizer->image!='')
                                     <img src="{{ asset('storage/profile-images/'.$organizer->image) }}" alt="">
                                     @else
                                     <img src="{{ asset('agora/images/client.jpg') }}" alt="">
                                     @endif
                          </div>
                        </div>
                        <div class="organizer-desc">
                          <b>{{__('Moderator')}}:</b> {{$organizer->name}}
                        </div>
                      </div>
					@if($remote_controls_access==1)
                      <div class="choose-presenter">
                        <div class="choose-presnter-top">
                        <div class="choose-presnter-title">{{__('Presenter')}}</div>
                        <a class="admin-icon" href="javascript:makepresenterLocal(<?php echo $organizer->id; ?>,true)"><i class="fa fa-user-circle-o admin-icon" aria-hidden="true"></i></a>
                        </div>
                        <ul>
                          <li style="display:none;">
                            <select class="presenter-selectbox" id="presenter-list" style="display:none;">
               <option>Select Presenter</option>
              {{--
                              <option><b>James Daniel:</b> james.danial@gmail.com</option>
                              <option><b>James Daniel:</b> james.danial@gmail.com</option>
                              <option><b>James Daniel:</b> james.danial@gmail.com</option>
                              <option><b>James Daniel:</b> james.danial@gmail.com</option>
              --}}
                            </select>

                           
            
                            </ul>
                           
                            <!-- <div class="prsenter-left">
                                 <div class="john-title">James Daniel</div>
                                 <div class="john-desc">james.danial@gmail.com</div>
                            </div>
                            <i class="fa fa-angle-right" aria-hidden="true"></i> -->
                          </li>
                        </ul>
                     
                      </div>
                      <ul class="video-list prsenter-list-ul" id="presenter-list-ul"></ul>

            @endif
					  
                     
				
					  
					 
                      <div class="chat-participant-tab">
                        <nav>
                            <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true"><!-- <img src="{{ asset('agora/images/p1.png') }}"> --><i class="fa fa-user" aria-hidden="true"></i>Participants (<span id="participant_count">0</span>)</a>
                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false"><!-- <img src="{{ asset('agora/images/chat-i.png') }}"> --><i class="fa fa-commenting" aria-hidden="true"></i>Group Chat</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                    <!-- <div class="participant-title"><img src="{{ asset('agora/images/p1.png') }}">Participants</div> -->
                                   @if($remote_controls_access==1) 
                                    <div class="mute-button-bottom-outer"> 
                                    <div class="mute-bts">
                                        <a href="javascript:muteunmuteremote('all','mute','mic')" id="mute-mic-all"><i class="fa fa-microphone inactive-icon" aria-hidden="true"></i> Mute All</a>
                                        <a href="javascript:muteunmuteremote('all','unmute','mic')" id="unmute-mic-all" style="display:none;"><i class="fa fa-microphone active-icon1" aria-hidden="true"></i> Unmute All</a>
                                    </div>
                                    <div class="mute-bts">
                                        <a href="javascript:muteunmuteremote('all','mute','video')" id="mute-video-all">Mute All <i class="fa fa-video-camera inactive-icon" aria-hidden="true"></i></a>
                                        <a href="javascript:muteunmuteremote('all','unmute','video')" id="unmute-video-all" style="display:none;"><i class="fa fa-video-camera active-icon1" aria-hidden="true"></i> Unmute All</a>
                                    </div>
                                    <div class="mute-bts">
                                        <a href="javascript:muteunmuteremote('all','mute','chat')" id="mute-chat-all"><i class="fa fa-commenting inactive-icon" aria-hidden="true"></i> Mute All</a>
                                        <a href="javascript:muteunmuteremote('all','unmute','chat')" id="unmute-chat-all" style="display:none;"><i class="fa fa-commenting active-icon1" aria-hidden="true"></i> Unmute All</a>
                                    </div>
                                  </div>
                                   @endif
                                    <ul class="video-list" id="participants">                     

                                    </ul>
                                </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                               
                          <div class="chat-list-outr">   
                          <!-- <div class="john-title group-chat">Group Chat</div> -->
                          <div class="chat-main-outer" id="log">
                        
                          </div>
                          <hr class="chat-line">
                          <div class="chat-bottom" id="chat_control">
                              {{-- <div class="chat-bottom-left">
                              <label>To:</label>
                              <select>
                                  <option>Everyone</option>
                              </select>
                              </div> --}}
                              {{-- <div class="chat-bottom-right">
                              <label><i class="fa fa-file-o" aria-hidden="true"></i> File</label>
                              <i class="fa fa-ellipsis-h custom-ellispe" aria-hidden="true"></i>
                          </div> --}}
                          
                          <textarea placeholder="Type a Message" id="rtm_channel_message"></textarea>
                          <!-- <button type="button" value="Send" id="send_rtm_channel_message">Connecting...</button> -->
                          <button type="button" id="send_rtm_channel_message"><img src="{{ asset('agora/images/send.png') }}"></button>
                         
                          </div>
                        </div>
                            </div>
                        </div>
                      </div>
                </div>
            </div>

            
           
            

   	     </div>
   	  </div>
   </section> 
  


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/flexslider/2.7.2/jquery.flexslider.js"></script>
<script type="text/javascript">

	function slider(){
		
		// store the slider in a local variable
  var $window = $(window),
      flexslider = { vars:{} };
 
  // tiny helper function to add breakpoints
  function getGridSize() {
    return (window.innerWidth < 600) ? 3 :
           (window.innerWidth < 900) ? 3 : 4;
  }
 
  /*$(function() {
    SyntaxHighlighter.all();
  });
 */
		
$('#slider').flexslider({
      animation: "slide",
    controlNav: false,
    animationLoop: false,
    slideshow: false,
	 itemWidth: 300,
      itemMargin: 5,
      minItems: getGridSize(), // use function to pull in initial value
      maxItems: getGridSize(), // use function to pull in initial value
	controlNav: true,               //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
   controlsContainer: $(".custom-controls-container"),
    customDirectionNav: $(".custom-navigation a"),
    //touch: jQuery('.flexslider ul.slides li:empty').remove().length > 3,
    start: function() {
          jQuery('.flexslider').find('.loading').hide();
       
       jQuery('.flexslider').each(function(index){
          
        jQuery(this).find('ul.slides li:empty').remove();
		
         if (jQuery(this).find('ul.slides li').length <= 1 ) {
			 div = "<li id='waiting-partcipants'>Waiting for participants </li>";
            jQuery('.flexslider').data('flexslider').addSlide($(div)); 
			jQuery('#local_stream_container').addClass('no-participant');
		 }
          if (jQuery(this).find('ul.slides li').length <= 3) {
                 jQuery(this).find('ul.slides li:empty').remove();
                jQuery('.custom-navigation').hide();
           }
		    else{
			   jQuery(this).find('.flex-direction-nav').show(); 
			   
		   }
});
       
  
    },
	added: function(){
		
	jQuery('.flexslider').each(function(index){
          
        jQuery(this).find('ul.slides li:empty').remove();
		
         if (jQuery(this).find('ul.slides li').length >= 3 ) {
		    jQuery('#waiting-partcipants').remove();
			jQuery('#local_stream_container').removeClass('no-participant');
		 }
          if (jQuery(this).find('ul.slides li').length <= 2) {
                 jQuery(this).find('ul.slides li:empty').remove();
                 jQuery('.custom-navigation').hide();
           }
		   else{
			     jQuery(this).find('ul.slides li:empty').remove();
			   jQuery('.custom-navigation').show();
			   
		   }
});

	},
	removed: function(){
	
		jQuery('.flexslider').each(function(index){
          
        jQuery(this).find('ul.slides li:empty').remove();
		
         if (jQuery(this).find('ul.slides li').length <= 2 ) {
			
		   div = "<li id='waiting-partcipants'>Waiting for partcipants </li>";
            jQuery('.flexslider').data('flexslider').addSlide($(div)); 
			jQuery('#local_stream_container').addClass('no-participant');
		 }
          if (jQuery(this).find('ul.slides li').length <= 3) {
                 jQuery(this).find('ul.slides li:empty').remove();
                 jQuery('.custom-navigation').hide();
           }
		   else{
			     jQuery(this).find('ul.slides li:empty').remove();
			   jQuery('.custom-navigation').show();
		   }
});
	}
	
	  
    });
	}
$(".slide1").hide();
slider();
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="{{ asset('agora/js/bootstrap.min.js') }}"></script>

 
 

 <form id="form" class="row col l12 s12" style="height: 0;overflow: hidden;">
   
                    <select name="cameraId" id="cameraId"></select>
                 
                    <select name="microphoneId" id="microphoneId"></select>
                
  </form>
  
     <input type="hidden" id="user" value="{{$user}}" />
     <input type="hidden" id="channel" value="{{$conference->u_id}}" />
     <input type="hidden" id="conference_id" value="{{$conference->id}}" />
     <input type="hidden" id="organizer_id" value="{{$organizer->id}}" />
	 
     <input type="hidden" id="time_left" value="{{$time_left}}" />
     <input type="hidden" id="seconds_left" value="{{$seconds_left}}" />
 <input type="hidden" id="remote_controls_access" value="{{$remote_controls_access}}" />
<input type="hidden" id="now_presenting" value="{{$now_presenting}}" />
	
     <input type="hidden" id="uid_encrypted" value="{{$uid}}" />
     <input type="hidden" id="share_screen_id" value="" />
    <input type="hidden" id="uid_sharescreen" value="" />
	 <input type="hidden" id="my_user_id" value="{{$user->id}}" />
   <input type="hidden" id="prefix" value="{{$prefix}}" />
  
   <div id="hidden-admin-video" style="display:none;">
   
   </div>




<div id="hidden-streams" style="display:none"></div>
<!-- <script src="{{ asset('agora/materialize.min.js') }}"></script> -->
<script src="{{ asset('agora/AgoraRTCSDK-3.0.2.js') }}"></script>
<script src="{{ asset('agora/agora-rtm-sdk-1.2.2.js') }}"></script> 
<script src="{{ asset('agora/app-bk.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>


</div>

<script type="text/javascript">

   var time = '<?php echo $seconds_left ?>';
  var minutes = Math.floor(time / 60000);
  var seconds = ((time % 60000) / 1000).toFixed(0);
  document.getElementById('timer').innerHTML =
  minutes + ":" + seconds;
  startTimer();
function startTimer() {
  var presentTime = document.getElementById('timer').innerHTML;
  var timeArray = presentTime.split(/[:]+/);
  var m = timeArray[0];
  var s = checkSecond((timeArray[1] - 1));
  if(s==59){m=m-1}
  //if(m<0){alert('timer completed')}
  
  document.getElementById('timer').innerHTML =
    m + ":" + s;
  console.log(m)
  setTimeout(startTimer, 1000);
}

function checkSecond(sec) {
  if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
  if (sec < 0) {sec = "59"};
  return sec;
}
</script>
</body>

</html>