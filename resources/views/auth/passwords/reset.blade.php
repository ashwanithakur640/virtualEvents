@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card loginF">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form id="resetPasswordForm" method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                       <!--  <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> -->

                        <div class="form-group {{ $errors->has('email') ? 'error' : ''}}">
                            <label>Email</label>
                            <input id="emailInput" name="email" value="{{ $email ?? old('email') }}" placeholder="Email" class="form-control" type="email">
                            @if ($errors->has('email'))
                            <label class="help-block">{{ $errors->first('email') }}</label>
                            @endif

                        </div>

                        <!-- <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> -->

                        <div class="form-group {{ $errors->has('password') ? 'error' : ''}}">
                            <label>Password</label>
                            <input class="form-control" placeholder="Password" name="password" type="password" value="" id="password">
                            @if ($errors->has('password'))
                                <label class="help-block">{{ $errors->first('password') }}</label>
                            @endif

                        </div>

                        <!-- <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div> -->

                        <div class="form-group {{ $errors->has('password_confirmation') ? 'error' : ''}}">
                            <label>Confirm Password</label>
                            <input class="form-control" placeholder="Confirm Password" name="password_confirmation" type="password" value="" id="password-confirm">
                            @if ($errors->has('password_confirmation'))
                                <label class="help-block">{{ $errors->first('password_confirmation') }}</label>
                            @endif

                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12 text-center ">
                                <button type="submit" class="next-btn btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $.validator.addMethod(
        "validEmail", function (value, element) {
            var pattern = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
            return pattern.test(value);
        },'Please enter a valid email address.'
    );
    $('#resetPasswordForm').validate({
        rules : {
            email: {
                required: true,
                email: true,
                validEmail: true,
                maxlength: 40,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 16,
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                maxlength: 16,
                equalTo: "#password",
            },
        },
        messages:{
            email:{
                required: "Please enter your email.",
                maxlength: "Email may not be greater than 40 characters.",
            },
            password:{
                required: "Please enter your password.",
                minlength: "Password must be at least 8 characters.",
                maxlength: "Password may not be greater than 16 characters.",
            },
            password_confirmation:{
                required: "Please enter your confirm password.",
                minlength: "Confirm password must be at least 8 characters.",
                maxlength: "Confirm password may not be greater than 16 characters.",
                equalTo: "Password and confirm password does not match.",
            },
        },
    });
</script>
@endsection
