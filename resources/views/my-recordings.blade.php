@extends('layouts.app')
@section('content')
<section class="virtual-outer conf-history">
        <div class="container">
           
				 <div class="loader" style="display:none;"><img src="{{URL::asset('images/loader.gif')}}"></div>
				 <div id="video-output">
				  <div class="row">
                <div class="col-md-12 col-sm-12"> 
                    <div class="my-conference-title video-title">
                        Recordings
                         {{-- <span class="blue-upload"> <a href="{{url('add-video')}}" class="layer20 left-radius"> <i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload Video </a> </span> --}}
                         </div>

                    <div class="row">
					@forelse($videos as $video)
					
					 <div class="col-md-4 col-sm-6"> 
                            <div class="video-box1"> 
							@php
							$video_folder = explode('.',$video->video);
							@endphp
                            {{-- 
                                <div class="video-img_div video-img_div_recording" data-toggle="modal" data-target="#exampleModal" data-video-recording="{{$video->id}}">
                                --}}
                                <a href="{{url('get-video-recording')}}/{{$video->id}}">
                                    @if(!empty($video->image))
                                        <img src="{{URL::asset('storage/conference-images')}}/{{$video->image}}" alt=""  class="videoimage" style="width:100%" >
                                       @else
                                            <img src="{{URL::asset('images/con1.jpg')}}" alt=""  class="videoimage" style="width:100%" >
                                           @endif


                                    <div class="middle">
                                      <div class="text"> <img src="{{URL::asset('images/video-play.png')}}" alt="" class="video-play"></div>
                                    </div>
                                    </a>
                                {{-- 
                                </div>
                                --}}
                              
															<!-- ----------- -->
                                <div class="profile-content px-0 pt-3 pb-0">
                                    <p>{{$video->title}} </p>
                                    <div class="profile_div">
                                        {{-- <a href="javascript:;"> <i class="fa fa-user-circle" aria-hidden="true"></i>{{$video->author}}</a>
                                        <span class="ml-auto mb-0">{{$video->views}} {{($video->views <= '1')?'View':'Views'}} </span> --}}
                                    </div>
                                </div>
                               
                            </div>
                        </div>
					@empty
					  <div class="virtual-inner1">
                       
						<div class="row">
						<div class="col-md-12 col-sm-12">
						<div class="my-conference-inner">
						<div class="nothing-found">
						<div class="not-found-left">
						<img src="{{URL::asset('images/sad-face.png')}}" alt="">
						</div>
						<div class="not-found-right">
						<p>No Recording found.</p>
						<a href="{{url('create-conference')}}">Click Here</a> to create conference
						</div>
						</div>
						</div>
						</div>
						</div>
						
					 </div>
					@endforelse
                      
                    </div>
                
               




            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="pagination-div">
				
                    </div>
                </div>
            </div>
        </div>
		</div>
		</div>
    </section>
    <div id="video-loader"></div>
	@push('scripts')
	<script>
	$(document).on('hidden.bs.modal','#videoModal', function () {
	var vid = document.getElementById("my-video");
vid.pause();
});
$(document).on("change","input[type='radio']",function(){
    // Do something interesting here
	var avarage_score = 0;
	$("input:checked").each(function () {
   
	  avarage_score += parseInt($(this).val());
    });
	$('#total-scores').html('Total Score: '+(avarage_score/6).toFixed(2));
	//alert(avarage_score/6);
});
</script>
@endpush

   @endsection
    