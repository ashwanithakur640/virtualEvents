<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
	<a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ asset('/admin/dashboard') }}">
		<div class="sidebar-brand-text mx-3 toggle_logo">
			<img class="header_logo" src="{{ asset('images/logo.jpg') }}" alt="image">
		</div>
	</a>
	<hr class="sidebar-divider my-0">
	<li class="nav-item {{ \Request::segment(2) == 'dashboard' ? 'active' : ''}}">
		<a class="nav-link" href="{{ asset('admin/dashboard') }}">
		<i class="fas fa-fw fa-tachometer-alt"></i>
		<span>Dashboard</span></a>
	</li>
	<!-- <hr class="sidebar-divider"> -->
	<li class="nav-item {{ \Request::segment(2) == 'participants' || \Request::segment(2) == 'end-user' ? 'active' : ''}}">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
			<i class="fas fa-fw fa-users"></i>
			<span>Manage Users</span>
		</a>
		<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<h6 class="collapse-header">Users</h6>
				<a class="collapse-item" href="{{ url($Prefix . '/participants') }}">Participants</a>
				<a class="collapse-item" href="{{ url($Prefix . '/end-user') }}">End Users</a>
			</div>
		</div>
	</li>
	
	<li class="nav-item {{ \Request::segment(2) == 'categories' ? 'active' : ''}}">
		<a class="nav-link" href="{{ url($Prefix . '/categories/') }}">
		<i class="fas fa-fw fa-bars"></i>
		<span>Manage Categories</span></a>
	</li>
	<li class="nav-item {{ \Request::segment(2) == 'events' ? 'active' : ''}}">
		<a class="nav-link" href="{{ url($Prefix . '/events') }}">
		<i class="fas fa-fw fa-calendar"></i>
		<span>Manage Events</span></a>
	</li>

	<li class="nav-item {{ \Request::segment(2) == 'contact-us' || \Request::segment(2) == 'faq' ? 'active' : ''}}">
		<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
			<i class="fas fa-fw fa-edit"></i>
			<span>Manage Report</span>
		</a>
		<div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
			<div class="bg-white py-2 collapse-inner rounded">
				<h6 class="collapse-header">Pages</h6>
				<a class="collapse-item" href="{{ url($Prefix . '/contact-us-report') }} ">Contact Us</a>
				<a class="collapse-item" href="{{ url($Prefix . '/faq-report') }}">FAQ</a>
			</div>
		</div>
	</li>
	<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	</div>
</ul>
