console.log("agora sdk version: " + AgoraRTC.VERSION + " compatible: " + AgoraRTC.checkSystemRequirements());
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
        Toast.error("Please Enter " + key);
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

function addView (id, show) {
  if (!$("#" + id)[0]) {
    $("<div/>", {
      id: "remote_video_panel_" + id,
      class: "video-view",
    }).appendTo("#video");

    $("<div/>", {
      id: "remote_video_" + id,
      class: "video-placeholder",
    }).html(id)
    .appendTo("#remote_video_panel_" + id);

    $("<div/>", {
      id: "remote_video_info_" + id,
      class: "video-profile " + (show ? "" :  ""),
    }).html(id)
    .appendTo("#remote_video_panel_" + id);

    $("<div/>", {
      id: "video_autoplay_"+ id,
      class: "autoplay-fallback",
    }).appendTo("#remote_video_panel_" + id);
  }
}
function removeView (id) {
  if ($("#remote_video_panel_" + id)[0]) {
    $("#remote_video_panel_"+id).remove();
  }
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
    console.log(err)
  })
  // Occurs when the peer user leaves the channel; for example, the peer user calls Client.leave.
  rtc.client.on("peer-leave", function (evt) {
    var id = evt.uid;
    console.log("id", evt);
    if (id != rtc.params.uid) {
      removeView(id);
    }
    Toast.notice("peer leave")
    console.log('peer-leave', id);
  })
  // Occurs when the local stream is published.
  rtc.client.on("stream-published", function (evt) {
    Toast.notice("stream published success")
    console.log("stream-published");
  })

  rtc.client.on('peer-online', function(evt) {
    console.log('peer-online', evt.uid);
    Toast.notice("user online "+evt.uid)

    $.ajax({url: "http://localhost/virtual_confrence/public/getUser/"+evt.uid, success: function(result){
      var jonee = JSON.parse(result);
      $("#userlisting").append(jonee.name);
    }});
  });

  // Occurs when the remote stream is added.
  rtc.client.on("stream-added", function (evt) {  
    var remoteStream = evt.stream;
    var id = remoteStream.getId();
    Toast.info("stream-added uid: " + id)
    if (id !== rtc.params.uid) {
      rtc.client.subscribe(remoteStream, function (err) {
        console.log("stream subscribe failed", err);
      })
    }
    console.log('stream-added remote-uid: ', id);
  });
  // Occurs when a user subscribes to a remote stream.
  rtc.client.on("stream-subscribed", function (evt) {
    var remoteStream = evt.stream;
    console.log(remoteStream,"=====remoteStream")
    var id = remoteStream.getId();
    rtc.remoteStreams.push(remoteStream);
    addView(id);



    remoteStream.play("remote_video_" + id);
    Toast.info('stream-subscribed remote-uid: ' + id);
    console.log('stream-subscribed remote-uid: ', id);
  })
  // Occurs when the remote stream is removed; for example, a peer user calls Client.unpublish.
  rtc.client.on("stream-removed", function (evt) {
    var remoteStream = evt.stream;
    var id = remoteStream.getId();
    Toast.info("stream-removed uid: " + id)
    remoteStream.stop("remote_video_" + id);
    rtc.remoteStreams = rtc.remoteStreams.filter(function (stream) {
      return stream.getId() !== id
    })
    removeView(id);
    console.log('stream-removed remote-uid: ', id);
  })
  rtc.client.on("onTokenPrivilegeWillExpire", function(){
    // After requesting a new token
    // rtc.client.renewToken(token);
    Toast.info("onTokenPrivilegeWillExpire")
    console.log("onTokenPrivilegeWillExpire")
  });
  rtc.client.on("onTokenPrivilegeDidExpire", function(){
    // After requesting a new token
    // client.renewToken(token);
    Toast.info("onTokenPrivilegeDidExpire")
    console.log("onTokenPrivilegeDidExpire")
  })

  rtc.client.on("mute-audio", function(evt){
    var stream = evt.stream;
    // Mutes the remote stream.
    $("#mute-audio").hide();
    $("#unmute-audio").show();
    stream.muteAudio();
    Toast.info("mute-audio")
    console.log("mute-audio")
  })

  rtc.client.on("unmute-audio", function(evt){
    var stream = evt.stream;
    // Mutes the remote stream.
    $("#mute-audio").show();
    $("#unmute-audio").hide();
    stream.unmuteAudio();
    Toast.info("unmute-audio")
    console.log("unmute-audio")
  })

  rtc.client.on("mute-video", function(evt){
    var stream = evt.stream;

    var remoteStream = evt.stream;
    console.log(remoteStream,"=====remoteStream")
    var id = remoteStream.getId();
    
    // Mutes the remote stream.
    $("#mute-video").hide();
    $("#unmute-video").show();
    stream.disableVideo();
    Toast.info("mute-video-")
    console.log("mute-video-"+id)
  })

  rtc.client.on("unmute-video", function(evt){
    var stream = evt.stream;
    // Mutes the remote stream.
    $("#mute-video").show();
    $("#unmute-video").hide();
    stream.enableVideo();
    Toast.info("unmute-video")
    console.log("unmute-video")
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
function join (rtc, option) {
  if (rtc.joined) {
    Toast.error("Your already joined");
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
    console.log("init success");

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
      Toast.notice("join channel: " + option.channel + " success, uid: " + uid);
      console.log("join channel: " + option.channel + " success, uid: " + uid);
      rtc.joined = true;

      rtc.params.uid = uid;

      // create local stream
      rtc.localStream = AgoraRTC.createStream({
        streamID: rtc.params.uid,
        audio: true,
        video: true,
        screen: false,
        microphoneId: option.microphoneId,
        cameraId: option.cameraId
      })

      // init local stream
      rtc.localStream.init(function () {
        console.log("init local stream success");
        // play stream with html element id "local_stream"
        rtc.localStream.play("local_stream")

        // publish local stream
        publish(rtc);
      }, function (err)  {
        Toast.error("stream init failed, please open console see more detail")
        console.error("init local stream failed ", err);
      })
    }, function(err) {
      Toast.error("client join failed, please open console see more detail")
      console.error("client join failed", err)
    })
  }, (err) => {
    Toast.error("client init failed, please open console see more detail")
    console.error(err);
  });
}

