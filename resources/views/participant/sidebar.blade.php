<div class="horizontal-menu">
    <nav class="navbar top-navbar">
        <div class="container">

            <div class="navbar-content">
                <a href="{{ asset($Prefix . '/participant-dashboard') }}"><img class="" style="width: 100px; height: auto" src="{{ asset('images/logo.jpg') }}" alt="image"></a>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown nav-profile">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if(isset(Auth::user()->image) && !empty(Auth::user()->image))
                            <img class="img-profile rounded-circle" src="{{ asset('assets/images/profile_pic/'.Auth::user()->image) }}" alt="image">
                            @else
                            <img class="img-profile rounded-circle" src="{{ asset('assets/images/profile_pic/user.png') }}" alt="image">
                            @endif

                        </a>
                        <div class="dropdown-menu" aria-labelledby="profileDropdown">
                            <div class="dropdown-header d-flex flex-column align-items-center">
                                <div class="figure mb-3">
                                    @if(isset(Auth::user()->image) && !empty(Auth::user()->image))
                                    <img class="img-profile rounded-circle" src="{{ asset('assets/images/profile_pic/'.Auth::user()->image) }}" alt="image">
                                    @else
                                    <img class="img-profile rounded-circle" src="{{ asset('assets/images/profile_pic/user.png') }}" alt="image">
                                    @endif

                                </div>
                                <div class="info text-center">
                                    <p class="name font-weight-bold mb-0">{{ Auth::user()->first_name}} {{ Auth::user()->middle_name}} {{ Auth::user()->last_name}}</p>
                                    <p class="email text-muted mb-3">{{ Auth::user()->email}}</p>
                                </div>
                            </div>
                            <div class="dropdown-body">
                                <ul class="profile-nav p-0 pt-3">
                                    <li class="nav-item {{ \Request::segment(2) == 'edit-profile' ? 'active' : ''}}">
                                        <!-- <a href="{{ url('vendor/edit-profile') }}" class="nav-link"> -->
                                        <a href="{{ url($Prefix . '/profile') }}" class="nav-link">
                                            <i class="fa fa-user"></i>
                                            <span>My Profile</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ \Request::segment(2) == 'change-password' ? 'active' : ''}}">
                                        <!-- <a href="{{ url('vendor/change-password') }}" class="nav-link"> -->
                                        <a href="{{ url($Prefix . '/changing-password') }}" class="nav-link">
                                            <i class="fa fa-lock"></i>
                                            <span>Change Password</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <!-- <a href="{{ url('vendor/logout') }}" class="nav-link"> -->
                                        <a href="{{ url($Prefix . '/logout') }}" class="nav-link">
                                            <i class="fa fa-power-off"></i>
                                            <span>Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
                </button>
            </div>
        </div>
    </nav>
    <nav class="bottom-navbar">
        <div class="container">
            <ul class="nav page-navigation">
                <li class="nav-item {{ \Request::segment(2) == 'participant-dashboard' ? 'active' : ''}}">
                    <!-- <a class="nav-link" href="{{ asset('vendor/dashboard') }}"> -->
                    <a class="nav-link" href="{{ asset($Prefix . '/participant-dashboard') }}">
                        <i class="link-icon fa fa-tachometer"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>

               
                <li class="nav-item ">
                    <a class="nav-link" href="{{ asset($Prefix . '/my-events') }}">
                        <span class="menu-title">My Events</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>