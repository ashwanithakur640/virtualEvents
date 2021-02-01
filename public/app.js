var appId = "1bc8e1d623f74353b50ca6f741e40193";
var jonee;
var organizer_id;
var translators_id;
var time_left;
var seconds_left;
var remote_controls_access;
var user_is_presenter;
var self_controls_access;
var plan_id;
var confernce_params = [];
var is_screen_shared = false;
var user;
var mic_muted_by_org = false;
var video_muted_by_org = false;
var selfleave= false;
var uid_encrypted;
var pinned_user = null;
var conference_id;
var cname;
var video_streaming_id;
var participant_count = 0;
var is_presenter_presenting = false;
var current_presenter = 0;
var share_screen_presenter = 0;
var currently_presenting = 0;
//console.log("agora sdk version: " + AgoraRTC.VERSION + " compatible: " + AgoraRTC.checkSystemRequirements());
var resolutions = [
  {
    name: 'default',
    value: 'default',
  },
  {
    name: '480p',
    value: '480p',
  },
  {
    name: '720p',
    value: '720p',
  },
  {
    name: '1080p',
    value: '1080p'
  }
];

function Toastify (options) {
  M.toast({html: options.text, classes: options.classes});
}

var Toast = {
  info: (msg) => {
    Toastify({
      text: msg,
      classes: "info-toast"
    })
  },
  notice: (msg) => {
    Toastify({
      text: msg,
      classes: "notice-toast"
    })
  },
  error: (msg) => {
    Toastify({
      text: msg,
      classes: "error-toast"
    })
  }
};
function validator(formData, fields) {
  var keys = Object.keys(formData);
  for (let key of keys) {
    if (fields.indexOf(key) != -1) {
      if (!formData[key]) {
        //Toast.error("Please Enter " + key);
        return false;
      }
    }
  }
  return true;
}

function serializeformData() {
  var formData = $("#form").serializeArray();
  var obj = {}
  for (var item of formData) {
    var key = item.name;
    var val = item.value;
    obj[key] = val;
  }
  return obj;
}

function addView (jonee, show) {
	
  if (!$("#remote_video_panel_" + jonee.id)[0]) {
    
    var class_div = '';
	if(jonee.translator || jonee.guest)
	{
	//class_div = 'hide';
  var appenTo = `<div class="testimonial-content1" id="remote_video_`+jonee.id+`">`;
	$(appenTo).appendTo('#hidden-streams');
	}
    else{
    if(remote_controls_access==1) {
      
      div = `<li id="remote_video_panel_`+jonee.id+`" class="`+class_div+`">
      <div class="col-sm-12">
      <div class="testimonial-outr">
      <div class="testimonial-content1" id="remote_video_`+jonee.id+`">
      
      <div class="organiser-img-outer-outer" id="remote_video_dp_`+jonee.id+`"  style="display:none;" >
      <div class="organiser-img-outer">
      <img src="`+jonee.image+`" alt="" >
      </div>
      </div>
      
      <div class="slider-bottom">
      <h4>  `+jonee.name+` </h4>
      </div>
      </div>
      <div class="testimonial-lower-outr">
      <a href="javascript:pinUser(`+jonee.id+`)"><i class="fa fa-thumb-tack" aria-hidden="true"></i></a>
      </div>
      </div>
      </div>
      </li>`;
	  
            $('#slider').data('flexslider').addSlide($(div));
	       
		//$('#raise-hand').append("<a href='javascript:raiseHand(`"+jonee.id+"`)'><i class='fa fa-hand-o-up' aria-hidden='true'></i></a>");
    }
    else{
      div = `<li id="remote_video_panel_`+jonee.id+`" class="`+class_div+`">
      <div class="col-sm-12">
      <div class="testimonial-outr">
      <div class="testimonial-content1" id="remote_video_`+jonee.id+`">
     
      <div class="organiser-img-outer-outer" id="remote_video_dp_`+jonee.id+`"   style="display:none;">
      <div class="organiser-img-outer">
      <img src="`+jonee.image+`" alt=""  >
      </div>
      </div>

      <div class="slider-bottom">
      <h4>  `+jonee.name+` </h4>
      </div>
      </div>
      <div class="testimonial-lower-outr">

      </div>
      </div>
      </div>
      </li>`; 
	  
            $('#slider').data('flexslider').addSlide($(div));
			  
			//$('#raise-hand').append("<a href='javascript:raiseHand(`"+jonee.id+"`)'><i class='fa fa-hand-o-up' aria-hidden='true'></i></a>");
			
    }
    }
  }
   
	   

}


function getStream(uid){

}

function addParticipant (jonee, show) {
	 
  if (!$("#participant_" + jonee.id)[0]) {
	
    if(remote_controls_access==1 && user_is_presenter != 1) {
 if(!jonee.presenter && !jonee.translator){
      var appenTo = `<li id="participant_`+jonee.id+`" data-position='-1'>
      <div class="">
         <div class="con-left">
         <img src="`+jonee.image+`" alt="" >
         </div>
         <div class="con-right">
           <div class="john-title">`+jonee.name+`</div>
           <div class="john-desc"></div>
         </div>
         <div class="con-right-right">`;

         if((jonee.plan_id!=1 && jonee.plan_id!=5) && jonee.id!=user.id){
			 let color = '';
			 let icon = ''
			 if(jonee.plan_id==3)
			 {
				 color = '#FFD700';
				 icon = base_url+'images/raise-h.png';
			 }
			 if(jonee.plan_id==4)
			 {
				 color = '#000000';
				 icon = base_url+'images/raise-b.png';
			 }
			 
          appenTo += `<a onClick="un_raise_admin(`+jonee.id+`,`+jonee.membership+`)" data-user="`+jonee.id+`" id="raise_btn_mute_`+jonee.id+`" style="display:none" title="Lower Hand"><img src="`+icon+`" class="hands-raised"></a><a style="display:none;" id="pin_user_raise_`+jonee.id+`" href="javascript:pinUser(`+jonee.id+`)"><i class="fa fa-thumb-tack" aria-hidden="true" style="color:#72b772"></i></a>
         <a class="chat_btn_mute" id="chat_btn_mute_`+jonee.id+`" href="javascript:muteunmuteremote(`+jonee.id+`,'mute','chat')"><i class="fa fa-commenting" aria-hidden="true" style="color:#0080008a"></i></a>
         <a class="chat_btn_unmute" id="chat_btn_unmute_`+jonee.id+`" href="javascript:muteunmuteremote(`+jonee.id+`,'unmute','chat')" style="display:none"><i class="fa fa-commenting" aria-hidden="true"></i></a>
  
         <a class="mic_btn_mute" id="mic_btn_mute_`+jonee.id+`" href="javascript:muteunmuteremote(`+jonee.id+`,'mute','mic')"><i class="fa fa-microphone-slash" aria-hidden="true" style="color:#0080008a"></i></a>
         <a class="mic_btn_unmute" id="mic_btn_unmute_`+jonee.id+`" href="javascript:muteunmuteremote(`+jonee.id+`,'unmute','mic')" style="display:none"><i class="fa fa-microphone-slash" aria-hidden="true"></i></a>`;
         }
         
         if(jonee.id!=user.id){
          appenTo += `<a class="video_btn_mute" id="video_btn_mute_`+jonee.id+`" href="javascript:muteunmuteremote(`+jonee.id+`,'mute','video')"><i class="fa fa-video-camera" aria-hidden="true" style="color:#0080008a"></i></a>       
         <a class="video_btn_unmute" id="video_btn_unmute_`+jonee.id+`" href="javascript:muteunmuteremote(`+jonee.id+`,'unmute','video')" style="display:none"><i class="fa fa-video-camera" aria-hidden="true"></i></a>`;
         }
		 
  
  
  
         appenTo += ` </div>
        
       </div>
  </li>`;
      $(appenTo).appendTo('#participants');
 }
 else if(jonee.translator)
 {
if(!$("#flags-ul #participant_translator_"+jonee.id).length)
         {   
 $(`<li id="participant_translator_`+jonee.id+`" class="translators-li `+jonee.translator_belongs+`">
	  <a><img src="`+base_url+'images/flag'+jonee.translator_flag+'.jpg'+`"></a>
  </li>`).appendTo('#flags-ul');

      if($('#now_presenting').val()==0 || $('#now_presenting').val()=='')
      {
        $('#flags-ul li').hide();
        //alert(uid);
        $('.'+organizer_id).show();
      }
      else{
        $('#flags-ul li').hide();
        //alert(uid);
        $('.'+$('#now_presenting').val()).show();
      }
     }	 
	 
 }
 else{ 
 
	var optionExists = ($('#presenter-list option[value=' +jonee.id+ ']').length > 0);
if(!optionExists)
{	
if(jonee.id==$('#now_presenting').val() && $('#now_presenting').val()!=0)
{
	$('#presenter-list')
         .append($("<option></option>")
                    .attr("value", jonee.id)
					.attr("selected",'selected')
                    .text(jonee.name+':'+jonee.email)); 
}
else{
$('#presenter-list')
         .append($("<option></option>")
                    .attr("value", jonee.id)
                    .text(jonee.name+':'+jonee.email)); 	
}
/* changed to list */
var appenTo = `<li id="participant_`+jonee.id+`" data-position='-1'>
      <div class="presenter-outer">
         <div class="con-left">
         <img src="`+jonee.image+`" alt="" >
         </div>
         <div class="con-right">
           <div class="john-title">`+jonee.name+`</div>
           <div class="john-desc"></div>
         </div>
         <div class="con-right-right">`;

         if((jonee.plan_id!=1 && jonee.plan_id!=5) && jonee.id!=user.id){
			 let color = '';
			 let icon = ''
			 if(jonee.plan_id==3)
			 {
				 color = '#FFD700';
				 icon = base_url+'images/raise-h.png';
			 }
			 if(jonee.plan_id==4)
			 {
				 color = '#000000';
				 icon = base_url+'images/raise-b.png';
			 }
			 
          appenTo += `<a class="presenter" id="make_presenter_`+jonee.id+`" href="javascript:makepresenter(`+jonee.id+`,'true')"><i class="fa fa-television admin-icon" aria-hidden="true"></i></a><a onClick="un_raise_admin(`+jonee.id+`,`+jonee.membership+`)" data-user="`+jonee.id+`" id="raise_btn_mute_`+jonee.id+`" style="display:none" title="Lower Hand"><img src="`+icon+`" class="hands-raised"></a><a style="display:none;" id="pin_user_raise_`+jonee.id+`" href="javascript:pinUser(`+jonee.id+`)"><i class="fa fa-thumb-tack" aria-hidden="true" style="color:#72b772"></i></a>
         <a class="chat_btn_mute" id="chat_btn_mute_`+jonee.id+`" href="javascript:muteunmuteremote(`+jonee.id+`,'mute','chat')"><i class="fa fa-commenting" aria-hidden="true" style="color:#0080008a"></i></a>
         <a class="chat_btn_unmute" id="chat_btn_unmute_`+jonee.id+`" href="javascript:muteunmuteremote(`+jonee.id+`,'unmute','chat')" style="display:none"><i class="fa fa-commenting" aria-hidden="true"></i></a>
  
         <a class="mic_btn_mute" id="mic_btn_mute_`+jonee.id+`" href="javascript:muteunmuteremote(`+jonee.id+`,'mute','mic')"><i class="fa fa-microphone-slash" aria-hidden="true" style="color:#0080008a"></i></a>
         <a class="mic_btn_unmute" id="mic_btn_unmute_`+jonee.id+`" href="javascript:muteunmuteremote(`+jonee.id+`,'unmute','mic')" style="display:none"><i class="fa fa-microphone-slash" aria-hidden="true"></i></a>
         `;
        }
         
         if(jonee.id!=user.id){
          appenTo += `<a class="video_btn_mute" id="video_btn_mute_`+jonee.id+`" href="javascript:muteunmuteremote(`+jonee.id+`,'mute','video')"><i class="fa fa-video-camera" aria-hidden="true" style="color:#0080008a"></i></a>       
         <a class="video_btn_unmute" id="video_btn_unmute_`+jonee.id+`" href="javascript:muteunmuteremote(`+jonee.id+`,'unmute','video')" style="display:none"><i class="fa fa-video-camera" aria-hidden="true"></i></a>`;
         }
		 
  
  
  
         appenTo += ` </div>
        
       </div>
  </li>`;

      $(appenTo).appendTo('#presenter-list-ul');

/* End changed to list*/


}

}

     
    }
	else if(user_is_presenter == 1)
	{
if(!jonee.translator){
	
      var appenTo = `<li id="participant_`+jonee.id+`" data-position='-1'>
      <div class="">
         <div class="con-left">
         <img src="`+jonee.image+`" alt="" >
         </div>
         <div class="con-right">
           <div class="john-title">`+jonee.name+`</div>
           <div class="john-desc"></div>
         </div>`;

		 
		 if($('#my_user_id').val()==$('#now_presenting').val())
		 {
			appenTo += `<div class="con-right-right corner-right-presenter current_user_presenter_`+$('#my_user_id').val()+`">`; 
		 }
		 else{
			 
			appenTo += `<div class="con-right-right corner-right-presenter current_user_presenter_`+$('#my_user_id').val()+`" style="display:none">`; 
		 }
         if((jonee.plan_id!=1 && jonee.plan_id!=5) && jonee.id!=user.id){
			 let color = '';
			 let icon = ''
			 if(jonee.plan_id==3)
			 {
				 color = '#FFD700';
				 icon = base_url+'images/raise-h.png';
			 }
			 if(jonee.plan_id==4)
			 {
				 color = '#000000';
				 icon = base_url+'images/raise-b.png';
			 }
			 
          appenTo += `<a onClick="un_raise_admin(`+jonee.id+`,`+jonee.membership+`)" data-user="`+jonee.id+`" id="raise_btn_mute_`+jonee.id+`" style="display:none" title="Lower Hand"><img src="`+icon+`" class="hands-raised"></a><a style="display:none;" id="pin_user_raise_`+jonee.id+`" href="javascript:pinUser(`+jonee.id+`)"><i class="fa fa-thumb-tack" aria-hidden="true" style="color:#72b772"></i></a>`;
         }
         
  
  
         appenTo += ` </div>
        
       </div>
  </li>`;
      $(appenTo).appendTo('#participants');

}
else{
		if(!$("#flags-ul #participant_translator_"+jonee.id).length)
         {   
 $(`<li id="participant_translator_`+jonee.id+`" class="translators-li `+jonee.translator_belongs+`">
	  <a onclick="switchLanguage(`+jonee.id+`)"><img src="`+base_url+'images/flag'+jonee.translator_flag+'.jpg'+`"></a>
  </li>`).appendTo('#flags-ul');

  if($('#now_presenting').val()==0 || $('#now_presenting').val()=='')
  {
    $('#flags-ul li').hide();
    //alert(uid);
    $('.'+organizer_id).show();
  }
  else{
    $('#flags-ul li').hide();
    //alert(uid);
    $('.'+$('#now_presenting').val()).show();
  }

     }
	   }
		
	}
    else{
		if(!jonee.translator){
      $(`<li id="participant_`+jonee.id+`">
      <div class="">
         <div class="con-left">
         <img src="`+jonee.image+`" alt="" >
         </div>
         <div class="con-right">
           <div class="john-title">`+jonee.name+`</div>
           <div class="john-desc"></div>
         </div>
         <div class="con-right-right">
    
  
  
  
       </div>
        
       </div>
  </li>`).appendTo('#participants');
		}
       else{
		if(!$("#flags-ul #participant_translator_"+jonee.id).length)
         {   
 $(`<li id="participant_translator_`+jonee.id+`" class="translators-li `+jonee.translator_belongs+`">
	  <a onclick="switchLanguage(`+jonee.id+`)"><img src="`+base_url+'images/flag'+jonee.translator_flag+'.jpg'+`"></a>
  </li>`).appendTo('#flags-ul');
  if($('#now_presenting').val()==0 || $('#now_presenting').val()=='')
  {
    $('#flags-ul li').hide();
    //alert(uid);
    $('.'+organizer_id).show();
  }
  else{
    $('#flags-ul li').hide();
    //alert(uid);
    $('.'+$('#now_presenting').val()).show();
  }
     }
	   }	   
		
    }
  

    getParticipantsCount();
	
}
}

