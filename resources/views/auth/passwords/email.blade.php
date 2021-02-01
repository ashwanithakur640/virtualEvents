@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="panel-body loginF">
                <div class="text-center">
                    <h3><i class="fa fa-lock fa-4x"></i></h3>
                    <h2 class="text-center">Forgot Password?</h2>
                    <p>You can reset your password here.</p>
                    <div class="panel-body mb-2">
                        <form id="forgotPasswordForm" class="form" action="{{ route('password.email') }}" method="post">
                            @csrf

                            <div class="form-group {{ $errors->has('email') ? 'error' : ''}}">
                                <input id="emailInput" name="email" placeholder="Email address" class="form-control" type="email">
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group mb-0">
                                <input class="btn btn-lg btn-primary btn-block next-btn" value="Send Password Reset Link" type="submit">
                            </div>
                        </form>
                    </div>
                    <a href="{{ asset('/login') }}" class="for">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/login.js') }}"></script>
@endsection
