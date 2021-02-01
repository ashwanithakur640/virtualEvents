@extends('layouts.app')

@section('content')
<?php 
 	use Carbon\Carbon;
?>
<style>
.btn_part a {
    color: White !important;
}
</style>

@push('scripts')


@endpush
<main>
	<section class="event_page_pg">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="event_gg_pge">
						<div class="row align-items-center">
							<div class="col-md-12 mb-4">
								<div class="event_banner_igg">
									@if(isset($data['category']) & (isset( $data['category']->image)) )
										<img src="{{ asset('assets/images/categories/'.$data['category']->image) }}">
									@endif
										
								</div>
							</div>
							<div class="col-md-4">
								<div class="event_bx_gg">
									<ul>
										<li>
											<h2 class="mb-0">Event Name: <span>{{ $data->name }}</span></h2>
										</li>
										<li>
											<h2 class="mb-0">Event Title: <span>{{ $data->title }}</span></h2>
										</li>
									</ul>
								</div>
							</div>
							<div class="col-md-4 text-center">
								<div class="right_bx_event">
									<h1 class="price_text mb-0">
										@if($data->amount != 0.00)
											₹{{ $data->amount }}
										@else
											Free
										@endif

									</h1>
								</div>
							</div>
							<div class="col-md-4 text-right">
								<div class="right_bx_event">
									<div class="btn_part">
										@if($data->amount != 0.00)
											@if(empty($participated))
												<a id="participateButton" href="#eventPayment">Participate into Event</a>
											@else
										    	<a href="javascript:void(0);">Participated</a>
										    @endif
										@else
										    @if(empty($participated))
											<form action="{{ url('exhibit-hall/participate-into-event') }}" method="POST">
										    @csrf

										    	<input type="hidden" name="event_id" value="{{ $data->id }}">
										    	<button type="submit" class="btn btn-primary">Participate into Event</button>
										    </form> 
										    @else

												<?php
										    		if(  strtotime($data->start_date_time) >  strtotime("now")     ){
										    	?>
										    		<a id="getting-started"></a>
										    	<?php
										    	}else{

										    		if(  strtotime($data->end_date_time) < strtotime("now")     ){
										    		?> 
										    			<a href="javascript:void(0);">Event has ended</a>
										    		<?php
										    		}else{
										    			?>
										    			<a href="javascript:void(0);">Ongoing Scroll down to join session</a>
										    			<?php
										    		}
										    	} 
										    	?>
										    	
										    @endif
										@endif
										
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="event_bx_gg">
									<ul>
										<li>
											<h2>Event Description: </h2>
											<p>{!! $data->description !!}</p>
										</li>
									</ul>
								</div>
							</div>
						</div>
							<!--  -->
							<div class="row align-items-center" id="eventPayment" style="display: none;">
								<div class="col-md-12">
									<div class="payment_box">
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
												<form action="{{ url('exhibit-hall/participate-into-event') }}" method="POST">
										            @csrf 

										            <script
										                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
										                data-key="pk_test_51HZAf7LkM2YxAR2pEechQp2WSVjNvFT4JZuSImLAqwwo44mi8fdoNtITfU9qmOlWsweLStJKS5qYf2RlRhOtBLqg00ifkQkkMB"
										                data-amount="{{ number_format(($data->amount*100) , 0, '', '') }}"
										                data-email="{{ Auth::user()->email }}"
										                data-description="AmbiPlatforms"
										                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
										                data-locale="auto"
										                data-currency="inr"
										            > 
										            </script>
										            <input type="hidden" name="event_id" value="{{ $data->id }}">
										            <input type="hidden" name="amount" value="{{ $data->amount }}">
										        </form>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--  -->
							<div class="row align-items-center">
							<div class="col-md-12">
								<div class="category_box">
									 <a  data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" title="Click to view details">
    <h2>Category  <i class="fa fa-plus-circle" aria-hidden="true"></i></h2>
  </a>

									<div class="collapse" id="collapseExample">
										<div class="row">
											<div class="col-md-12">
												<div class="event_bx_gg">
													<ul>
														<li>
															<h2 class="mb-0">Category Name: 
																<span>
																	@if(isset($data['category'])) {{ $data['category']->name }} @endif
																</span>
															</h2>
														</li>
														<li>
															<h2>Category Description: </h2>
															<p>@if(isset($data['category'])) {!! $data['category']->description !!} @endif</p>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-12">
								<div class="session_box">
									<a  data-toggle="collapse" href="#collapseExample2" role="button" aria-expanded="false" aria-controls="collapseExample2" title="Click to view details"><h2>Session<i class="fa fa-plus-circle" aria-hidden="true"></i></h2>
  </a>

									@if(isset($data['session']) && !empty($data['session']))

										<div class="collapse" id="collapseExample2">
											@foreach( $data['session'] as $value)
											<div class="box_session mb-4">
												<div class="row">
													<div class="col-md-12">
														<h3>{{ $value->name }}  </h3>   
													</div>
													<div class="col-md-12">
														<h4>Date: <span>{{ $value->date }}</span></h4>
													</div>
													<div class="col-md-3">
														<h4>Start Time: <span>{{ $value->start_time }}</span></h4>
													</div>
													<div class="col-md-6">
														<h4>End Time: <span>{{ $value->end_time }}</span></h4>
													</div>
													<div class="col-md-3">

														<?php

														$ddm = $value->date.' '.$value->start_time; 
														//echo strtotime($ddm);
										    		if(  strtotime($ddm) >  strtotime("now")  ){
										    	?>
										    		<a class="getting-started" data-id="{{ date('Y-m-d H:i' , strtotime($value->date.''. $value->start_time) ) }}"></a>
										    	<?php
										    	}else{
										    		if(  strtotime($value->date.' '. $value->end_time) <  strtotime("now")     ){
										    		?> 
										    			<a href="javascript:void(0);">Session has ended</a>
										    		<?php
										    		}else{

										    			 $url = url('');
  $url .= '/webinar/'.$value->u_id;
										    			?>
										    			<a href="{{ $url }}">Join session</a>
										    			<?php
										    		}
										    	} 
										    	?>

														
													</div>

													<input type="hidden" class="rotationalPresenter" data-presenter-id="{{ $value->id }}" data-start-time="{{ date('Y-m-d H:i' , strtotime($value->day.''. $value->start_time) ) }}" data-end-time="{{ date('Y-m-d H:i' , strtotime($value->day.''. $value->end_time) ) }}" >


													<div class="col-md-12">
														<h4>Description: </h4>
														<p>{!! $value->description !!}</p>
													</div>
												</div>
											</div>
											@endforeach
										</div>

									@endif

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<input type="hidden" id= "timezone">

