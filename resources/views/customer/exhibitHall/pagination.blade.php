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