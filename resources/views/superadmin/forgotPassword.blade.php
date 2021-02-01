<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            .error span, .error{
                color: red !important;
            }
            label#emailInput-error {
                position: absolute;
                width: 100%;
                left: 0;
                bottom: -29px;
            }
            form#forgotPasswordForm fieldset .form-group:first-child {
                margin-bottom: 29px;
            }
        </style>
        <title>AmbiPlatforms</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link href="{{ asset('assets/css/admin/login.css') }}" rel="stylesheet">
    </head>
    <body class="fix-header fix-sidebar card-no-border">
<!--         <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-default"> -->
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
                                <h2 class="text-center main-forgot-title">Forgot Password?</h2>
                                <p class="forgot-text">You can reset your password here.</p>
                            </div>
                            <form id="loginForm" action="{{ asset('/superadmin/forgot-password') }}" method="post">
                                @csrf

                                <div class="login-input has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label>Email<span class="asterisk">*</span></label>
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="john@email.com">
                                    @if ($errors->has('email'))
                                        <span class="help-block" style="color: red">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif

                                </div>

                                <div class="buttons-outer">
                                    <button type="submit" class="next-btn">Submit</button>
                                </div>
                                <a href="{{ asset('/superadmin/login') }}" class="for login-text">Login</a>
                            </form>                 
                        </div>
                    </div>
                </div>
            </div>
        </div>
                        
<!--                         <div class="panel-body">
                            <div class="text-center">
                                <h3><i class="fa fa-lock fa-4x"></i></h3>
                                <h2 class="text-center">Forgot Password?</h2>
                                <p>You can reset your password here.</p>
                                <div class="panel-body">
                                    <form id="forgotPasswordForm" class="form" action="{{ asset('admin/forgot-password') }}" method="post">
                                        @csrf
                                        <fieldset>
                                            <div class="form-group {{ $errors->has('email') ? 'error' : ''}}">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                                    <input id="emailInput" name="email" placeholder="Email address" class="form-control" type="email">
                                                </div>
                                                @if ($errors->has('email'))
                                                    <span class="help-block">{{ $errors->first('email') }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <input class="btn btn-lg btn-primary btn-block" value="Submit" type="submit">
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                                <a href="{{ asset('admin/login') }}" class="for">Login</a>
                            </div>
                        </div> -->
<!--                     </div>
                </div>
            </div>
        </div> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- Start jquery validate js -->
        <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('assets/js/additional-validate-methods.min.js') }}"></script>
        <!-- End jquery validate js -->
        <script src="{{ asset('js/login.js') }}"></script>
        <script>
            $('.alert-success').delay(5000).fadeOut('slow');
            $('.alert-warning').delay(5000).fadeOut('slow');
            $('.alert-danger').delay(5000).fadeOut('slow');
        </script>
    </body>
</html>