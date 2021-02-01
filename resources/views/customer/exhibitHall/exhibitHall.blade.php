@extends('layouts.app')

@section('content')
<main>
	<section class="exhibit-hall-page">
		<img src="{{asset('images/Exhibit-Hall.jpg')}}">
		<div class="video_exhibit_hall_video">
			<img src="{{asset('images/Exhibit-Hall-video-handal.png')}}">
			<div class="video_insider_exhibit">
				<img src="{{asset('images/Exhibit-Hall-video-video.png')}}">
				<div class="heading_section_exhibit">
					<h2>Virtual Event for IT Companies  Aug 09-11</h2>
					<p>Data Literacy, and Architecture</p>
					<img src="{{asset('images/play_iocon.png')}}">
				</div>
			</div>
		</div>
		<div id="dynamicContent">
			<?php $count=1 ?>
			@if(isset($data) && !empty($data))
				@foreach($data as $event)
				<div class="notice_stand notice_{{ $count }}">
					<a href="{{ asset('exhibit-hall/detail/'.Helper::encrypt($event->id)) }}" data-toggle="tooltip" title="View Detail">
						<div class="position-relative">
							<img src="{{asset('images/Exhibit-Hall-notic-stand.png')}}">
						
<div class="logo_exhibit_hall">
                @if(isset($event->image) && !empty($event->image))
                                            <img  src="{{ asset('assets/images/profile_pic/'.$event->image) }}" alt="image">
                                            @else
                                            <img  src="{{ asset('assets/images/profile_pic/user.png') }}" alt="image">
                                            @endif
              </div>
						
							<div class="heading_notice">
								<h2>{{ $event->first_name }}<span>{{  $event->company_name  }}</span></h2>
							</div>
						</div>
					</a>
				</div>
				<?php $count++; ?>
				@endforeach
			@endif

			{!! $data->render() !!}
		</div>
		<div class="walk_in_talk">
			<img src="{{asset('images/Exhibit-Hall-ptp-1.png')}}" class="exhibit_postition_1">
			<img src="{{asset('images/Exhibit-Hall-ptp-2.png')}}" class="exhibit_postition_2">
			<img src="{{asset('images/Exhibit-Hall-ptp-3.png')}}" class="exhibit_postition_3">
			<img src="{{asset('images/Exhibit-Hall-ptp-4.png')}}" class="exhibit_postition_4">
			<img src="{{asset('images/Exhibit-Hall-ptp-5.png')}}" class="exhibit_postition_5">
			<img src="{{asset('images/Exhibit-Hall-ptp-6.png')}}" class="exhibit_postition_6">
			<img src="{{asset('images/Exhibit-Hall-ptp-7.png')}}" class="exhibit_postition_7">
			<img src="{{asset('images/Exhibit-Hall-ptp-8.png')}}" class="exhibit_postition_8">
			<img src="{{asset('images/Exhibit-Hall-ptp-9.png')}}" class="exhibit_postition_9">
		</div>
	</section>
</main>
@endsection

@section('script')
<script>
/* Start custom pagination */
$(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');  
    $.ajax({
        type: 'post',
        url: url,
        data: { "_token": "{{ csrf_token() }}" },
        success: function(response){
        	console.log(response);
            if (response) {
                $('#dynamicContent').html(response);
            }
        }
    });
});
/* End custom pagination */
</script>
@endsection