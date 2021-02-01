@extends('admin.adminLayout')
@section('content')
    <section class="content-header">
        <h1>Change Password</h1>
    </section>
    <div class="content">
        @include('layouts.message')

        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <form id="changePasswordForm" action="{{ asset('admin/change-password') }}" method="post">
                                    @csrf 

                	                <div class="row">
                	                    <div class="form-group col-sm-6 {{ $errors->has('current_password') ? 'error' : ''}}">
                                            <label>Current Password<span class="asterisk">*</span></label>
                	                        <div class="">
                	                           <input type="password" class="form-control" name="current_password" id="current_password" placeholder="**********">
                                                @if ($errors->has('current_password'))
                                                    <label class="help-block">{{ $errors->first('current_password') }}</label>
                                                @endif

                	                       </div>
                	                    </div>
                	                </div>
                	                <div class="row">
                	                    <div class="form-group col-md-6 {{ $errors->has('new_password') ? 'error' : ''}}">
                                            <label>New Password<span class="asterisk">*</span></label>
                	                        <div class="">
                	                           <input id="new_password" type="password" class="form-control" name="new_password" id="new_password" placeholder="**********">
                                               @if ($errors->has('new_password'))
                                                    <label class="help-block">{{ $errors->first('new_password') }}</label>
                                                @endif

                	                        </div>
                	                    </div>
                	                </div>
                	                <div class="row">
                	                    <div class="form-group col-sm-6 {{ $errors->has('confirm_password') ? 'error' : ''}}">
                                            <label>Confirm Password<span class="asterisk">*</span></label>
                	                        <div class="">
                	                           <input type="password" class="form-control" name="confirm_password" placeholder="**********">
                                               @if ($errors->has('confirm_password'))
                                                    <label class="help-block">{{ $errors->first('confirm_password') }}</label>
                                                @endif
                                                
                	                        </div>
                	                    </div>
                	                </div>
                                    <div class="buttons-outer password-reset">
                                    	<button type="submit" class="next-btn btn btn-primary">Update Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
               </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('js/changePasswordForm.js') }}"></script>

@endsection