$(document).on('change','#presenter-list',function() {
	
	if($('#uid_sharescreen').val())
	{
		
    let user_id = screenStream.getId().slice(1);
  
	 if(user_id==organizer_id)
	 {
		 alert('Please disable the screen sharing icon first.');
		 $("#presenter-list").prop("selectedIndex", 0);
		 return ;
	 }

	}

  if(this.value!='' && this.value!='Select Presenter')
  {
	  
makepresenter(this.value,true);	  
  }
});

function removeView (id) {
  // if ($("#remote_video_panel_" + id)[0]) {
  //   $("#remote_video_panel_"+id).remove();
  // }
  $("#remote_video_panel_"+id).remove();
}

function getDevices (next) {
  AgoraRTC.getDevices(function (items) {
    items.filter(function (item) {
      return ['audioinput', 'videoinput'].indexOf(item.kind) !== -1
    })
    .map(function (item) {
      return {
      name: item.label,
      value: item.deviceId,
      kind: item.kind,
      }
    });
    var videos = [];
    var audios = [];
    for (var i = 0; i < items.length; i++) {
      var item = items[i];
      if ('videoinput' == item.kind) {
        var name = item.label;
        var value = item.deviceId;
        if (!name) {
          name = "camera-" + videos.length;
        }
        videos.push({
          name: name,
          value: value,
          kind: item.kind
        });
      }
      if ('audioinput' == item.kind) {
        var name = item.label;
        var value = item.deviceId;
        if (!name) {
          name = "microphone-" + audios.length;
        }
        audios.push({
          name: name,
          value: value,
          kind: item.kind
        });
      }
    }
    next({videos: videos, audios: audios});
  });
}

var rtc = {
  client: null,
  joined: false,
  published: false,
  localStream: null,
  remoteStreams: [],
  params: {}
};

function handleEvents (rtc) {
  // Occurs when an error message is reported and requires error handling.
  rtc.client.on("error", (err) => {
    //console.log(err)
  })
  // Occurs when the peer user leaves the channel; for example, the peer user calls Client.leave.
  rtc.client.on("peer-leave", function (evt) {
    var id = evt.uid;

    // if(organizer_id==id){
    //   leave(rtc);
    // }
    if(evt.uid==organizer_id){
      $("#local_stream").html('');
        rtc.localStream.play("local_stream");
        $("#local_stream").addClass('video-not-available');
    $("#organizer_status").html(`<span id="organizer_status_offline">Organiser Is offline. <a href="/give-rating/"`+uid_encrypted+` style="color:red;">LEAVE</a></span> `);
    var organizer_status = setInterval(function() {
      document.getElementById('organizer_status_offline').style.display = ( document.getElementById('organizer_status_offline').style.display == 'none' ? '' : 'none');
  }, 1000);
    }

    removeView(id);
    $("#remote_video_panel_"+id).remove();
	$('#participant_translator_'+id).remove();
  $("#presenter-list option[value="+id+"]").remove();
  $('#make_presenter_'+id).remove();
	switchLanguage(organizer_id,true);
	if(currently_presenting==id && currently_presenting!=0)
	{
	$("#local_stream_container").prepend($("#local_stream"));
     $(".organiser-img-outer").show();	
	}
    //console.log("id", evt);
    if (id != rtc.params.uid) {
      
      $("#participant_"+id).remove();

      getParticipantsCount();
    }
    //Toast.notice("peer leave")
    //console.log('peer-leave', id);
  })
  // Occurs when the local stream is published.
  rtc.client.on("stream-published", function (evt) {
    //Toast.notice("stream published success")
    //console.log("stream-published");
  })

  rtc.client.on('peer-online', function(evt) {
    //console.log('peer-online', evt.uid);
    //Toast.notice("user online "+evt.uid);
    if(evt.uid==organizer_id){
      clearInterval(organizer_status);
      $("#organizer_status").html('');
      
      $("#message").html('');
    }
	
    
  });

  // Occurs when the remote stream is added.
  rtc.client.on("stream-added", function (evt) {  
    var remoteStream = evt.stream;
    var id = remoteStream.getId();

    //Toast.info("stream-added uid: " + id)
    if (id !== rtc.params.uid) {
      rtc.client.subscribe(remoteStream, function (err) {
        //console.log("stream subscribe failed", err);
      })
    }
    //console.log('stream-added remote-uid: ', id);
  });
  // Occurs when a user subscribes to a remote stream.
  rtc.client.on("stream-subscribed", function (evt) {
	 
    var remoteStream = evt.stream;
    //console.log(remoteStream,"=====remoteStream")
    var id = remoteStream.getId();
	//alert(id);
	//remoteStream.muteAudio();
    rtc.remoteStreams.push(remoteStream);
	 
      var conf_id = $('#conference_id').val();
      if($('#uid_sharescreen').val() !='')
      {
        $('#share_screen_id').val(id);
      }
    $.ajax({url:  base_url+"getUser/"+id+"?conf_id="+conf_id, success: function(result){
      jonee = JSON.parse(result);
      //console.log(jonee,"====joinee name");
      if(jonee.image=='' || jonee.image==null) jonee.image = base_url + `images/client.jpg`;
    else{
      jonee.image = base_url + `storage/profile-images/`+jonee.image
    } 

      if(organizer_id==id){
        $("#local_stream").html('');
        $("#local_stream").removeClass('video-not-available');
        remoteStream.play("local_stream");
      }
      else{
		  // addView(jonee);
		
		addView(jonee);
		remoteStream.play("remote_video_" + id);
	 
      }

      if(jonee.translator || jonee.guest)
      {
        remoteStream.muteAudio();
      remoteStream.muteVideo();
      }
      addParticipant(jonee);
	  console.log(jonee);
      //Toast.info('stream-subscribed remote-uid: ' + id);
      //console.log('stream-subscribed remote-uid: ', id);
	  
	 // allowOrganizerLanguage();
    if(jonee.current_presenter != 0)
	{
	
     makepresenterLocal(jonee.current_presenter);

	}
    }});

  



    
  })
  // Occurs when the remote stream is removed; for example, a peer user calls Client.unpublish.
  rtc.client.on("stream-removed", function (evt) {
    var remoteStream = evt.stream;
    var id = remoteStream.getId();


    //Toast.info("stream-removed uid: " + id)
    remoteStream.stop("remote_video_" + id);
    rtc.remoteStreams = rtc.remoteStreams.filter(function (stream) {
      return stream.getId() !== id
    })
    removeView(id);
    //console.log('stream-removed remote-uid: ', id);
  })
  rtc.client.on("onTokenPrivilegeWillExpire", function(){
    // After requesting a new token
    // rtc.client.renewToken(token);
    //Toast.info("onTokenPrivilegeWillExpire")
    //console.log("onTokenPrivilegeWillExpire")
  });
  rtc.client.on("onTokenPrivilegeDidExpire", function(){
    // After requesting a new token
    // client.renewToken(token);
    //Toast.info("onTokenPrivilegeDidExpire")
    //console.log("onTokenPrivilegeDidExpire")
  })

  rtc.client.on("mute-audio", function(evt){
    var stream = evt.stream;
    var id = evt.uid;
    $('#mic_btn_mute_'+id).hide();
    $('#mic_btn_unmute_'+id).show();
    //console.log('#mic_btn_mute_'+id);
    // Mutes the remote stream.
    //stream.muteAudio();
    //Toast.info("mute-audio")
    //console.log("mute-audio")
  })

  rtc.client.on("unmute-audio", function(evt){
    var stream = evt.stream;
    var id = evt.uid;
    $('#mic_btn_mute_'+id).show();
    $('#mic_btn_unmute_'+id).hide();
    // Mutes the remote stream.
    //stream.unmuteAudio();
    //Toast.info("unmute-audio")
    //console.log("unmute-audio")
  })

  rtc.client.on("mute-video", function(evt){
    var stream = evt.stream;
    var id = evt.uid;

    //console.log('#video_btn_mute_'+id);
    $('#video_btn_mute_'+id).hide();
    $('#video_btn_unmute_'+id).show();
    $('#remote_video_dp_'+id).show();
    
    
    // Mutes the remote stream.
    
    //stream.muteVideo();
    //Toast.info("mute-video-")
    //console.log("mute-video-"+id)
  })

  rtc.client.on("unmute-video", function(evt){
    var stream = evt.stream;
    var id = evt.uid;
    $('#video_btn_mute_'+id).show();
    $('#video_btn_unmute_'+id).hide();
    $('#remote_video_dp_'+id).hide();
    // Mutes the remote stream.
    //stream.unmuteVideo();
    //Toast.info("unmute-video")
    //console.log("unmute-video")
  })
  
}