<input type="hidden" id="start_time" value="{{ date('Y-m-d H:i' , strtotime($data->start_date_time) ) }}">

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data-2012-2022.min.js"></script>


<script src="{{ asset('js/jquery.countdown.js') }}"></script>
<script>

	$( document ).ready(function() {



            var nextYear = moment.tz($('#start_time').val(), moment.tz.guess());


	  		$('#getting-started').countdown(nextYear.toDate(), function(event) {
	  				$(this).html(event.strftime('%D days %H:%M:%S'));
			});



			//getting-started


			$('.getting-started').each(function(i, obj) { 

				var th = moment.tz($(this).data('id'), moment.tz.guess());


		  		$(this).countdown(th.toDate(), function(event) {
		  				$(this).html(event.strftime('%D days %H:%M:%S'));
				});


                // if( i == ( $('.rotationalPresenter').length - 1) ){

                //     var previousEndTime = moment($('.rotationalPresenter').eq(i-1).data('end-time'),"HH:mm:ss");
                //     var conferenceEndTime = moment($('#conference_end_time').val(),"HH:mm:ss"); 

                //     var newStartTime = moment(previousEndTime).add(2, 'seconds').format("HH:mm:ss");

                //     if( conferenceEndTime.diff(previousEndTime,"seconds") > 10 ){

                //           $('<input type="hidden" class="rotationalPresenter" data-presenter-id="'+ $('#organizer_id').val() +'" data-start-time="' + newStartTime +'" data-end-time="'+ $('#conference_end_time').val() +'"  data-name="Admin"    data-minutes-diif="">').appendTo('body'); 

                //     }

                // } 

            });

        });
	$(document).on("click","#participateButton", function(){
		$('#eventPayment').show();
	});
</script>
@endsection