@extends('superadmin.superadminLayout')
@section('content')
    <section class="content-header">
        <h1>My Profile</h1>
    </section>
    <div class="content">
        @include('layouts.message')

        <div class="box box-primary">
            <div class="box-body">
                <div class="row">  
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <form id="editAdminProfileForm" action="{{ asset('superadmin/update-profile') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                    <div class="row">
                                        <div class="col-md-12 col-lg-5 left_side_profile">
                                            <div class="form-group {{ $errors->has('image') ? 'error' : ''}}">
                                                @if(isset($data->image) && !empty($data->image))
                                                <img class ='preview_img' src="{{ asset('assets/images/profile_pic/'.$data->image) }}" alt="image">
                                                @else
                                                <img class ='preview_img' src="{{ asset('assets/images/profile_pic/user.png') }}" alt="image">
                                                @endif

                                                <input type="file" name="image">

                                                <span class="">
                                                    <p><small><b>Note: </b><i>Only .jpg, .jpeg and .png format are accepted.</i></small></p>
                                                </span>
                                                @if ($errors->has('image'))

                                                    <label class="help-block">{{ $errors->first('image') }}</label>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-7">
                                            <div class="form-group {{ $errors->has('first_name') ? 'error' : ''}}">
                                                <label> First Name<span class="asterisk">*</span> </label>
                                                <input class="form-control" placeholder="First Name" name="first_name" type="text" value="{{ isset($data->first_name) ? $data->first_name : old('first_name') }}">
                                                @if ($errors->has('first_name'))

                                                    <span class="help-block">{{ $errors->first('first_name') }}</span>

                                                @endif
                                            </div>
                                            <div class="form-group {{ $errors->has('middle_name') ? 'error' : ''}}">
                                                <label> Middle Name </label>
                                                <input class="form-control" placeholder="Middle Name" name="middle_name" type="text" value="{{ isset($data->middle_name) ? $data->middle_name : old('middle_name') }}">
                                                @if ($errors->has('middle_name'))

                                                    <label class="help-block">{{ $errors->first('middle_name') }}</label>

                                                @endif
                                            </div>
                                            <div class="form-group {{ $errors->has('last_name') ? 'error' : ''}}">
                                                <label> Last Name </label>
                                                <input class="form-control" placeholder="Last Name" name="last_name" type="text" value="{{ isset($data->last_name) ? $data->last_name : old('last_name') }}">
                                                @if ($errors->has('last_name'))

                                                    <label class="help-block">{{ $errors->first('last_name') }}</label>

                                                @endif
                                            </div>
                                            <div class="form-group {{ $errors->has('email') ? 'error' : ''}}">
                                                <label>Email<span class="asterisk">*</span></label>
                                                <input class="form-control" placeholder="Email" name="email" type="email" value="{{ isset($data->email) ? $data->email : old('email') }}">
                                                @if ($errors->has('last_name'))

                                                    <label class="help-block">{{ $errors->first('email') }}</label>

                                                @endif
                                            </div>
                                            <div class="form-group {{ $errors->has('mobile') ? 'error' : ''}}">
                                                <label class="">Mobile<span class="asterisk">*</span></label>
                                                <input id="phone" class="form-control" name="mobile" type="tel" value="{{ isset($data->mobile) ? $data->mobile : old('mobile') }}">
                                                <span id="error-msg" class="hide error">Invalid phone number</span>
                                                <input type="hidden" name="country_code" value="{{ isset($data->country_code) ? $data->country_code : old('country_code') }}" class="selected-country-code"/>
                                                <input type="hidden" name="country_iso" value="{{ isset($data->country_iso) ? $data->country_iso : old('country_iso') }}" class="selected-country-iso"/>
                                                @if ($errors->has('mobile'))

                                                    <label class="help-block">{{ $errors->first('mobile') }}</label>
                                                    
                                                @endif
                                            </div>
                                            <div class="form-actions border-none">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                <a class="btn btn-warning mr-1" href="{{ asset('superadmin/dashboard') }}">Cancel</a>
                                            </div>
                                        </div>
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
<!-- start IntlInputPhone -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/css/intlTelInput.css" rel="stylesheet" media="screen">
<!-- <link rel="stylesheet" href="{{ asset('assets/IntlInputPhone/css/intlTelInput.css') }}"> -->
<script src="{{ asset('assets/IntlInputPhone/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/IntlInputPhone/js/intlTelInput.min.js') }}"></script>
<script src="{{ asset('assets/IntlInputPhone/js/utils.js') }}"></script>
<script>
    var telInput = $("#phone");
    var input = telInput;
    var countryCode = '<?php echo !empty($data) ? $data->country_iso : "in" ; ?>';
    input.intlTelInput({
        initialCountry: countryCode,
        dropdownContainer: document.body,
        allowExtensions: true,
        formatOnDisplay: true,
        autoFormat: true,
        autoHideDialCode: true,
        autoPlaceholder: true,
        defaultCountry: "auto",
        ipinfoToken: "yolo",
        nationalMode: true,
        numberType: "MOBILE",
        preferredCountries: false,
        preventInvalidNumbers: true,
        separateDialCode: true,
        geoIpLookup: function(callback) {
            $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                var countryCode = (resp && resp.country) ? resp.country : "";
                callback(countryCode);
            });
        },
        separateDialCode: true,
        utilsScript: "{{ asset('assets/IntlInputPhone/js/utils.js') }}"
    });
    function reset() {
        var classTest = $('.iti-flag').attr('class');
        var res = classTest.replace('iti-flag', '');
        var countryCode = $('.selected-dial-code').html();
        telInput.removeClass("error");
        $('.selected-country-code').val(countryCode);
        $('.selected-country-iso').val(res);
    };
    telInput.keyup(function() {
        if (telInput.val()) {
            $("#error-msg").addClass("hide");
            reset();
            if ($.trim(telInput.val())) {
                var currentFormat = (telInput.val()[0] === "+") ? intlTelInputUtils.numberFormat.INTERNATIONAL : intlTelInputUtils.numberFormat.NATIONAL;
                if (telInput.intlTelInput("isValidNumber") == true) {
                    telInput.val((telInput.intlTelInput("getNumber", currentFormat)));
                    $('button[type="submit"]').removeAttr('disabled');
                } else{
                    telInput.addClass("error");
                    $('button[type="submit"]').attr('disabled','disabled');
                    $("#error-msg").removeClass("hide");
                }
            }
        }
    });
    telInput.on("keyup change", reset);
    $(document).on('click','.country-list .country',function(){
        $('#phone').val('');
    });
</script>
<!-- End IntlInputPhone -->
@endsection