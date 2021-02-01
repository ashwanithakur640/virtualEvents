<!DOCTYPE html>
<html lang="en">
<head>
    <title>Web Conference</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/media.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&family=Rubik:wght@300;400;500;600&display=swap" rel="stylesheet"> 
   
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <!--   <link rel="stylesheet" href="{{ asset('agora/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('agora/css/slick-theme.css') }}"> -->
  
    <style type="text/css">
        .slide1 .fa-thumb-tack{
            display:none;
        }
        #timer{
          display:none;
        }

        textarea#rtm_channel_message {
            width: 100%;
        }

        ul.video-upper-btns {
          display: flex;
          background: black;
        }

        ul.video-upper-btns li {
          margin-left: 100px;
        }

        ul.video-upper-btns {
          display: flex;
          background: black;
        }

        .chat_btn_unmute {
            display: none ;
        }

        .chat_btn_mute{
          display:none;
        }
        @media (min-width:801px)  { 
          .remote_video_panel{
            height: 74px;
            overflow: hidden;
          }
        }

    </style>

</head>

<body>

<header class="custom-header">
    <div class="container-fluid">
        <div class="header_section d-flex justify-content-between align-items-center">
            <div class="left-side">
                 Virtual Event
            </div>
            <div class="right-side">
                <nav class="navbar navbar-expand-lg navbar-light"></nav>
            </div>
        </div>  
     </div>
</header>

<main class="help_section-s">
   
<section class="help_section pt-3">
        <div class="container-fluid">
            <div class="row">
            <div class="col-md-3">
            <div class="left-side-textarea">
                <div class="ask_question">
                    <h2>Lets Chat</h2>
                </div>
                <div class="welcome-faq">
                    <h2 class="mb-0"> </h2>
                </div>
                <div class="text_question" id="log">
                    
                   
                </div>
                <textarea placeholder="Type a Message" id="rtm_channel_message"></textarea>
                <div class="btn_enter">
                   <button type="submit" class="btn_sec" id="send_rtm_channel_message">Enter</button>
               </div>
                </div>
             
            </div>
           <div class="col-md-6">
                <div class="help_faq center-video-part">
                    <div class="help_heading">
                        <h2>{{ $conference->name }}</h2>
                    </div>
                    <div class="watct_on">
                        <div class="video-icon-place" id="local_stream_container">
                            <div class="video-call-center"  id="local_stream">
                               
                                {{-- <video width="100%" height="300" controls>
                                  <source src="agora/movie.mp4" type="video/mp4">
                                  <source src="movie.ogg" type="video/ogg">
                                  Your browser does not support the video tag.
                                </video> --}}
                            </div>
                            <div class="share-screen-center video-call-center"  id="local_stream_screen" style="height:0">
                      
                            </div>

                            <ul class="video-controls">
                            <li><div id="timer"></div>  </li>

<?php 
  if($user->id == $organizer->id){
?>
                              
                               <li>
                                    <a class="active" id="mute-audio" data-userid="{{$user->id}}" href="#"  data-toggle="tooltip" title="Mute Mic"><i class="fa fa-microphone" aria-hidden="true"></i></a>
                                    <a class="" id="unmute-audio" href="#"  style="display: none;" data-toggle="tooltip" title="Unmute Mic"><i class="fa fa-microphone-slash" aria-hidden="true"></i></a>
                                </li>


                                <li>
                                    <a class="active" id="mute-video" data-userid="{{$user->id}}" href="#"  data-toggle="tooltip" title="Mute Video"><i class="fa fa-video-camera" aria-hidden="true"></i></a>
                                    <a class="" id="unmute-video" href="#" style="display: none;"  data-toggle="tooltip" title="Unmute Video"><i class="fa fa-pause-circle" aria-hidden="true"></i></a>
                                </li>


<li>
                                    <a id="sharescreen" href="#" data-id="{{$user->id}}"  data-toggle="tooltip" title="Share Screen"><i class="fa fa-share" aria-hidden="true"></i></a>
                                    <a class="active" id="sharescreenclose" href="#"  style="display: none;"  data-toggle="tooltip" title="Close Share Screen"><i class="fa fa-undo" aria-hidden="true"></i></a>
                                  </li> 
<?php
  }

