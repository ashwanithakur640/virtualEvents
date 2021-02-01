<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            .error span, .error{
                color: red !important;
            }
            .form-gap {
                padding-top: 70px;
            }
            label#passwordlInput-error, #confirmPassword-error {
                position: absolute;
                width: 100%;
                left: 0;
                bottom: -29px;
            }
            form#resetPasswordForm fieldset .form-group {
                margin-bottom: 29px;
            }
        </style>
        <title>AmbiPlatforms</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    </head>
    <body class="fix-header fix-sidebar card-no-border">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="text-center">
                                <h3><i class="fa fa-lock fa-4x"></i></h3>
                                <h2 class="text-center">Reset Password</h2>
                                <div class="panel-body">
                                    <form id="resetPasswordForm" class="form" action="{{ asset('superadmin/reset-password/'.$securityToken) }}" method="post">
                                        @csrf
                                        <fieldset>
                                            <div class="form-group {{ $errors->has('password') ? 'error' : ''}}">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                                    <input id="passwordlInput" name="password" placeholder="New password" class="form-control" type="password">
                                                </div>
                                                @if ($errors->has('password'))
                                                    <span class="help-block">{{ $errors->first('password') }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                                    <input id="confirmPassword" name="confirm_password" placeholder="Re-enter password" class="form-control" type="password">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input class="btn btn-lg btn-primary btn-block" value="Submit" type="submit">
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                                <a href="{{ asset('superadmin/login') }}" class="for">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{asset('js/jquery.min.js')}}"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>
        <!-- Start jquery validate js -->
        <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('assets/js/additional-validate-methods.min.js') }}"></script>
        <!-- End jquery validate js -->
        <script src="{{ asset('js/login.js') }}"></script>
    </body>
</html>