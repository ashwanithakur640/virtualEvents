<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
	<a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ asset('/employee/dashboard') }}">
		<div class="sidebar-brand-text mx-3 toggle_logo">
			<img class="header_logo" src="{{ asset('images/logo.jpg') }}" alt="image">
		</div>
	</a>
	<hr class="sidebar-divider my-0">
	<li class="nav-item {{ \Request::segment(2) == 'dashboard' ? 'active' : ''}}">
		<a class="nav-link" href="{{ asset('employee/dashboard') }}">
		<i class="fas fa-fw fa-tachometer-alt"></i>
		<span>Dashboard</span></a>
	</li>
	
	<li class="nav-item {{ \Request::segment(2) == 'set-payment-amount' ? 'active' : ''}}">
		<a class="nav-link" href="#">
		<i class="fas fa-fw fa-mobile"></i>
		<span>Chat</span></a>
	</li>
	
	<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	</div>
</ul>
