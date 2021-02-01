<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            .error span, .error{
                color: red !important;
            }
            .loginF {
                max-width: 40%;
                padding: 35px;
                background-color: #fff;
                margin: auto;
                border: 4px double #c3c3c3 !important;
                margin: 60px auto;
            }
            .next-btn {
                    background-image: linear-gradient(#30377e, #4876f5) !important;
                border: 0px;
                color: #fff;
                padding: 11px 30px;
                display: block;
                border-radius: 0px;
            }
            .next-btn:hover {
                   background-image: linear-gradient(#4876f5, #30377e) !important;

                color: #fff;
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
            form#forgotPasswordForm fieldset .form-group:last-child {
                margin-bottom: 5px;
            }
        </style>
        <title>AmbiPlatforms</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    </head>
    <body class="fix-header fix-sidebar card-no-border">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel loginF panel-default">
                        @include('layouts.message')
                        
                        <div class="panel-body ">
                            <div class="text-center">
                                <h3><i class="fa fa-lock fa-4x"></i></h3>
                                <h2 class="text-center">Forgot Password?</h2>
                                <p>You can reset your password here.</p>
                                <div class="panel-body">
                                    <form id="forgotPasswordForm" class="form" action="{{ asset('vendor/forgot-password') }}" method="post">
                                        @csrf
                                        <fieldset>
                                            <div class=" form-group {{ $errors->has('email') ? 'error' : ''}}">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                                    <input id="emailInput" name="email" placeholder="Email address" class="form-control" type="email">
                                                </div>
                                                @if ($errors->has('email'))
                                                    <span class="help-block">{{ $errors->first('email') }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group mb-0">
                                                <input class="next-btn btn btn-lg btn-primary btn-block" value="Submit" type="submit">
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                                <a href="{{ asset('vendor/login') }}" class="for">Login</a>
                            </div>
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
            $('.alert-success').delay(5000).fadeOut('slow');
            $('.alert-warning').delay(5000).fadeOut('slow');
            $('.alert-danger').delay(5000).fadeOut('slow');
        </script>
    </body>
</html>