function publish (rtc) {
  if (!rtc.client) {
    Toast.error("Please Join Room First");
    return;
  }
  if (rtc.published) {
    Toast.error("Your already published");
    return;
  }
  var oldState = rtc.published;

  // publish localStream
  rtc.client.publish(rtc.localStream, function (err) {
    rtc.published = oldState;
    console.log("publish failed");
    Toast.error("publish failed")
    console.error(err);
  })
  Toast.info("publish")
  rtc.published = true
}

function unpublish (rtc) {
  if (!rtc.client) {
    Toast.error("Please Join Room First");
    return;
  }
  if (!rtc.published) {
    Toast.error("Your didn't publish");
    return;
  }
  var oldState = rtc.published;
  rtc.client.unpublish(rtc.localStream, function (err) {
    rtc.published = oldState;
    console.log("unpublish failed");
    Toast.error("unpublish failed");
    console.error(err);
  })
  Toast.info("unpublish")
  rtc.published = false;
}

function leave (rtc) {
  if (!rtc.client) {
    Toast.error("Please Join First!");
    return;
  }
  if (!rtc.joined) {
    Toast.error("You are not in channel");
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
    console.log("client leaves channel success");
    rtc.published = false;
    rtc.joined = false;
    Toast.notice("leave success");
  }, function (err) {
    console.log("channel leave failed");
    Toast.error("leave success");
    console.error(err);
  })
}


function muteaudio (rtc) {
  if (!rtc.client) {
    Toast.error("Please Join Room First");
    return;
  }
  rtc.localStream.muteAudio();
  Toast.notice("muted"); 
}


function unmuteaudio (rtc) {
  if (!rtc.client) {
    Toast.error("Please Join Room First");
    return;
  }
  rtc.localStream.unmuteAudio();
  Toast.notice("unmuted"); 
}



function mutevideo (rtc) {
  if (!rtc.client) {
    Toast.error("Please Join Room First");
    return;
  }
 // rtc.localStream.disableVideo();

  while (rtc.remoteStreams.length > 0) {
      var stream = rtc.remoteStreams.shift();
      var id = stream.getId();
      if(id==17) rtc.localStream.disableVideo();
      //removeView(id);
    }

   

  Toast.notice("muted"); 
}


function unmutevideo (rtc) {
  if (!rtc.client) {
    Toast.error("Please Join Room First");
    return;
  }
  rtc.localStream.enableVideo();
  Toast.notice("unmuted"); 
}


$(function () {
    var user = $("#user").val();
    user = JSON.parse(user);
    console.log(user.name,"====user");
    $("#uid").val(user.id);


    
    
  getDevices(async function (devices) {
    console.log("===getDevices");
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
    M.AutoInit();

    var confernce_params = [];
    confernce_params['appID'] = "b768c01946084f6bbd1c0747ae407da0";
    confernce_params['cameraId'] = $("#cameraId").val();
    confernce_params['cameraResolution'] = "default";
    confernce_params['channel'] = "test";
    confernce_params['codec'] = "h264";
    confernce_params['microphoneId'] = $("#microphoneId").val();
    confernce_params['mode'] = "live";
    confernce_params['uid'] = user.id;

    var params = serializeformData();
    console.log(params,"===confernce_params");
    if (validator(params, fields)) {
      join(rtc, confernce_params);
    }

  })

  
    

  var fields = ['appID', 'channel'];

  $("#join").on("click", function (e) {

    console.log("join")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      join(rtc, params);
    }
  })

  $("#publish").on("click", function (e) {
    console.log("publish")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      publish(rtc);
    }
  });

  $("#unpublish").on("click", function (e) {
    console.log("unpublish")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      unpublish(rtc);
    }
  });

  $("#leave").on("click", function (e) {
    console.log("leave")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      leave(rtc);
    }
  });


  $("#mute-audio").on("click", function (e) {
    console.log("#mute-audio")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      muteaudio(rtc);
    }
  })


  $("#unmute-audio").on("click", function (e) {
    console.log("#unmute-audio")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      unmuteaudio(rtc);
    }
  })

  $("#mute-video").on("click", function (e) {
    console.log("#mute-video")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      mutevideo(rtc);
    }
  })


  $("#unmute-video").on("click", function (e) {
    console.log("#unmute-video")
    e.preventDefault();
    var params = serializeformData();
    if (validator(params, fields)) {
      unmutevideo(rtc);
    }
  })

})