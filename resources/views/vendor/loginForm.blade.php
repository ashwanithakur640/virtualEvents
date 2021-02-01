@extends('vendor.layouts.app')

@section('content')

<div class="login-outer">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="login-inner">
                    <div class="login-title">

                        <a href="{{ asset('/') }}">
                            <img class="" style="width: 100px; height: auto" src="{{ asset('images/logo.jpg') }}" alt="image">
                        </a>
                    </div>
                    <form id="loginForm" method="post" action="{{ asset('vendor/login') }}">
                        @csrf

                        <div class="login-input has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label>Email<span class="asterisk">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="john@email.com" autocomplete="off">
                            @if ($errors->has('email'))
                            <span class="help-block" style="color: red">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif

                        </div>

                        <div class="login-input password-div has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label>Password<span class="asterisk">*</span></label>
                            <input type="password" name="password" placeholder="***********" autocomplete="off">
                            @if ($errors->has('password'))
                            <span class="help-block" style="color: red">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif  

                        </div>
                        <a href="{{ asset('vendor/forgot-password') }}" class="forgot-email-title mt-3">Forgot password ?</a>
                        <div class="buttons-outer">
                            <button type="submit" class="next-btn">Sign in</button>
                        </div>
                    </form>                 
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('js/login.js') }}"></script>
@endsection