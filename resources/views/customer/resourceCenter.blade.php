@extends('layouts.app')

@section('content')
<main class="Session_hall">
	<section class="Session_section pt-3">
		<div class="container">
			<div class="row">
				<div class="col-md-4 pr-0">
					<div class="left-side-demand res_que p-0">
						<div class="resouce_question d-flex">
							<h2>Conference</h2>
							<h2>Resource Listing </h2>
						</div>
						<div class="welcome-faq">

							<select size="1" id="events">
						       <option value='future'>Upcoming Events</option>
						       <option value='past'>Past Events</option>
						    </select>


							<h2 class="title">Upcoming Session </h2>
						</div>
						<div class="demand_box">
							@if(count($upcomingEvents) > 0)
								@foreach($upcomingEvents as $value)
									<div class="tt_btx pb-3">
										<h5>{{ $value->name }}</h5>
										<p class="mb-0">{{ $value->start_date_time }}    |    - {{ $value->end_date_time }}</p>
										<div class="btn_session">
											<?php
                                            // if(strtotime(Carbon\Carbon::now()) >= strtotime($value->date . ' ' .$value->start_time) ){
                                            //     $sessionStartButton = '';
                                            // } else{
                                            //     $sessionStartButton = 'disabled';
                                            // }
                                            ?>
											<a class="" href="{{ asset('exhibit-hall/event-detail/'.Helper::encrypt($value->id)) }}">View Details</a>
										</div>
									</div>
								@endforeach
							@else
                            	<p style="text-align: center;">No data found</p>
							@endif	

						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="help_faq resource-center">
						<div class="welcome_heading">
							<h2>Welcome to Resource Center </h2>
						</div>
						<div class="ddd" >
						@if($upcomingEvents->count() > 0)
							@foreach($upcomingEvents as $key => $value)
								@if($key % 2 == 0)
									@php
										$color = 'resource_ctn_box';
									@endphp
								@else
									@php
										$color = 'gld_ctn_box';
									@endphp
								@endif
							<div class="resdata">
								<div class="{{ $color }}">
									<h2>{{ $value->name }} <span> {{ $value->start_date_time }}</span></h2>
									<p class="mb-0">{{ $value->title }}</p>
									<p class="mb-2">{!! Illuminate\Support\Str::limit($value->description, 200) !!}</p>
									<div class="side_rit">
										<span class="price_text mb-0">
											@if($value->amount != 0.00)
												₹{{ $value->amount }}
											@else
												Free
											@endif

										</span>
										<?php 
											$participated = \DB::table('event_participants')->where([['user_id', Auth::user()->id], ['event_id', $value->id]])->pluck('id')->first();
										?>
										@if($value->amount != 0.00)
											@if(empty($participated))
												<a class="participateButton partic_btn" href="#eventPayment">Participate into Event</a>
											@else
										    	<a class="partic_btn" href="javascript:void(0);">Participated</a>
										    @endif
										@else
										    @if(empty($participated))
											<form action="{{ url('resource-center/participate-into-event') }}" method="POST">
										    @csrf

										    	<input type="hidden" name="event_id" value="{{ $value->id }}">
										    	<button type="submit" class="btn btn-primary">Participate into Event</button>
										    </form> 
										    @else
										    	<a class="partic_btn" href="javascript:void(0);">Participated</a>
										    @endif
										@endif

									</div>

									@if($value->amount != 0.00)
									<div class="row align-items-center eventPayment" style="display: none">
										<div class="col-md-12">
											<div class="payment_box resource_payment">
												<div class="row">
													<div class="col-md-12">
														<h2>Payment</h2>
														<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
														<ul>
															<li><a herf="#"><img src="{{ asset('images/payment-1.png') }}"></a></li>
															<li><a herf="#"><img src="{{ asset('images/payment-2.png') }}"></a></li>
															<li><a herf="#"><img src="{{ asset('images/payment-3.png') }}"></a></li>
															<li><a herf="#"><img src="{{ asset('images/payment-4.png') }}"></a></li>
														</ul>
														<form action="{{ url('resource-center/participate-into-event') }}" method="POST">
												            @csrf 

												            <script
												                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
												                data-key="pk_test_51HZAf7LkM2YxAR2pEechQp2WSVjNvFT4JZuSImLAqwwo44mi8fdoNtITfU9qmOlWsweLStJKS5qYf2RlRhOtBLqg00ifkQkkMB"
												                data-amount="{{ number_format(($value->amount*100) , 0, '', '') }}"
												                data-email="{{ Auth::user()->email }}"
												                data-description="AmbiPlatforms"
												                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
												                data-locale="auto"
												                data-currency="inr"
												            > 
												            </script>
												            <input type="hidden" name="event_id" value="{{ $value->id }}">
												            <input type="hidden" name="amount" value="{{ $value->amount }}">
												        </form>
													</div>
												</div>
											</div>
										</div>
									</div>
									@endif

								</div>
							@endforeach

						</div>
						@else
                            <p style="text-align: center;">No data found</p>	
						@endif

					</div>

					</div>
				</div>
			</div>
		</div>
	</section>
</main>
@endsection

@section('script')
<script>

	var ajaxurl = '{{route("past-events")}}'; 

	$(document).on("click",".participateButton", function(){
		$('.eventPayment').hide();
		$(this).parent('div').next().show();
	});

	 $("#events").change(function() {
	 	
	 	var im = $('option:selected', this).val();

	 	if($('option:selected', this).val() == 'past'){
			$('.title').html('');
			$('.title').html('Past Events');
		}else{
			$('.title').html('');
			$('.title').html('Upcoming Events');
		}

        $.ajax({
        	url: ajaxurl, 
        	type: "POST",
		    data: {
		      "_token": "{{ csrf_token() }}",
		      "type": $('option:selected', this).val()
		    },
		    headers: {
		      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    },

        	success: function(result){

	    		var result = jQuery.parseJSON(result);
	    		$('.demand_box').html('');
	    		$('.demand_box').html(result.left);

	    		$('.ddd').html('');
	    		$('.ddd').html(result.right);

  			}
  		});

    });

</script>
@endsection