/**
  * rtc: rtc object
  * option: {
  *  mode: string, 'live' | 'rtc'
  *  codec: string, 'h264' | 'vp8'
  *  appID: string
  *  channel: string, channel name
  *  uid: number
  *  token; string,
  * }
 **/

 function getParticipantsCount(){
  var element_participants = document.getElementById("participants");
  var participant_count = element_participants.children.length;
      $("#participant_count").html(participant_count);
 }
function join (rtc, option) {
  $(".slide1").css('visibility','hidden');
  if (rtc.joined) {
    //Toast.error("Your already joined");
    return;
  }

  /**
   * A class defining the properties of the config parameter in the createClient method.
   * Note:
   *    Ensure that you do not leave mode and codec as empty.
   *    Ensure that you set these properties before calling Client.join.
   *  You could find more detail here. https://docs.agora.io/en/Video/API%20Reference/web/interfaces/agorartc.clientconfig.html
  **/
  rtc.client = AgoraRTC.createClient({mode: option.mode, codec: option.codec});

  rtc.params = option;

  // handle AgoraRTC client event
  handleEvents(rtc);

  // init client
  rtc.client.init(option.appID, function () {
    //console.log("init success");

    /**
     * Joins an AgoraRTC Channel
     * This method joins an AgoraRTC channel.
     * Parameters
     * tokenOrKey: string | null
     *    Low security requirements: Pass null as the parameter value.
     *    High security requirements: Pass the string of the Token or Channel Key as the parameter value. See Use Security Keys for details.
     *  channel: string
     *    A string that provides a unique channel name for the Agora session. The length must be within 64 bytes. Supported character scopes:
     *    26 lowercase English letters a-z
     *    26 uppercase English letters A-Z
     *    10 numbers 0-9
     *    Space
     *    "!", "#", "$", "%", "&", "(", ")", "+", "-", ":", ";", "<", "=", ".", ">", "?", "@", "[", "]", "^", "_", "{", "}", "|", "~", ","
     *  uid: number | null
     *    The user ID, an integer. Ensure this ID is unique. If you set the uid to null, the server assigns one and returns it in the onSuccess callback.
     *   Note:
     *      All users in the same channel should have the same type (number or string) of uid.
     *      If you use a number as the user ID, it should be a 32-bit unsigned integer with a value ranging from 0 to (232-1).
    **/
    rtc.client.join(option.token ? option.token : null, option.channel, option.uid ? +option.uid : null, function (uid) {
      //Toast.notice("join channel: " + option.channel + " success, uid: " + uid);
      //console.log("join channel: " + option.channel + " success, uid: " + uid);
      rtc.joined = true;

      rtc.params.uid = uid;

      if(plan_id==5){
      var audio_mode = false;
      var video_mode = true;
      }
      else {var audio_mode = true; var video_mode = true;}

      // create local stream
      rtc.localStream = AgoraRTC.createStream({
        streamID: rtc.params.uid,
        audio: audio_mode,
        video: video_mode,
        screen: false,
        microphoneId: option.microphoneId,
        cameraId: option.cameraId
      })

      // init local stream
      rtc.localStream.init(function () {
        //console.log("init local stream success");
        // play stream with html element id "local_stream"

        //console.log(organizer_id+"=organiser="+uid);
        if(organizer_id==uid){
        $("#local_stream").html('');
        rtc.localStream.play("local_stream");
        }
        else{
			
          addView(user);
          rtc.localStream.play("remote_video_" + uid);
        }
        addParticipant(user);
        

        // publish local stream
        publish(rtc);

        if(plan_id==5){
          $('#mute-audio').click();
         $('#mute-video').click();
         $('#unmute-video').hide();
          //muteunmuteremote(uid, 'mute', 'chat');
        }

/* stream mute if organizer mute this */
if($('#remote-mic').val()=='1' && $('#my_user_id').val() !=$('#organizer_id').val() && $('#my_user_id').val() !=$('#now_presenting').val() && !JSON.parse($("#translators_id").val()).includes(parseInt($('#my_user_id').val())))
{
 
  setTimeout(function(){ $('#mute-audio').click()}, 1000);
   mic_muted_by_org = true;

}
if($('#remote-camera').val()=='1' && $('#my_user_id').val() !=$('#organizer_id').val() && $('#my_user_id').val() !=$('#now_presenting').val())
{
  
  setTimeout(function(){ $('#mute-video').click();}, 1000);
  video_muted_by_org = true;
}
if($('#remote-chat').val()=='1' && $('#my_user_id').val() !=$('#organizer_id').val() && $('#my_user_id').val() !=$('#now_presenting').val())
{
  
  setTimeout(function(){ $('#chat_control').hide();}, 1000);
}
   
        /* end stream mute if organizer mute this */
        

      }, function (err)  {
        //Toast.error("stream init failed, please open console see more detail")
        if(err.msg=="NotFoundError")
        {
        //addParticipant(user);
        $('.body-conference-call').hide();
        
        setTimeout(function(){ alert('Make sure your camera and mic are working and you give permssions to use those'); window.location.href='/home' }, 1000);
        }
        if(err.msg=="NotAllowedError")
        {
          $('.body-conference-call').hide();
          alert('Make sure your camera and mic are working and you give permssions to use those');
          setTimeout(function(){ alert('Make sure your camera and mic are working and you give permssions to use those'); window.location.href='/home' }, 1000);
        }
        console.error("init local stream failed ", err);
      })
    }, function(err) {
      //Toast.error("client join failed, please open console see more detail")
      console.error("client join failed", err)
    })
  }, (err) => {
    //Toast.error("client init failed, please open console see more detail")
    console.error(err);
  });
}

function publish (rtc) {
  if (!rtc.client) {
    //Toast.error("Please Join Room First");
    return;
  }
  if (rtc.published) {
    //Toast.error("Your already published");
    return;
  }
  var oldState = rtc.published;

  // publish localStream
  rtc.client.publish(rtc.localStream, function (err) {
    rtc.published = oldState;
    //console.log("publish failed");
    //Toast.error("publish failed")
    console.error(err);
  })
  //Toast.info("publish")
  rtc.published = true
}

function unpublish (rtc) {
  if (!rtc.client) {
    //Toast.error("Please Join Room First");
    return;
  }
  if (!rtc.published) {
    //Toast.error("Your didn't publish");
    return;
  }
  var oldState = rtc.published;
  rtc.client.unpublish(rtc.localStream, function (err) {
    rtc.published = oldState;
    //console.log("unpublish failed");
    //Toast.error("unpublish failed");
    console.error(err);
  })
  //Toast.info("unpublish")
  rtc.published = false;
}

function leave (rtc) {
  if (!rtc.client) {
    //Toast.error("Please Join First!");
    return;
  }
  if (!rtc.joined) {
    //Toast.error("You are not in channel");
    return;
  }
  /**
   * Leaves an AgoraRTC Channel
   * This method enables a user to leave a channel.
   **/
  rtc.client.leave(function () {
    // stop stream
    rtc.localStream.stop();
    // close stream
    rtc.localStream.close();
    while (rtc.remoteStreams.length > 0) {
      var stream = rtc.remoteStreams.shift();
      var id = stream.getId();
      stream.stop();
      removeView(id);
    }
    rtc.localStream = null;
    rtc.remoteStreams = [];
    rtc.client = null;
    //console.log("client leaves channel success");
    rtc.published = false;
    rtc.joined = false;
    //Toast.notice("leave success");
  }, function (err) {
    //console.log("channel leave failed");
    //Toast.error("leave success");
    console.error(err);
  })
  //window.location.href="/give-rating/"+appId+"/";
}


function muteaudio (rtc) {
  if (!rtc.client) {
    //Toast.error("Please Join Room First");
    return;
  }
  rtc.localStream.muteAudio();

    $("#mute-audio").hide();
    $("#unmute-audio").show();
  //Toast.notice("muted"); 
}


function unmuteaudio (rtc) {
  if (!rtc.client) {
    //Toast.error("Please Join Room First");
    return;
  }
  rtc.localStream.unmuteAudio();

    $("#mute-audio").show();
    $("#unmute-audio").hide();
  //Toast.notice("unmuted"); 
}



function mutevideo (rtc) {
  if (!rtc.client) {
    //Toast.error("Please Join Room First");
    return;
  }

    rtc.localStream.muteVideo();
    $("#mute-video").hide();
    $("#unmute-video").show();

    if(organizer_id!=user.id){
    $('#remote_video_dp_'+user.id).show();
    }
  //Toast.notice("muted"); 
}


function unmutevideo (rtc) {
  if (!rtc.client) {
    //Toast.error("Please Join Room First");
    return;
  }

  rtc.localStream.unmuteVideo();

    $("#mute-video").show();
    $("#unmute-video").hide();

    if(organizer_id!=user.id){
      $('#remote_video_dp_'+user.id).hide();
      }
  //Toast.notice("unmuted"); 
}

function startrecording(refresh=false) {
  var message = new Object();
  message.type = 'startrecording';


  $("#start-recording").hide();
  $("#start-recording-wait").show();
   let screen_share_id = $('#uid_sharescreen').val();
    $.ajax({url:  base_url+"cloud-recording-acquire?cname="+cname+"&conference_id="+conference_id+"&screen_id="+screen_share_id, 
   success: function(result){
    //console.log(result+'======startrecording1');
     
     var result_arr = JSON.parse(result);
     video_streaming_id = result_arr.video_streaming_id;
    //console.log(result_arr.video_streaming_id+'======video_streaming_id');
    //console.log(result.video_streaming_id+'======video_streaming_id2');
	if(!refresh)
	{
    startrecordings();
    if($('#uid_sharescreen').val() !='')
    {
      setTimeout(function(){  updateRecordingLayout($('#share_screen_id').val(),'1'); }, 1000);
     
    }

    var msg = JSON.stringify(message);
    RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
      //console.log(msg+'======startrecording1');
   recordingLog(organizer_id,'1');
      }).catch(error => {
       //console.log(error+'======2');
      });    

	}
	else
	{
	$("#start-recording-wait").hide();
    $("#stop-recording").show();	
	}
  },
  error: function(err){

    $("#start-recording").show();
  }
});

  
}


function startrecordingrefresh() {
  
  $("#start-recording").hide();
  //$("#start-recording-wait").show();
   let screen_share_id = $('#uid_sharescreen').val();
    $.ajax({url:  base_url+"cloud-recording-acquire?cname="+cname+"&conference_id="+conference_id+"&screen_id="+screen_share_id, 
   success: function(result){
    //console.log(result+'======startrecording1');
     
     var result_arr = JSON.parse(result);
     video_streaming_id = result_arr.video_streaming_id;
     $("#start-recording-wait").hide();
  $("#stop-recording").show();

  },
  error: function(err){

    $("#start-recording").show();
  }
});

  
}

