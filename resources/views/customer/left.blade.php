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