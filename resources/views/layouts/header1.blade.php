<header class="custom-header landing_page">
    <div class="container-fluid">
        <div class="header_Landing_section d-flex justify-content-between align-items-center">
            <div class="left-side">
                <a class="navbar-brand" href="{{ asset('/') }}">AmbiPlatforms</a>
            </div>
            <div class="right-side">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ asset('/') }}">Home</a>
                            </li>

                            <li class="nav-item active">
                                <a class="nav-link" href="{{ asset('/about-us') }}">About Us</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ asset('/privacy-policy') }}">Privacy & Policy</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ asset('/contact-us') }}">Contact Us</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ asset('/help') }}">Help</a>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('Login') }}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ route('login') }}">Customer</a>
                                    <a class="dropdown-item" href="{{ asset('vendor/login') }}"> Vendor</a> 
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


                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>