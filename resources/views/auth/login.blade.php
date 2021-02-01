@extends('layouts.app')

@section('content')
<div class="container">
     @include('layouts.message')
     
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card loginF">
                <a  href="{{ asset('/') }}"><div class="card-header">{{ __('Login') }}</div></a>

                <div class="card-body">
                    <form id="loginForm" method="POST" action="{{ route('login') }}">
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

                        <div class="login-input password-div has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label>Password<span class="asterisk">*</span></label>
                            <input type="password" name="password" placeholder="***********">
                            @if ($errors->has('password'))
                            <span class="help-block" style="color: red">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif  

                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="next-btn btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                                @if (Route::has('password.request'))
                                    <a class="pl-0 btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot password ?') }}
                                    </a>
                                @endif

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
<script src="{{ asset('js/login.js') }}"></script>
@endsection