@extends('vendors.adminLayout')
@section('content')
@include('layouts.message')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>
<div class="row">
	<div class="col-xl-4 col-md-6 mb-4">
		<div class="card border-left-primary shadow h-100 py-2">
			<div class="card-body">
				<a href="{{ url($Prefix.'/participants') }}">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Participants</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $vendors }}</div>
						</div>
						<div class="col-auto">
							<i class="fa fa-user fa-2x text-gray-300"></i>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
	
	<div class="col-xl-4 col-md-6 mb-4">
		<div class="card border-left-info shadow h-100 py-2">
			<div class="card-body">
				<a href="{{ url($Prefix.'/events') }}">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Events</div>
							<div class="row no-gutters align-items-center">
								<div class="col-auto">
									<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $events }}</div>
								</div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fa fa-calendar fa-2x text-gray-300"></i>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>

	<div class="col-xl-4 col-md-6 mb-4">
		<div class="card border-left-info shadow h-100 py-2">
			<div class="card-body">
				<a href="{{ url($Prefix.'/today-events') }}">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Today's Events</div>
							<div class="row no-gutters align-items-center">
								<div class="col-auto">
									<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $todayEvents }}</div>
								</div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fa fa-calendar fa-2x text-gray-300"></i>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>


	<div class="col-xl-4 col-md-6 mb-4">
		<div class="card border-left-info shadow h-100 py-2">
			<div class="card-body">
				<a href="{{ url($Prefix.'/weekly-events') }}">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Events in current Week</div>
							<div class="row no-gutters align-items-center">
								<div class="col-auto">
									<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $weekEvents }}</div>
								</div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fa fa-calendar fa-2x text-gray-300"></i>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>


	<div class="col-xl-4 col-md-6 mb-4">
		<div class="card border-left-info shadow h-100 py-2">
			<div class="card-body">
				<a href="{{ url($Prefix.'/future-events') }}">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Future Event</div>
							<div class="row no-gutters align-items-center">
								<div class="col-auto">
									<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $futureEvents }}</div>
								</div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fa fa-calendar fa-2x text-gray-300"></i>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>

	<div class="col-xl-4 col-md-6 mb-4">
		<div class="card border-left-info shadow h-100 py-2">
			<div class="card-body">
				<a href="{{ url($Prefix.'/past-events') }}">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Past Event</div>
							<div class="row no-gutters align-items-center">
								<div class="col-auto">
									<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $pastEvents }}</div>
								</div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fa fa-calendar fa-2x text-gray-300"></i>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>



	<div class="col-xl-4 col-md-6 mb-4">
		<div class="card border-left-info shadow h-100 py-2">
			<div class="card-body">
				<a href="{{ url($Prefix.'/hold-events') }}">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Hold Event</div>
							<div class="row no-gutters align-items-center">
								<div class="col-auto">
									<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $holdEvents }}</div>
								</div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fa fa-calendar fa-2x text-gray-300"></i>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>

	<div class="col-xl-4 col-md-6 mb-4">
		<div class="card border-left-info shadow h-100 py-2">
			<div class="card-body">
				<a href="{{ url($Prefix.'/rescheduled-events') }}">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rescheduled Event</div>
							<div class="row no-gutters align-items-center">
								<div class="col-auto">
									<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $rescheduledEvents }}</div>
								</div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fa fa-calendar fa-2x text-gray-300"></i>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>



</div>


<div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Live Events</h6>
                 
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  @forelse($todayEventsDetails as $today)
                      
 						<p class="text-gray-900 p-3 m-0"><a href= <?= url("admin/events/show-event/".Helper::encrypt($today->id)) ?> >{{$today->title}}</a></p>
                           
                        @empty
                        	<p class="text-gray-900 p-3 m-0">No Events for today</p>
                        @endforelse
                </div>
              </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Events for today</h6>
               
                </div>
                <!-- Card Body -->
                <div class="card-body">

                  	@forelse($todayEventsDetails as $today)
                      
 						<p class="text-gray-900 p-3 m-0"><a href= <?= url("admin/events/show-event/".Helper::encrypt($today->id)) ?> >{{$today->title}}</a></p>
                           
                        @empty
                        	<p class="text-gray-900 p-3 m-0">No Events for today</p>
                        @endforelse
                 
                
                </div>
              </div>
            </div>
          </div>
@endsection