function startrecordings() {
  $('.recording-user').show();
  var audio = new Audio(base_url+'/agora/AUD-20200521-WA0024.mp3');
  audio.play();
  //console.log('======3');
  $("#start-recording-wait").hide();
  $("#stop-recording").show();
}
function handraised() {
var audio_play = new Audio(base_url+'/agora/beep-message.mp3');
audio_play.play();
}
function stoprecording() {
  
  $("#stop-recording-wait").show();
  $("#stop-recording").hide();
  $.ajax({url:  base_url+"cloud-recording-stop?video_streaming_id="+video_streaming_id, 
  success: function(result){
   //console.log(result+'======startrecording1');
    
    //var result_arr = JSON.parse(result);
   
    //console.log(result_arr+'======result_arr');


   var message = new Object();
  message.type = 'stoprecording';

  var msg = JSON.stringify(message);
  RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
    ////console.log('organizerLeft===client');
 recordingLog(organizer_id,'0');
    }).catch(error => {
     // //console.log('organizerLeft error==client');
    });
   stoprecordings() 


 },
 error: function(err){


 }
});

  
}

function stoprecordingRefresh() {

  $.ajax({url:  base_url+"cloud-recording-stop-refresh/"+conference_id, 
  success: function(result){
   


 },
 error: function(err){


 }
});

  
}
function stoprecordings() {
  $(".recording-user").hide();
  $("#stop-recording-wait").hide();
  $("#start-recording").show();
 
}


$(function () {
    user = $("#user").val();
    user = JSON.parse(user);
    if(user.image=='' || user.image==null) user.image = base_url + `images/client.jpg`;
    else{
      user.image = base_url + `storage/profile-images/`+user.image
    }
    //console.log(user.name,"====user");
    $("#uid").val(user.id);
    organizer_id = $("#organizer_id").val();
	translators_id = $("#translators_id").val();
    time_left = $("#time_left").val();
    seconds_left = $("#seconds_left").val();
    remote_controls_access = $("#remote_controls_access").val();
	user_is_presenter = $("#user_is_presenter").val();
    self_controls_access = $("#self_controls_access").val();
    plan_id = $("#plan_id").val();
    conference_id = $("#conference_id").val();
    currently_presenting = $("#now_presenting").val();
    uid_encrypted = $("#uid_encrypted").val();
    cname = $("#channel").val();


    
    
  getDevices(async function (devices) {
    //console.log("===getDevices");
    devices.audios.forEach(function (audio) {
      $('<option/>', {
        value: audio.value,
        text: audio.name,
      }).appendTo("#microphoneId");
    })
    devices.videos.forEach(function (video) {
      $('<option/>', {
        value: video.value,
        text: video.name,
      }).appendTo("#cameraId");
    })
    resolutions.forEach(function (resolution) {
      $('<option/>', {
        value: resolution.value,
        text: resolution.name
      }).appendTo("#cameraResolution");
    })
    //M.AutoInit();

    
    confernce_params['appID'] = appId;
    confernce_params['cameraId'] = $("#cameraId").val();
    confernce_params['cameraResolution'] = "4K_3";
    confernce_params['channel'] = $("#channel").val();
    confernce_params['codec'] = "h264";
    confernce_params['microphoneId'] = $("#microphoneId").val();
    confernce_params['mode'] = "live";
    confernce_params['uid'] = user.id;

    var params = serializeformData();
    //console.log(confernce_params,"===confernce_params");
    if (validator(confernce_params, fields)) {

      if(time_left==0){
        join(rtc, confernce_params);
        logs();
        joinscreen();
        joinrtm();

        //console.log(seconds_left,"==seconds_left");
        setTimeout(function(){ 
        

          $("#message").html(`<span style="color:red">Conference Has Been Ended.</span>`);
          setTimeout(function(){ 
            selfleave = true;
           
            window.location.href="/give-rating/"+uid_encrypted;
          }, 4000);
        }, seconds_left);
         
      }
      else if(time_left==-1){
        $("#message").html(`Conference Has Been Ended. <a href="/give-rating/`+uid_encrypted+`">LEAVE</a>`);
        
      }
      else {
        //$("#message").html('Conference will start at '+time_left);
        if(!alert('Conference will start on '+time_left)) document.location = '/my-conferences';
          //time_left = new Date(time_left* 1000);
          // setInterval(function() {
            
          //  time_left.setSeconds(time_left.getSeconds() - 1);
            
          // }, 1000);
        

      }
      
    }

  })

  
    

  var fields = ['appID', 'channel'];

  $("#join").on("click", function (e) {

    //console.log("join")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      join(rtc, params);
    }
  })


  $("#joinscreen").on("click", function (e) {

    //console.log("joinscreen")
    e.preventDefault();
    joinscreen();
  })

  $("#publish").on("click", function (e) {
    //console.log("publish")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      publish(rtc);
    }
  });

  $("#unpublish").on("click", function (e) {
    //console.log("unpublish")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      unpublish(rtc);
    }
  });

  $("#leave").on("click", function (e) {
    // //console.log("leave")
    e.preventDefault();
    // var params = serializeformData();
    // if (validator(params, fields)) {
    //   leave(rtc);
      
    // }
     if(organizer_id==user.id){
		 $.confirm({
    title: 'End this conference!',
    content: 'Are you sure to end this conference?',
    buttons: {
        confirm: function () {
        organizerLeft();
window.location.href="/give-rating/"+uid_encrypted;		
        },
        cancel: function () {
          // return ;
        }
        }
          });
		 
     /* var r = confirm("Are you sure to end this conference?");
      if (r == true) {
      organizerLeft()
      }
      else{
      return ;
      }*/
    }
	else{
		$.confirm({
    title: 'Want to leave!',
    content: 'Are you sure to leave this conference?',
    buttons: {
        confirm: function () {
     selfleave = true;
     if($('#now_presenting').val()==user.id){
      makepresenter(organizer_id);
     }
    window.location.href="/give-rating/"+uid_encrypted;
        },
        cancel: function () {
          // return ;
        }
        }
          });
   
	}
  });


  $("#pin").on("click", function (e) {

    //console.log("#mute-audio")
    e.preventDefault();
    pinSelf(user.id);
  })

  $("#mute-audio").on("click", function (e) {

    //console.log("#mute-audio")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      muteaudio(rtc);
    }
  })


  $("#unmute-audio").on("click", function (e) {
	  if(JSON.parse($("#translators_id").val()).includes($(this).data('userid')) || $(this).data('userid')==$('#now_presenting').val() || JSON.parse($("#translators_id").val()).includes(parseInt($('#my_user_id').val())))
	  {
		 e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      unmuteaudio(rtc);
    }  
		  
	  }
	  else {
    if(mic_muted_by_org==false){
    ////console.log("#unmute-audio")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      unmuteaudio(rtc);
    }
  }
	  }
  })

  $("#mute-video").on("click", function (e) {
    ////console.log("#mute-video")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      mutevideo(rtc);
    }
  })


  $("#unmute-video").on("click", function (e) {
    if(video_muted_by_org==false || $('#my_user_id').val() == $('#now_presenting').val() || JSON.parse($("#translators_id").val()).includes(parseInt($('#my_user_id').val()))){
    ////console.log("#unmute-video")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      unmutevideo(rtc);
    }
  }
  })


  $("#start-recording").on("click", function (e) {
    e.preventDefault();
    //if(is_screen_shared !== true)
    startrecording();
  });

  $("#stop-recording").on("click", function (e) {
    e.preventDefault();
    //if(is_screen_shared !== true)
    stoprecording();
  });


  $("#sharescreen").on("click", function (e) {

    ////console.log("sharescreen")
    e.preventDefault();
	//alert($('#now_presenting').val());
	if(organizer_id == $('#my_user_id').val())
	{
     if($('#now_presenting').val() !=0)
	 {
		alert('someone else is presenting the screen currently please take access back to present');
      return  ;		
	 }
	}
    if(is_screen_shared !== true)
    sharescreen($(this).data('id'));
    share_screen_presenter = $(this).data('id');
   // else //console.log("screen already shared");
  });


  $("#sharescreenclose").on("click", function (e) {

   
    e.preventDefault();
    sharescreenclose();
	share_screen_presenter = 0;
  });

  $("#send_rtm_channel_message").on("click", function (e) {
    e.preventDefault();
    if (!RTMClient._logined) {
      //Toast.error("Please Login First");
      return;
    }
    sendRTMChannelMessage();
  });

$("#rtm_channel_message").keypress(function(e) {
	
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code == 13 && !e.shiftKey) { //Enter keycode
	e.preventDefault();
	if (!RTMClient._logined) {
      //Toast.error("Please Login First");
      return;
    }
    sendRTMChannelMessage();
       
    }
});

})




var localStreams = [];

var screenClient = AgoraRTC.createClient({
    mode: 'rtc',
    codec: 'vp8'
});
var ss_uid;
var screenStream;

function joinscreen(){

AgoraRTC.Logger.setLogLevel(AgoraRTC.Logger.INFO);


screenClient.init(confernce_params['appID'], function() {
    screenClient.join(null, confernce_params['channel'], "1"+confernce_params['uid'], function(uid) {
        // Save the uid of the local stream.
      
        localStreams.push(confernce_params['uid']);
        ss_uid = uid;
        console.log('==ss_uid', ss_uid);
     
        screenClient.on('peer-online', function(evt) {
          ////console.log('sspeer-online', evt.uid);
          //Toast.notice("ssuser online "+evt.uid);
          
        });

        screenClient.on('stream-added', function(evt) {
          var stream = evt.stream;
          var uid = stream.getId()
          $('#share-screen-id').val(uid);
         // //console.log("=========error3")
          // Check if the stream is a local uid.
          if (!localStreams.includes(uid)) {
             // //console.log('subscribe stream:' + uid);
              // Subscribe to the stream.
              screenClient.subscribe(stream);
          }
      });
  
      // Occurs when a user subscribes to a remote stream.
      screenClient.on("stream-subscribed", function (evt) {
        var remoteStream = evt.stream;
		let user_id = evt.stream.getId().slice(1);
		
        if(user_id == organizer_id)
		{
        $("#organiser-img").prepend($("#local_stream"));
        $(".organiser-img-outer").hide();
        
        $("#local_stream_screen").addClass('share-img-screen1');
      //$("#local_stream_screen").css("height","450px");
         remoteStream.play('local_stream_screen');
		}
		else{
		 $("#organiser-img").prepend($("#local_stream"));
        $(".organiser-img-outer").hide();
        $('#share-screen-out').prepend($("#remote_video_panel_"+user_id));
		$("#remote_video_panel_"+user_id).find('.testimonial-lower-outr').addClass('no-clickable');
        $("#local_stream_screen").addClass('share-img-screen1');
      //$("#local_stream_screen").css("height","450px");
         remoteStream.play('local_stream_screen');	
			
		}
      is_screen_shared = true;
      
      
    })

    screenClient.on("stream-removed", function (evt) {
		 $('#uid_sharescreen').val('');
      var remoteStream = evt.stream;
     // //console.log("=========error5");
      $("#local_stream_screen").html("");
	   let user_id = evt.stream.getId().slice(1);
	  if(user_id == organizer_id)
		{
	 
      //$("#local_stream").css("height","370px");
      $("#local_stream_container").prepend($("#local_stream"));
      $(".organiser-img-outer").show();
      
     $("#local_stream_screen").removeClass('share-img-screen1');
     $("#local_stream_screen").css("height","0");
     // //console.log("=========error50");
      // rtc.localStream.play('local_stream');
		}
		else{
			
			//alert('on stream removed');
		$("#remote-streams").prepend($("#remote_video_panel_"+user_id));
      $(".organiser-img-outer").hide();
      $("#remote_video_panel_"+user_id).find('.testimonial-lower-outr').removeClass('no-clickable');
     $("#local_stream_screen").removeClass('share-img-screen1');
     $("#local_stream_screen").css("height","0");	
	 $('#share-screen-out').html('');
		}
      is_screen_shared = false;
	  
	   var message = new Object();
  message.type = 'removesharingid';
 RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
      //console.log(msg+'======startrecording1');
   
      $('#uid_sharescreen').val(user_id);
      $('#share_screen_id').val(evt.stream.getId());
      }).catch(error => {
       //console.log(error+'======2');
      }); 
    });

    screenClient.on("stopScreenSharing", function (evt) {
      var remoteStream = evt.stream;
     $('#uid_sharescreen').val('');
      ////console.log("=========error6");
 var message = new Object();
  message.type = 'removesharingid';
 RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
      //console.log(msg+'======startrecording1');
   
      $('#uid_sharescreen').val(user_id);
      $('#share_screen_id').val('1'+user_id);
      }).catch(error => {
       //console.log(error+'======2');
      }); 
 
      ////console.log("=========error7");
      //rtm.client.leave();
      
    });


  

    }, function(err) {
        ////console.log(err);
    })
});

}


