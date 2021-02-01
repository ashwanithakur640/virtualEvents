<ul class="navbar-nav ml-auto mt-2 mt-lg-0">

    <li class="nav-item {{ Request::is('about-us') ? 'active' : '' }}">
        <a class="nav-link" href="{{url('vendor/about-us')}}">About Us</a>
    </li>
    <li class="nav-item {{ Request::is('privacy-policy') ? 'active' : '' }}">
        <a class="nav-link" href="{{url('vendor/privacy-policy')}}">Privacy & Policy</a>
    </li>
    <li class="nav-item {{ Request::is('terms-conditions') ? 'active' : '' }}">
        <a class="nav-link" href="{{url('vendor/contact-us')}}">Contact Us</a>
    </li>
    <li class="nav-item {{ Request::is('terms-conditions') ? 'active' : '' }}">
        <a class="nav-link" href="{{url('vendor/help')}}">Help</a>
    </li>
  <!--   <li class="nav-item {{ Request::is('login') ? 'active' : '' }}">
        <a class="nav-link" href="{{url('vendor/login')}}">Login</a>
    </li> -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Login
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="{{ route('login') }}">Customer</a>
            <a class="dropdown-item" href="{{ asset('vendor/login') }}"> My Vendor</a>
        </div>
    </li>
    <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('Sign Up') }}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ route('register') }} ">Customer</a>
                                    <a class="dropdown-item" href="{{ asset('vendor/register') }}"> My Vendor</a> 
                                </div>
                            </li>

</ul>



