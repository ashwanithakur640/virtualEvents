@extends('vendor.vendorLayout')

@section('content')

@include('layouts.message')

<section class="content- mb-2 d-flex align-items-center justify-content-between">
    <h4 class=" mb-0pull-left">Dashboard</h4>
</section>
<div class="card p-3">
	<div class="row">
		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-primary shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="{{ asset($Prefix . '/events/') }}">
								<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Events</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userEvents }}</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fa fa-calendar fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="{{ asset($Prefix . '/session/') }}">
								<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Session</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userSession }}</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fa fa-barcode fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="{{ asset($Prefix . '/events/weekly-events') }}">
								<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Events in current Week</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $weekEvents }}</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fa fa-barcode fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="{{ asset($Prefix . '/events/future-events') }}">
								<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Future Event</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $futureEvents }}</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fa fa-barcode fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="{{ asset($Prefix . '/events/past-events') }}">
								<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Past Event</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pastEvents }}</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fa fa-barcode fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="{{ asset($Prefix . '/events/hold-events') }}">
								<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Hold Event</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $holdEvents }}</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fa fa-barcode fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="{{ asset($Prefix . '/events/rescheduled-events/') }}">
								<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Rescheduled Event</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $rescheduledEvents }}</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fa fa-barcode fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="{{ asset($Prefix . '/events/today-events/') }}">
								<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Today's Event</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayEvents }}</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fa fa-barcode fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- <div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="#">
								<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Session</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userSession }}</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fa fa-barcode fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-4 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<a href="#">
								<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Session</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userSession }}</div>
							</a>
						</div>
						<div class="col-auto">
							<i class="fa fa-barcode fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div> -->

		

	</div>
</div>
@if(empty($data->company_name))
<div class="modal fade model_onload" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button> -->
			<div class="modal-body">
				<section class="onload">
					<div class=" register">
						<form id="addProfileForm" action="{{ asset($Prefix . '/add-profile') }}" method="post">
                        @csrf

							<div class="row align-items-center">
								<div class="col-md-3 register-left">
									<h3>Welcome</h3>
									<!-- <input type="submit" name="" value="Login"/><br/> -->
									<button type="submit" class="next-btn btn btn-primary">Continue</button>
								</div>
								<div class="col-md-9 register-right">
									<div class="row register-form">
										<div class="col-md-6">
											<div class="form-group {{ $errors->has('company_name') ? 'error' : ''}}">
												<label class="">Company Name<span class="asterisk">*</span></label>
												<input type="text" class="form-control" name="company_name" placeholder="Company Name" value="{{ old('company_name') }}" />
												@if ($errors->has('company_name'))
	                                                <label class="help-block">{{ $errors->first('company_name') }}</label>
	                                            @endif

											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group {{ $errors->has('company_city_location') ? 'error' : ''}}">
												<label class="">Company City Location<span class="asterisk">*</span></label>
												<input type="text" class="form-control" name="company_city_location" placeholder="Company City Location" value="{{ old('company_city_location') }}" />
												@if ($errors->has('company_city_location'))
	                                                <label class="help-block">{{ $errors->first('company_city_location') }}</label>
	                                            @endif

											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group {{ $errors->has('state') ? 'error' : ''}}">
												<label class="">State<span class="asterisk">*</span></label>
												<input type="text" class="form-control" name="state" placeholder=" State" value="{{ old('state') }}" />
												@if ($errors->has('state'))
	                                                <label class="help-block">{{ $errors->first('state') }}</label>
	                                            @endif

											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group {{ $errors->has('country') ? 'error' : ''}}">
												<label class="">Country<span class="asterisk">*</span></label>
												<input type="text" class="form-control" name="country" placeholder="Country" value="{{ old('country') }}" />
												@if ($errors->has('country'))
	                                                <label class="help-block">{{ $errors->first('country') }}</label>
	                                            @endif

											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="">Address</label>
												<textarea class="form-control" name="address">{{ old('address') }}</textarea>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="">Website</label>
												<input type="text" class="form-control" name="website" placeholder="Website" value="{{ old('website') }}" />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="">Office No</label>
												<input type="text" class="form-control" name="office_no" placeholder="Office No" value="{{ old('office_no') }}" />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="">Office Email ID</label>
												<input type="text" class="form-control" name="office_email_id" placeholder="Office Email ID" value="{{ old('office_email_id') }}" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>
@endif

@endsection

@section('script')
<script>
  	$(window).on('load',function(){
       setTimeout(function(){ 
       		$('#exampleModal').modal('show');
       }, 3000);
   	});
</script>
<script>
    $(document).ready(function(){
		$("#exampleModal").modal({
			show:false,
			backdrop:'static'
		});
	});
</script>
@endsection