/* screen sharing */

function sharescreen(user_id=null){
   // Create the stream for screen sharing.
   //ss_uid = $('#uid_sharescreen').val();

   console.log('ssuid' + ss_uid );
var message = new Object();
  message.type = 'startsharingscreen';
  message.uid = user_id;
   const streamSpec = {
    streamID: ss_uid,
    audio: false,
    video: false,
    screen: true,
    mediaSource: 'screen' // 'screen', 'application', 'window'
  }
  // Set relevant attributes according to the browser.
  // Note that you need to implement isFirefox and isCompatibleChrome.
  // if (isFirefox()) {
     //streamSpec.mediaSource = 'window';
  // } else if (!isCompatibleChrome()) {
  //   streamSpec.extensionId = 'minllpmhdgpndnkomcoccfekfegnlikg';
  // }
screenStream = AgoraRTC.createStream(streamSpec);
// Initialize the stream.
screenStream.init(function() {
    // Play the stream.
    // rtc.localStream.stop();
    // rtc.localStream.close();
    screenStream.play('local_stream_screen');

    // $("#organiser-img").prepend($("#local_stream"));
    // $(".organiser-img-outer").hide();

   
    //$("#local_stream_screen").addClass('share-img-screen1');
    //$("#local_stream_screen").css("height","450px");

    // Publish the stream.
   //console.log("=========error0")

   //$('#share-screen-id').val(screenStream.getId());
    screenClient.publish(screenStream);
    //console.log("=========error1")
	  var msg = JSON.stringify(message);
    RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
      //console.log(msg+'======startrecording1');
   console.log(screenStream.getId(),"screenstream");
      $('#uid_sharescreen').val(user_id);
      $('#share_screen_id').val(screenStream.getId());
      if($('#recording_enable').val()=='1')
      {
        setTimeout(function(){  updateRecordingLayout($('#share_screen_id').val(),'1'); }, 1000);
      }
      }).catch(error => {
       //console.log(error+'======2');
      });  
	
	
    screenClient.on("error", (err) => {
      ////console.log(err,"=========error")
    })
    ////console.log("=========error2")
    // Listen to the 'stream-added' event.
   
    $("#sharescreen").hide();
    $("#sharescreenclose").show();
    

}, function(err) {
   // //console.log(err);
});
}

function sharescreenclose(){
  ////console.log("sharescreenclose")
 
//var user_id = screenStream.getId();
  let user_id = screenStream.getId().slice(1);
 
      screenClient.unpublish(screenStream);
      screenStream.stop();
      ////console.log("sharescreenclose2")
      screenStream.close();
    $('#uid_sharescreen').val('');
    $('#share_screen_id').val('');
    if($('#recording_enable').val()=='1')
      {
      updateRecordingLayout(organizer_id,'0');
      }
	  /* 
	  onclose share screen 
	  */
	  
	 if(user_id == organizer_id)
		{
	 
      //$("#local_stream").css("height","370px");
      $("#local_stream_container").prepend($("#local_stream"));
      $(".organiser-img-outer").show();
      
     $("#local_stream_screen").removeClass('share-img-screen1');
     $("#local_stream_screen").css("height","0");
     // //console.log("=========error50");
      // rtc.localStream.play('local_stream');
		}
		else{
			
			//alert('on stream removed');
		$("#remote-streams").prepend($("#remote_video_panel_"+user_id));
      $(".organiser-img-outer").hide();
      $("#remote_video_panel_"+user_id).find('.testimonial-lower-outr').removeClass('no-clickable');
     $("#local_stream_screen").removeClass('share-img-screen1');
     $("#local_stream_screen").css("height","0");	
	 $('#share-screen-out').html('');
		}
      is_screen_shared = false;
	  
	  
	 /* */
	  
	  
	  
	  
	  //$('#uid_sharescreen').val('');
      ////console.log("sharescreenclose3");
      //$("#local_stream").css("height","370px");

      // $("#local_stream_container").prepend($("#local_stream"));
      // $(".organiser-img-outer").show();

      // $("#local_stream_screen").removeClass('share-img-screen1');
      // $("#local_stream_screen").css("height","0");
      
  $("#sharescreen").show();
  $("#sharescreenclose").hide();
}



function pinSelf(uid){
  var message = new Object();
  message.type = 'pinUser';
  message.uid = uid;


  var msg = JSON.stringify(message);
  RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
    ////console.log('organizerLeft===client');

    
    }).catch(error => {
     // //console.log('organizerLeft error==client');
    });
    


      ////console.log(stream.getId(),"======remoteStreams");
      $("#local_stream").html('');
      rtc.localStream.play("local_stream");
 
   
  

}

function pinUser(uid){
  var message = new Object();
  message.type = 'pinUser';
  message.uid = uid;

  //console.log(message.uid,"=======message.uid");


  var msg = JSON.stringify(message);
  RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
    ////console.log('organizerLeft===client');

    
    }).catch(error => {
     // //console.log('organizerLeft error==client');
    });
    
    pinUserFrame(uid)
}

/* Raise hand */
function raiseHand(type,uid,plan){
  var message = new Object();
  message.type = type;
  message.uid = uid;
   message.plan = plan;
  //console.log(message.uid,"=======message.uid");

  var msg = JSON.stringify(message);
  RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
    ////console.log('organizerLeft===client');
	
    if(message.type=='raiseHand')
	{
		$('#raise-hand-'+message.uid).hide();
		$('#un-raise-hand-'+message.uid).show();
	}
	else if(message.type=='unraiseHand')
	{
	  $('#raise-hand-'+message.uid).show();
	  $('#un-raise-hand-'+message.uid).hide();	
	}
	else{
		
	}
	 
    
    }).catch(error => {
     // //console.log('organizerLeft error==client');
    });
    
    //pinUserFrame(uid)
	
}

/* unRaise hand admin*/
function un_raise_admin(uid,plan){
  var message = new Object();
  message.type = "unraiseAdmin";
  message.uid = uid;
  message.plan = (plan=='3')?'2':'1';
 var msg = JSON.stringify(message);
RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
    ////console.log('organizerLeft===client');
	
    if(message.type=='unraiseAdmin')
	{
		$('#raise_btn_mute_'+uid).hide();
		$('#pin_user_raise_'+message.uid).hide();
		if($( "#participants li" ).hasClass( "raise-secondary") || $( "#participants li" ).hasClass( "raise-primary"))
		{
		
        $('#participant_'+message.uid).removeClass('raise-primary');	
         $('#participant_'+message.uid).removeClass('raise-secondary');	
          if($( "#participants li" ).hasClass( "raise-secondary"))
		  {
		   $('#participant_'+message.uid).insertAfter('.raise-secondary:last');	
		  }
          else if($( "#participants li" ).hasClass( "raise-primary"))
	      {
           $('#participant_'+message.uid).insertAfter('.raise-primary');	
	      }
		 else
		 {
			 $('#participant_'+message.uid).appendTo('#participants');	
		 }		  
		}
	}
	
	else{
		
	}
	 
    
    }).catch(error => {
     // //console.log('organizerLeft error==client');
    });
}

function pinUserFrame(uid){
  if(pinned_user==null){
    $("#share-screen-out").prepend($("#remote_video_panel_"+uid));
    pinned_user = uid;
  }
  else if(pinned_user==uid){
    
    $("#remote_stream").prepend($("#remote_video_panel_"+uid));
    pinned_user = null;
  }
  else{
    $("#remote_stream").prepend($("#remote_video_panel_"+pinned_user));
    $("#share-screen-out").prepend($("#remote_video_panel_"+uid));
    pinned_user = uid;
  }
}

function organizerLeft(){
  var message = new Object();
  message.type = 'organizerLeft';


  var msg = JSON.stringify(message);
  
  RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
    ////console.log('organizerLeft===client');

    
    }).catch(error => {
      ////console.log('organizerLeft error==client');
    });

}



function muteunmuteremote(uid, action, stream){
  var message = new Object();
  message.uid = uid;
  message.stream = stream;
  message.type = action;


  var msg = JSON.stringify(message);
 
  RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
   // //console.log('muteunmuteremote===');

	 if(message.type == "mute" && message.stream == "chat" && message.uid=='all'){
		$('#mute-chat-all').hide(); 
     $('#unmute-chat-all').show(); 
     addControlDB('chat',1);
     if($('#now_presenting').val() !='0')
     {
      setTimeout(function(){  $('#chat_btn_unmute_'+$('#now_presenting').val()).hide(); 
      $('#chat_btn_mute_'+$('#now_presenting').val()).show(); }, 100);
      
     }
	 }
	 if(message.type == "unmute" && message.stream == "chat" && message.uid=='all'){
		$('#mute-chat-all').show(); 
     $('#unmute-chat-all').hide(); 
     addControlDB('chat',0);
	 }
	 
	 if(message.type == "mute" && message.stream == "video" && message.uid=='all'){
		$('#mute-video-all').hide(); 
     $('#unmute-video-all').show(); 
     addControlDB('video',1);
	 }
	 if(message.type == "unmute" && message.stream == "video" && message.uid=='all'){
		$('#mute-video-all').show(); 
     $('#unmute-video-all').hide(); 
     addControlDB('video',0);
	 }
	 if(message.type == "mute" && message.stream == "mic" && message.uid=='all'){
		$('#mute-mic-all').hide(); 
     $('#unmute-mic-all').show(); 
     addControlDB('mic',1);
	 }
	 if(message.type == "unmute" && message.stream == "mic" && message.uid=='all'){
		$('#mute-mic-all').show(); 
     $('#unmute-mic-all').hide(); 
     addControlDB('mic',0);
	 }
    if(message.type == "mute" && message.stream == "chat" && message.uid=='all'){
		
      $('.chat_btn_mute').hide();
      $('.chat_btn_unmute').show();
     // //console.log('#chat_btn_mute_'+message.uid);
    }
    else if(message.type == "mute" && message.stream == "chat"){
      ////console.log('#chat_btn_mute_'+message.uid);
      $('#chat_btn_mute_'+message.uid).hide();
      $('#chat_btn_unmute_'+message.uid).show();
    }
    ////console.log('#chat_btn_mute_'+JSON.stringify(message));


    if(message.type == "unmute" && message.stream == "chat" && message.uid=='all'){
      $('.chat_btn_mute').show();
      $('.chat_btn_unmute').hide();
      ////console.log('#chat_btn_mute_'+message.uid);
    }
    else if(message.type == "unmute" && message.stream == "chat"){
      ////console.log('#chat_btn_mute_'+message.uid);
      $('#chat_btn_mute_'+message.uid).show();
      $('#chat_btn_unmute_'+message.uid).hide();
    }

    
    }).catch(error => {
      ////console.log('muteunmuteremote error==');
    });

}