?>
                                   <li class="shared_screen_presenter" id="share_screen_user_{{$user->id}}" style="display:none">
                                    <a id="sharescreen" href="#" data-id="{{$user->id}}" data-toggle="tooltip" title="Share Screen"><i class="fa fa-share" aria-hidden="true"></i></a>
                                    <a class="active" id="sharescreenclose" href="#"  style="display: none;" data-toggle="tooltip" title="Stop Share Screen"><i class="fa fa-undo" aria-hidden="true"></i></a>
                                  </li> 
              
                                  <li class="call-end"><a href="javascript:void()" id="leave" data-toggle="tooltip" title="End Call"><i class="fa fa-phone" aria-hidden="true"></i></a></li>
                                  

                                  <li class="full-screen"><a class="max-icon-img" href="javascript:toggleFullScreen(document.body)"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a></li>
            
                        </ul>


                            
                             <!-- <div class="record-div">
                              <a href="#" id="start-recording"><span class="red-record green-record"></span><span class="record-text">Record</span></a>
                              <a id="start-recording-wait" style="display:none;"><span class="red-record green-record"></span><span class="record-text">Processing...</span></a>
                              <a href="#" id="stop-recording-wait" style="display:none;"><span class="red-record"></span><span class="record-text">Processing...</span></a>
                            
                              <a href="#" id="stop-recording" style="display:none;"><span class="red-record"></span><span class="record-text">Recording</span></a>
                            </div> -->
              
              
              
                           
                            <div class="share-screen-out" id="share-screen-out">
                              {{-- <img src="/storage/conference-images/1589986285.jpg" alt=""> --}}
                            </div>
                          </div>
                        <div class="watct_img">
                            <!-- <img src="images/watch-on_other.jpg"> -->
                        </div>
                    </div>
                </div>
 <div class="participant-slider">
                      

<div class="meeting-title">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                     
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

            <div class="col-md-3">
              <div class="left-side-textarea left-video-part">
                <div class="ask_question">
                    <h2>Participants</h2>
                </div>

                <div class="text_question participant-list">

                  @if($remote_controls_access==1)
                      <div class="choose-presenter">
                        <div class="choose-presnter-top">
                        <div class="choose-presnter-title">{{__('Presenter')}}</div>
                        <a class="admin-icon" href="javascript:makepresenterLocal(<?php echo $organizer->id; ?>,true)"><i class="fa fa-user-circle-o admin-icon" aria-hidden="true"></i></a>
                        </div>
                       
                           
                     
                      </div>
                    <!--   <ul class="video-list prsenter-list-ul" id="presenter-list-ul"></ul> -->

            @endif
                  <ul class="participant-ul" id="participants">
                   
                    
                  </ul>
                </div>

                </div>
            </div>
                </div>
          </div>
        </section>
   

  



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
</div>

</main>


<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
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
 
 


 <script> var base_url = "{{asset('/')}}"; </script>

<script src="{{ asset('agora/AgoraRTCSDK-3.0.2.js') }}"></script>
<script src="{{ asset('agora/agora-rtm-sdk-1.2.2.js') }}"></script> 
<script src="{{ asset('agora/webinar.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>


<script type="text/javascript">
//alert('hekekeke');
   var time = '<?php echo $seconds_left ?>';
  var minutes = Math.floor(time / 60000);
  var seconds = ((time % 60000) / 1000).toFixed(0);
  document.getElementById('timer').innerHTML =
  minutes + ":" + seconds;
  startTimer();
   //muteunmuteremote('all','mute','mic');
function startTimer() {
  var presentTime = document.getElementById('timer').innerHTML;
  var timeArray = presentTime.split(/[:]+/);
  var m = timeArray[0];
  var s = checkSecond((timeArray[1] - 1));
  if(s==59){m=m-1}
  //if(m<0){alert('timer completed')}
  
  // document.getElementById('timer').innerHTML =
  //   m + ":" + s;
  console.log(m)
  setTimeout(startTimer, 1000);
}

function checkSecond(sec) {
  if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
  if (sec < 0) {sec = "59"};
  return sec;
}


// $( document ).ready(function() {
   
// });



</script>


