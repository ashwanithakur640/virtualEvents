<!DOCTYPE html>
<html lang="en">
    <head>
        <title>AmbiPlatforms</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link href="{{ asset('assets/css/admin/login.css') }}" rel="stylesheet">
    </head>
    <body>
        @include('layouts.message')

        <div class="login-outer">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 offset-md-2">
                        <div class="login-inner">
                            <div class="login-title">
                                <a href="{{ asset('/') }}">
                                    <img class="login_logo" src="{{ asset('images/logo.jpg') }}" alt="image">
                                </a>
                            </div>
                            <form id="loginForm" action="{{ URL::route('do-login') }} " method="POSt">
                                @csrf
                                <input type="hidden" name="param" value="{{$param}}">
                                <div class="login-input has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label>Email<span class="asterisk">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="john@email.com">
                                    @if ($errors->has('email'))
                                        <span class="help-block" style="color: red">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif

                                </div>

                                <div class="login-input password-div has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label>Password<span class="asterisk">*</span></label>
                                    <input type="password" name="password" placeholder="***********">
                                    @if ($errors->has('password'))
                                        <span class="help-block" style="color: red">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif   

                                </div>
                                <div class="right-side-forget">
                                    <a href="{{ asset('admin/forgot-password') }}" class="for">Forgot password ?</a>
                                </div>
                                <div class="buttons-outer">
                                    <button type="submit" class="next-btn">Login</button>
                                </div>
                            </form>                 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- Start jquery validate js -->
        <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('assets/js/additional-validate-methods.min.js') }}"></script>
        <!-- End jquery validate js -->
        <script src="{{ asset('js/login.js') }}"></script>
        <script>
            /* Start alert message fade out */
            $('.alert-success').delay(5000).fadeOut('slow');
            $('.alert-warning').delay(5000).fadeOut('slow');
            $('.alert-danger').delay(5000).fadeOut('slow');
            /* End alert message fade out */
        </script>
    </body>
</html>