/*
function switchLanguage(uid,screenshare=false)
{


	$('.translators-li').removeClass('active');
 rtc.remoteStreams.forEach(function (stream) {

alert(stream.getId());
	  if(!$.inArray(uid, translators_id) && !screenshare)
	  {
		
	  // stream.unmuteAudio();
	  stream.muteAudio();
	  }
	  if($.inArray(uid, translators_id) && screenshare)
	  {
		
	  stream.muteAudio();
	  }
	  
     if(stream.getId() == uid)
	 {	
 alert('here');
      alert(stream.muteAudio());
	  
     if(stream.unmuteAudio())
	  {
		  alert('translation unumted');
		$('#participant_translator_'+uid).addClass('active');
	  }
	 }
	 
	 if(stream.getId() == organizer_id && uid != organizer_id)
	 {
		
      //stream.unmuteAudio();		 
      stream.muteAudio();
	 }

	 
	 /*
	  if(stream.getId() == uid)
	 {	
 //alert('here');
      stream.muteAudio();
	  
     if(stream.unmuteAudio())
	  {
		 // alert('translation unumted');
		$('#participant_translator_'+uid).addClass('active');
	  }
	 }
	 
	 /*
//alert(stream.getId());
	  if(!$.inArray(uid, translators_id) && !screenshare)
	  {
		
	  // stream.unmuteAudio();
	  stream.muteAudio();
	  }
	  if($.inArray(uid, translators_id) && screenshare)
	  {
		
	  stream.muteAudio();
	  }
	  
     if(stream.getId() == uid)
	 {	
 //alert('here');
      stream.muteAudio();
	  
     if(stream.unmuteAudio())
	  {
		 // alert('translation unumted');
		$('#participant_translator_'+uid).addClass('active');
	  }
	 }
	 
	 if(stream.getId() == organizer_id && uid != organizer_id)
	 {
		
      //stream.unmuteAudio();		 
      stream.muteAudio();
	 }
	 */
   /* })*/
 
	
/*}*/

function switchLanguage(uid,screenshare=false)
{
	
	var turn_on = false;
	if($('#participant_translator_'+uid).hasClass('active'))
	{
	
	if($('#now_presenting').val()==0)
	{
    uid = organizer_id;
	turn_on = true;
	}
	else
	{
    uid = $('#now_presenting').val();
	turn_on = false;
	}
	}
	
	
	if(uid != organizer_id)
	{
	
	$('.translators-li').removeClass('active');
 rtc.remoteStreams.forEach(function (stream) {
//alert(stream.getId());
	  if(JSON.parse($("#translators_id").val()).includes(stream.getId()) && !screenshare)
	  {
		
	  // stream.unmuteAudio();
	  if(stream.hasAudio() && stream.getId() != uid)
	  {
	  stream.muteAudio();
	  }
	  }
	  if(JSON.parse($("#translators_id").val()).includes(stream.getId()) && screenshare)
	  {
		
	  stream.muteAudio();
	  }
	  
     if(stream.getId() == uid)
	 {	
 //alert('here');
      stream.muteAudio();
	  
     if(stream.unmuteAudio())
	  {
		
		$('#participant_translator_'+uid).addClass('active');
	  }
	 }
	 
	 if(stream.getId() == organizer_id && uid != organizer_id)
	 {
		stream.unmuteAudio();	
	 }
	 
	 if(stream.getId() == $('#now_presenting').val() && uid != $('#now_presenting').val() && $('#now_presenting').val() != 0)
	 {
		
      //stream.unmuteAudio();		 
      stream.muteAudio();
	 }
 
	 
    })
	}
	else
		{
		
		 rtc.remoteStreams.forEach(function (stream) {
			 
			var unmuted = false; 
      if($('#now_presenting').val() != 0 && stream.getId()==organizer_id)
	  {
      stream.muteAudio();
	  //alert(stream.unmuteAudio());
     if(stream.unmuteAudio())
	  {
		  unmuted = true;
		$('.translators-li').removeClass('active');
		$('#participant_translator_'+uid).addClass('active');
	  }
	  }
      
	  if($('#now_presenting').val() == 0 && stream.getId()==organizer_id)
	  {
      stream.muteAudio();
	  
     if(stream.unmuteAudio())
	  {
		   unmuted = true;
		$('.translators-li').removeClass('active');
		$('#participant_translator_'+uid).addClass('active');
	  }
	  } 
	  
	  if(unmuted)
	  {
		disableTranslators(screenshare);  
	  }
		
		/*if(JSON.parse($("#translators_id").val()).includes(stream.getId()) && !screenshare)
	  {
		
	  // stream.unmuteAudio();
	  stream.muteAudio();
	 /* if(stream.hasAudio())
	  {
	  stream.muteAudio();
	  }*/
	/*  }
	  if(JSON.parse($("#translators_id").val()).includes(stream.getId()) && screenshare && unmuted)
	  {
		
	  stream.muteAudio();
	  }*/
     
		})	
		}
	
}

function disableTranslators(screenshare)
{
rtc.remoteStreams.forEach(function (stream) {
			 	
		if(JSON.parse($("#translators_id").val()).includes(stream.getId()) && !screenshare)
	  {
		
	  // stream.unmuteAudio();
	  stream.muteAudio();
	 /* if(stream.hasAudio())
	  {
	  stream.muteAudio();
	  }*/
	  }
	  if(JSON.parse($("#translators_id").val()).includes(stream.getId()) && screenshare)
	  {
		
	  stream.muteAudio();
	  }
     
		})		
}

/*function switchLanguage(uid,screenshare=false)
{
//alert(uid);
//alert(uid);
	$('.translators-li').removeClass('active');
	 translators_id = JSON.parse($("#translators_id").val());
	 //alert( rtc.remoteStreams );
 rtc.remoteStreams.forEach(function (stream) {
	
	  //alert(translators_id);
	 // alert(stream.getId());

	  if(JSON.parse($("#translators_id").val()).includes(stream.getId()) && !screenshare)
	  { 
	
	  // stream.unmuteAudio();
	 
	  if(!stream.muteAudio())
	  {
		stream.unmuteAudio();
         stream.muteAudio();		
	  }
	  }
	  if(JSON.parse($("#translators_id").val()).includes(stream.getId()) && screenshare)
	  {
	if(!stream.muteAudio())
	  {
		stream.unmuteAudio();
         stream.muteAudio();		
	  }
	  }
	 
     if(stream.getId() == uid)
	 {	
    
     // stream.muteAudio();
	 // alert(stream.unmuteAudio());
     if(stream.unmuteAudio())
	  {
		
		$('#participant_translator_'+uid).addClass('active');
	  }
	  else{
		stream.muteAudio();
		if(stream.unmuteAudio())
	  {
		
		$('#participant_translator_'+uid).addClass('active');
	  }		
	  }
	 }
	 
	 if(stream.getId() == organizer_id && uid != organizer_id)
	 {
		
      //stream.unmuteAudio();		 
      stream.muteAudio();
	 }
    })
 
	
} */



function allowOrganizerLanguage()
{
 rtc.remoteStreams.forEach(function (stream) {
if(JSON.parse($("#translators_id").val()).includes(stream.getId()))
 {
	 rtc.remoteStreams.forEach(function (stream) {
if(stream.getId() == organizer_id)
	 {
		
      //stream.unmuteAudio();		 
      stream.unmuteAudio();
	 }
	 });
 }
    });
}

function makepresenter(uid,mute=false)
{
  if($('#uid_sharescreen').val())
	{
		
    let user_id = screenStream.getId().slice(1);
  
	 if(user_id==organizer_id)
	 {
		 alert('Please disable the screen sharing icon first.');
		 $('.presenter').removeClass('active');
		 return ;
	 }

  }
  makepresenterLocal(organizer_id,true);
  
  $('.presenter').removeClass('active');
	
  var message = new Object();
   message.uid = uid;
   message.type = "makepresenter";
   var msg = JSON.stringify(message);  
  RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
  
    $('#make_presenter_'+uid).addClass('active');
	 if(is_screen_shared)
	{
	$('#sharescreenclose').click();	
	 $('#sharescreenclose').hide();
	  $('#sharescreen').show();
	}
	assignPresenter(uid);
	
	if(mute){
	muteunmuteremote('all','mute','mic');
	}
}).catch(error => {
      ////console.log('muteunmuteremote error==');
    });
}

function makepresenterLocal(uid,selfclicked=false)
{
 var message = new Object();
   message.uid = uid;
   message.type = "makepresenter";
   var msg = JSON.stringify(message); 
   if(selfclicked)
   {
if(!is_screen_shared)
		{   
  RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
	assignPresenter(uid);
	//muteunmuteremote('all','mute','mic');
	
	if($('#now_presenting').val() != 0 && uid==$('#now_presenting').val())
	{
	
  $("#presenter-list option[value="+$('#now_presenting').val()+"]").attr('selected','selected');	 
  $('#make_presenter_'+$('#now_presenting').val()).addClass('active');
	}
	else{
  $('#presenter-list').prop("selectedIndex", 0);
  $('.presenter').removeClass('active');	
	}
}).catch(error => {
      ////console.log('muteunmuteremote error==');
    });
		}
		else{
			$.confirm({
    title: 'Notice!',
    content: 'Someone else is currently presenting do you really want to take access ?',
    buttons: {
        confirm: function () {
            RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
	assignPresenter(uid);
	//muteunmuteremote('all','mute','mic');
	
	if($('#now_presenting').val() != 0 && uid==$('#now_presenting').val())
	{
	
  $("#presenter-list option[value="+$('#now_presenting').val()+"]").attr('selected','selected');	 
  $('#make_presenter_'+$('#now_presenting').val()).addClass('active');
	}
	else{
  $('#presenter-list').prop("selectedIndex", 0);	
  $('.presenter').removeClass('active');
	}
}).catch(error => {
      ////console.log('muteunmuteremote error==');
    });
        },
        cancel: function () {
          
        }
    }
});
		}
   }
   else
   {
	 RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {
	assignPresenter(uid);
	//muteunmuteremote('all','mute','mic');
	
	if($('#now_presenting').val() != 0 && uid==$('#now_presenting').val())
	{
	
  $("#presenter-list option[value="+$('#now_presenting').val()+"]").attr('selected','selected');	
  $('#make_presenter_'+$('#now_presenting').val()).addClass('active'); 
	}
	else{
  $('#presenter-list').prop("selectedIndex", 0);	
  $('.presenter').removeClass('active');
	}
}).catch(error => {
      ////console.log('muteunmuteremote error==');
    });   
   }
}

/* Assign as presenter */
function assignPresenter(uid)
{
  $('#flags-ul li').hide();
  //alert(uid);
  $('.'+uid).show();
	$('.corner-right-presenter').hide();
	 if(uid==organizer_id)
	 {
		if(is_screen_shared)
		{
		
		 stopShareScreenRemote();
		}
		 if($("#remote-streams").is(':empty'))
         {
         }
		 else
		 {
			
			 var id_replace_user = $('#remote-streams li[id^="remote_video_panel_"]').attr('id');
			
            var final_id_user = id_replace_user.replace("remote_video_panel_","");
		
		  $('#slider').data('flexslider').addSlide($("#remote_video_panel_"+final_id_user));	
		  $('#remote-streams').remove(); 
		 }
		 prsenterLog(0);
		 $("#local_stream_container").prepend($("#local_stream"));
     $(".organiser-img-outer").show();
	$('.shared_screen_presenter').hide();
	
	 }
	 else{
    $("#local_stream_container").prepend($("#local_stream"));
     $(".organiser-img-outer").show();  
 $('.video-upper-btns').removeClass('remote-presenting');
//$('#slider').data('flexslider').addSlide('<li>'+$("#local_stream").html()+'</li>');
var id_replace = $('#local_stream div[id^="player_"]').attr('id');
var final_id = id_replace.replace("player_","");
if(final_id==organizer_id)
{
	
	$("#organiser-img").prepend($("#local_stream"));
   $(".organiser-img-outer").hide();

if($("#remote-streams").is(':empty'))
{
 
}
else
{

$('#slider').data('flexslider').addSlide($("#remote_video_panel_"+uid));	
 //$('#slider').data('flexslider').addSlide($($("#remote-streams").html()));	
 $("#remote-streams").remove();
}
}
else{

 $('#slider').data('flexslider').addSlide($("#remote_video_panel_"+uid));
//$("#remote-streams").remove(); 
}

prsenterLog(uid);
if(is_screen_shared)
		{	
		//alert('here');	
 $("#local_stream_container").prepend('<div id="remote-streams"></div>');
 //$("#remote-streams").prepend($("#remote_video_panel_"+uid));
 $('#share_screen_user_'+uid).show();
 $('.video-upper-btns').addClass('remote-presenting');
$('.current_user_presenter_'+uid).show();
$("#presenter-list option[value="+uid+"]").attr('selected','selected');	  
$('#share-screen-out').prepend($("#remote_video_panel_"+user_id));
$("#remote_video_panel_"+user_id).find('.testimonial-lower-outr').addClass('no-clickable');
	    }
		
		else
		{
		
	$('.shared_screen_presenter').hide();
	$("#remote-streams").remove();
	$("#local_stream_container").prepend('<div id="remote-streams"></div>');

 $("#remote-streams").prepend($("#remote_video_panel_"+uid));
 $('#share_screen_user_'+uid).show();
 $('.video-upper-btns').addClass('remote-presenting');
$('.current_user_presenter_'+uid).show();
$("#presenter-list option[value="+uid+"]").attr('selected','selected');	 
$("#make_presenter_"+uid).addClass('active');		
			
		}

	 }
}


function stopShareScreenRemote()
{
var message = new Object();
   message.type = "stopsharingremote";
   var msg = JSON.stringify(message);  
  RTMClient.activeChennel.sendMessage({ text: msg}).then(() => {

  }).catch(error => {
      ////console.log('muteunmuteremote error==');
    });  
	
}

/* RTM   */
const RTMClient = AgoraRTM.createInstance(appId); // Pass your App ID and RtmParameters.
RTMClient._logined = false;
function joinrtm(){

RTMClient.on('ConnectionStateChange', (newState, reason) => {
  ////console.log('on connection state changed to ' + newState + ' reason: ' + reason);
});

RTMClient.login({ token: null, uid: String(confernce_params['uid']) }).then(() => {
  RTMClient._logined = true;
  creteRTMChannel();
  $("#send_rtm_channel_message").html("<img src="+base_url+"images/send.png>");
  ////console.log('AgoraRTM client login success');
}).catch(err => {
  setTimeout(function(){ 
    joinrtm();
          }, 5000);
 
  ////console.log('AgoraRTM client login failure', err);
});

}

function creteRTMChannel(){
const channel = RTMClient.createChannel(confernce_params['channel']); 
RTMClient.activeChennel = channel;
channel.join().then(() => {
  ////console.log('channel joined');
  subscribeRTMChannelMessages();

  }).catch(error => {
    ////console.log('channel not joined');
  });
}

function sendRTMChannelMessage(){
	var text = $('#rtm_channel_message').val();
  var message = new Object();
  message.text = text.replace(/(<([^>]+)>)/ig,"");
  message.name = user.name;
  message.image = user.image;

 

  message.type = "channelMessage";
  var d = new Date();
  message.time = d.toLocaleTimeString('en-GB');


  var msg = JSON.stringify(message);
  ////console.log(msg,"=====msg=="+user.name+"----");
  if(message.text!="" && message.type == "channelMessage"){
  RTMClient.activeChennel.sendMessage({ text: msg, enableHistoricalMessaging:true }).then(() => {
    addSelfChatMessage(message);
	addMessageDb(message,user.id,d.toUTCString());
    $('#rtm_channel_message').val("");
    ////console.log('msg sent'+user.name+"===");
    }).catch(error => {
      ////console.log('msg not sent=='+error);
    });
  }

  }

  function subscribeRTMChannelMessages(){

    RTMClient.activeChennel.on('ChannelMessage', ({ text }, senderId) => { // text: text of the received channel message; senderId: user ID of the sender.
    /* Your code for handling events, such as receiving a channel message. */
    var message = JSON.parse(text);
    //console.log(message,"============message");

    if(message.type == 'organizerLeft'){
      selfleave = true;
      $("#message").html(`<span style="color:red">Conference Has Been Ended.</span>`);
	  prsenterLog(0);
      setTimeout(function(){ 
            selfleave = true;
            window.location.href="/give-rating/"+uid_encrypted;
          }, 4000);
    }

    
    if(message.type == 'pinUser'){
      pinUserFrame(message.uid);
   }
   if(message.type == "startrecording"){
     //console.log('======startrecording');
    startrecordings();
  }
  
  if(message.type == "stoprecording"){
    stoprecordings();
  }

    
    if(message.type == "mute" && message.stream == "mic"){
      mic_muted_by_org = true;
    }
    if(message.type == "unmute" && message.stream == "mic"){
      mic_muted_by_org = false;
    }
    if(message.type == "mute" && message.stream == "video"){
      video_muted_by_org = true;
    }
    if(message.type == "unmute" && message.stream == "video"){
      video_muted_by_org = false;
    }


    if(message.type == "channelMessage"){
      //console.log(message.text,"===text");
      addChatMessage(message);
    }
	else if(message.type=='raiseHand')
	 {
		
		$('#raise_btn_mute_'+message.uid).hide();
		$('#raise_btn_mute_'+message.uid).show(); 
		$('#pin_user_raise_'+message.uid).hide();
		$('#pin_user_raise_'+message.uid).show();
		if(message.plan=='1')
		{
		if($( "#participants li" ).hasClass( "raise-primary"))
		{
		$('#participant_'+message.uid).insertAfter('.raise-primary:last');	
		$('#participant_'+message.uid).addClass('raise-primary');	
		}
		else{
		$('#participant_'+message.uid).prependTo('#participants');	
        $('#participant_'+message.uid).addClass('raise-primary');		
		}
		}
		else if(message.plan=='2'){
		if($( "#participants li" ).hasClass( "raise-secondary"))
		{
		$('#participant_'+message.uid).insertAfter('.raise-secondary:last');	
		}
		else if($( "#participants li" ).hasClass( "raise-primary"))
		{
		$('#participant_'+message.uid).insertAfter('.raise-primary:last');	
		$('#participant_'+message.uid).addClass('raise-secondary');		
		}
		else{
		$('#participant_'+message.uid).prependTo('#participants');	
        $('#participant_'+message.uid).addClass('raise-secondary');		
		}
		}
		else{
			
		}
		//$("#participants li").sort(sort_li_secondary).appendTo('#participants');
	    //$("#participants li").sort(sort_li_primary).appendTo('#participants');
			
		
		
		handraised();
	 }
	 else if(message.type=='startsharingscreen')
	 {
		 //alert('screen share start');
		switchLanguage(message.uid,true);
		 
	 }
	 else if(message.type=='makepresenter')
	 {
    assignPresenter(message.uid);
	   
	 }
	 else if(message.type=='stopsharingremote')
	 {
		 if($('#my_user_id').val()==screenStream.getId().slice(1))
		 {
			//alert('remotestop');
           $('#sharescreenclose').click();	
          makepresenterLocal(organizer_id);		   
		 }
	 
	 }
	 else if(message.type=='unraiseHand')
	 {
		console.log(message);
		$('#raise_btn_mute_'+message.uid).hide();
		$('#pin_user_raise_'+message.uid).hide();
		$('#participant_'+message.uid).removeClass('handraised');
		if($( "#participants li" ).hasClass( "raise-secondary") || $( "#participants li" ).hasClass( "raise-primary"))
		{
		
        $('#participant_'+message.uid).removeClass('raise-primary');	
         $('#participant_'+message.uid).removeClass('raise-secondary');	
          if($( "#participants li" ).hasClass( "raise-secondary"))
		  {
		   $('#participant_'+message.uid).insertAfter('.raise-secondary:last');	
		  }
          else if($( "#participants li" ).hasClass( "raise-primary"))
	      {
           $('#participant_'+message.uid).insertAfter('.raise-primary');	
	      }
		 else
		 {
			 $('#participant_'+message.uid).appendTo('#participants');	
		 }		  
		}
		
	 }
	 
	  else if(message.type=='unraiseAdmin')
	 {
	   console.log(message);
	    $('#un-raise-hand-'+message.uid).hide();
		$('#pin_user_raise_'+message.uid).hide();
		$('#raise_btn_mute_'+message.uid).hide();
		$('#raise-hand-'+message.uid).show();
		
		
	 }
	 
    else if(message.type == "mute" && message.stream == "mic" && (message.uid==user.id || message.uid=='all')){
		
		if(message.uid=='all')
		{
			//alert(JSON.parse($("#translators_id").val()).includes($('#mute-audio').data('userid')));
		if(JSON.parse($("#translators_id").val()).includes($('#mute-audio').data('userid')))
	    {
	
	    
	     }
         else if($('#now_presenting').val()== $('#mute-audio').data('userid'))
		 {
         
		 }
		  else if($('#mute-audio').data('userid')==organizer_id)
		 {
         
		 }
         else
		 {
		
          $('#mute-audio').click();
		 }	 
		}
		else{
		  $('#mute-audio').click();	
		}
    
      //console.log('mute-audio_'+message.uid);
    }
    else if(message.type == "unmute" && message.stream == "mic" && (message.uid==user.id || message.uid=='all')){
      $('#unmute-audio').click();
	  
    }
    else if(message.type == "mute" && message.stream == "video" && (message.uid==user.id || message.uid=='all')){
      //$('#mute-video').click();
      //console.log('mute-video_'+message.uid);
      //console.log('mute-video_'+message.uid);

      if(message.uid=='all')
		{
			//alert(JSON.parse($("#translators_id").val()).includes($('#mute-audio').data('userid')));
		if(JSON.parse($("#translators_id").val()).includes($('#mute-video').data('userid')))
	    {
	
	    
	     }
         else if($('#now_presenting').val()== $('#mute-video').data('userid'))
		 {
         
		 }
		  else if($('#mute-video').data('userid')==organizer_id)
		 {
         
		 }
         else
		 {
		
      $('#mute-video').click();
		 }	 
		}
		else{
      $('#mute-video').click();
		}
	 
    }
    else if(message.type == "unmute" && message.stream == "video" && (message.uid==user.id || message.uid=='all')){
      $('#unmute-video').click();
	
    }
    else if(message.type == "mute" && message.stream == "chat" && (message.uid==user.id || message.uid=='all')){
      //$('#chat_control').hide(); 
      if(message.uid=='all')
      {
        //alert(JSON.parse($("#translators_id").val()).includes($('#mute-audio').data('userid')));
      if(JSON.parse($("#translators_id").val()).includes($('#mute-video').data('userid')))
        {
    
        
         }
           else if($('#now_presenting').val()== $('#mute-video').data('userid'))
       {
           
       }
        else if($('#mute-video').data('userid')==organizer_id)
       {
           
       }
           else
       {
      
        $('#chat_control').hide(); 
       }	 
      }
      else{
        $('#chat_control').hide(); 
      }
      //console.log('#chat_btn_mute_'+message.uid);
    }
    else if(message.type == "unmute" && message.stream == "chat" && (message.uid==user.id || message.uid=='all')){
      $('#chat_control').show();
      //console.log('#chat_btn_mute_'+message.uid);
    }


    // if(message.type == "mute" && message.stream == "mic" && message.uid=='all'){
    //   $('.mic_btn_mute').hide();
    //   $('.mic_btn_unmute').show();
    // }
    // else if(message.type == "mute" && message.stream == "mic"){
    //   $('#mic_btn_mute_'+message.uid).hide();
    //   $('#mic_btn_unmute_'+message.uid).show();
    // }

    // if(message.type == "unmute" && message.stream == "mic" && message.uid=='all'){
    //   $('.mic_btn_mute').show();
    //   $('.mic_btn_unmute').hide();
    // }
    // else if(message.type == "unmute" && message.stream == "mic"){
    //   $('#mic_btn_mute_'+message.uid).show();
    //   $('#mic_btn_unmute_'+message.uid).hide();
    // }



    // if(message.type == "mute" && message.stream == "video" && message.uid=='all'){
    //   $('.video_btn_mute').hide();
    //   $('.video_btn_unmute').show();
    // }
    // else if(message.type == "mute" && message.stream == "video"){
    //   $('#video_btn_mute_'+message.uid).hide();
    //   $('#video_btn_unmute_'+message.uid).show();
    // }

    // if(message.type == "unmute" && message.stream == "video" && message.uid=='all'){
    //   $('.video_btn_mute').show();
    //   $('.video_btn_unmute').hide();
    // }
    // else if(message.type == "unmute" && message.stream == "video"){
    //   $('#video_btn_mute_'+message.uid).show();
    //   $('#video_btn_unmute_'+message.uid).hide();
    // }



    if(message.type == "mute" && message.stream == "chat" && message.uid=='all'){
      $('.chat_btn_mute').hide();
      $('.chat_btn_unmute').show();
      //console.log('#chat_btn_mute_'+message.uid);
    }
    else if(message.type == "mute" && message.stream == "chat"){
      //console.log('#chat_btn_mute_'+message.uid);
      $('#chat_btn_mute_'+message.uid).hide();
      $('#chat_btn_unmute_'+message.uid).show();
    }
    //console.log('#chat_btn_mute_'+JSON.stringify(message));


    if(message.type == "unmute" && message.stream == "chat" && message.uid=='all'){
      $('.chat_btn_mute').show();
      $('.chat_btn_unmute').hide();
      //console.log('#chat_btn_mute_'+message.uid);
    }
    else if(message.type == "unmute" && message.stream == "chat"){
      //console.log('#chat_btn_mute_'+message.uid);
      $('#chat_btn_mute_'+message.uid).show();
      $('#chat_btn_unmute_'+message.uid).hide();
    }
    //console.log('#chat_btn_mute_'+JSON.stringify(message));


    


  

    //console.log(senderId+'msg recieved  '+text);
    });

  }
  
  function sort_li_primary(a, b) {
	
    return ($(b).data('position')) < ($(a).data('position')) ? 1 : -1;
  }
  function sort_li_secondary(a, b) {
	alert($(b).data('secondary')); 
    return ($(b).data('secondary')) < ($(a).data('secondary')) ? 1 : -1;
  }
  function addSelfChatMessage(message){
    $(`<div class="chat-outer chat-outer-2">
    <div class="chat-right">
        <div class="chat-desc">`+message.text+`</div>
        <ul>
            <li><a href="#">SENT `+message.time+`</a></li>
            <li><a href="#">`+message.name+`</a></li>
        </ul>
    </div>
    <div class="chat-left">
        <div class="con-left">
           <img src="`+message.image+`" alt="">
         </div>
    </div>
</div>`).appendTo('#log');

    var div = $("#log");
    div.scrollTop(div.prop('scrollHeight'));
    
  }

  function addChatMessage(message){
  
    $(`<div class="chat-outer">
    <div class="chat-left">
        <div class="con-left">
           <img src="`+message.image+`" alt="">
           </div>
    </div>
    <div class="chat-right">
        <div class="chat-desc">`+message.text+`</div>
        <ul>
            <li><a href="#">SENT `+message.time+`</a></li>
            <li><a href="#">`+message.name+`</a></li>
        </ul>
    </div>
</div>`).appendTo('#log');

    var div = $("#log");
    div.scrollTop(div.prop('scrollHeight'));
    $('#nav-profile-tab').click();
  }



  function toggleFullScreen(elem) {
    // ## The below if statement seems to work better ## if ((document.fullScreenElement && document.fullScreenElement !== null) || (document.msfullscreenElement && document.msfullscreenElement !== null) || (!document.mozFullScreen && !document.webkitIsFullScreen)) {
    if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
        if (elem.requestFullScreen) {
            elem.requestFullScreen();
        } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullScreen) {
            elem.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
}


function logs(){

  $.ajax({
    type: "POST",
    url: "/api/log-conference-joinee",
    data : {
      "_token":$('meta[name="csrf-token"]').attr('content'),
      "user_id" : user.id,
      "conf_id" : conference_id
    },
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
    success: function(msg){
          console.log( "Data Saved: " + msg );
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      console.log(errorThrown,"==some error");
    }
  });

}

function prsenterLog(user_id){
$('#now_presenting').val(user_id);
  $.ajax({
    type: "POST",
    url: "/api/add-presenter",
    data : {
      "_token":$('meta[name="csrf-token"]').attr('content'),
      "user_id" : user_id,
      "conf_id" : conference_id
    },
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
    success: function(msg){
          console.log( "Data Saved: " + msg );
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      console.log(errorThrown,"==some error");
    }
  });

}


function recordingLog(user_id,recording_status){
$('#recording_enable').val(recording_status);
  $.ajax({
    type: "POST",
    url: "/api/add-recording",
    data : {
      "_token":$('meta[name="csrf-token"]').attr('content'),
      "user_id" : user_id,
      "conf_id" : conference_id,
	   "status" : recording_status
    },
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
    success: function(msg){
          console.log( "Data Saved: " + msg );
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      console.log(errorThrown,"==some error");
    }
  });

}

function addMessageDb(message,user_id,time){

  $.ajax({
    type: "POST",
    url: "/api/add-message",
    data : {
      "_token":$('meta[name="csrf-token"]').attr('content'),
      "conf_id" : conference_id,
	   "message" : message.text,
	   "time" : time,
	   "user_id" : user_id
    },
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
    success: function(msg){
          console.log( "Data Saved: " + msg );
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      console.log(errorThrown,"==some error");
    }
  });

}

function getMessageDb(){

  $.ajax({
    type: "POST",
    url: "/api/get-message",
    data : {
      "_token":$('meta[name="csrf-token"]').attr('content'),
      "conf_id" : conference_id
	  
    },
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
    success: function(msg){

		if(msg.success)
		{
         
		  //var obj = jQuery.parseJSON(msg.messages);
     $.each(msg.messages, function(key,value) {
     console.log(value);
	 if(value.user_id==$('#my_user_id').val()){
	  $(`<div class="chat-outer chat-outer-2">
    <div class="chat-right">
        <div class="chat-desc">`+value.message+`</div>
        <ul>
            <li><a href="#">SENT `+value.time+`</a></li>
            <li><a href="#">`+value.user_name+`</a></li>
        </ul>
    </div>
    <div class="chat-left">
        <div class="con-left">
           <img src="`+value.image+`" alt="">
         </div>
    </div>
</div>`).appendTo('#log');
	 }
	 else
	 {
	  $(`<div class="chat-outer">
    <div class="chat-left">
        <div class="con-left">
           <img src="`+value.image+`" alt="">
           </div>
    </div>
    <div class="chat-right">
        <div class="chat-desc">`+value.message+`</div>
        <ul>
            <li><a href="#">SENT `+value.time+`</a></li>
            <li><a href="#">`+value.user_name+`</a></li>
        </ul>
    </div>
</div>`).appendTo('#log'); 
	 }
    var div = $("#log");
    div.scrollTop(div.prop('scrollHeight'));
      }); 
		}
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      console.log(errorThrown,"==some error");
    }
  });

}
function addControlDB(control,status){

  $.ajax({
    type: "POST",
    url: "/api/modrator-controls",
    data : {
      "_token":$('meta[name="csrf-token"]').attr('content'),
      "conf_id" : conference_id,
     "control" :status,
     "type":control
    },
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
    success: function(msg){
          console.log( "Data Saved: " + msg );
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      console.log(errorThrown,"==some error");
    }
  });

}

function addUserControlDB(control,status){

  $.ajax({
    type: "POST",
    url: "/api/modrator-controls",
    data : {
      "_token":$('meta[name="csrf-token"]').attr('content'),
      "conf_id" : conference_id,
      "user_id" : $('#my_user_id').val(),
     "control" :status,
     "type":control
    },
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
    success: function(msg){
          console.log( "Data Saved: " + msg );
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      console.log(errorThrown,"==some error");
    }
  });

}

function updateRecordingLayout(id,type=0){

  $.ajax({
    type: "POST",
    url: "/api/updatevideo-layout",
    data : {
      "_token":$('meta[name="csrf-token"]').attr('content'),
      "conf_id" : conference_id,
      "id" : id,
      "type":type
    },
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
    success: function(msg){
        //  console.log( "Data Saved: " + msg );
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      //console.log(errorThrown,"==some error");
    }
  });

}
function switchCamera()
{

  /*AgoraRTC.getDevices (function(devices) {
    console.log(devices[2].deviceId);
    var devCount = devices.length;
    alert(devCount);
    var id = devices[2].deviceId;
    alert(id);
    //rtc.localStream.getVideoTrack().stop();
   // rtc.localStream.switchDevice("video",id);
   rtc.localStream.switchDevice("video", id, console.log,console.log)

    console.log(id);
    }); */
	
    let let_option_not_selected = $('#cameraId option:not(:selected)').val();
	
    if(let_option_not_selected){
    $('#cameraId option[value="'+let_option_not_selected+'"]').prop('selected', true);
    //alert(let_option_not_selected);
    $('.camera-switch').toggleClass("active");
	 rtc.localStream.getVideoTrack().stop();
   // rtc.localStream.switchDevice("video",id);
   rtc.localStream.switchDevice("video", let_option_not_selected, console.log,console.log)
    }
}
$(document).ready(function(){
  //$('.camera-switch').show();
 if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
    // some code..
    $('.camera-switch').show();
   }

  /* Added on 15-july-2020 */
  if($('#remote-mic').val()=='1' && $('#my_user_id').val() !=$('#organizer_id').val() && $('#my_user_id').val() !=$('#now_presenting').val() && !JSON.parse($("#translators_id").val()).includes(parseInt($('#my_user_id').val())))
{
 
  setTimeout(function(){ $('#mute-audio').click()}, 3000);
   mic_muted_by_org = true;

}
if($('#remote-camera').val()=='1' && $('#my_user_id').val() !=$('#organizer_id').val() && $('#my_user_id').val() !=$('#now_presenting').val())
{
  
  setTimeout(function(){ $('#mute-video').click();}, 3000);
  video_muted_by_org = true;
}
if($('#remote-chat').val()=='1' && $('#my_user_id').val() !=$('#organizer_id').val() && $('#my_user_id').val() !=$('#now_presenting').val())
{
  
  setTimeout(function(){ $('#chat_control').hide();}, 3000);
}

if($('#remote-mic').val()=='1' && $('#my_user_id').val() ==$('#organizer_id').val())
{

  setTimeout(function(){ muteunmuteremote('all','mute','mic') }, 3000);
}
if($('#remote-camera').val()=='1' && $('#my_user_id').val() ==$('#organizer_id').val())
{
 
  setTimeout(function(){ muteunmuteremote('all','mute','video') }, 3000);
}
if($('#remote-chat').val()=='1' && $('#my_user_id').val() ==$('#organizer_id').val())
{
 
  setTimeout(function(){ muteunmuteremote('all','mute','chat') }, 3000);
}
/* Ended on 15-july-2020 */
	getMessageDb();
	if($('#recording_enable').val()=='1' && $('#my_user_id').val()==$('#organizer_id').val())
	{
		//stoprecordingRefresh();
		//stoprecording();
		setTimeout(function(){ startrecording(true); }, 7000);
	
  }
  if($('#recording_enable').val()=='1' && $('#my_user_id').val() !=$('#organizer_id').val())
	{
		$('.recording-user').show();
	
	}
	
});



