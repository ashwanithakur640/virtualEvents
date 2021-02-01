<div class="row">

     <div class="col-md-3">
                                <div class="form-group {{ $errors->has('salutation') ? 'error' : ''}}">
                                    <label> Salutation<span class="asterisk">*</span> </label>
                                    <select name="salutation" id="salutation" class="form-control valid" aria-invalid="false"> 

                                                    <option value="Mr." 

                                                    <?php 

                                                        if(isset($data->salutation) && ( $data->salutation == 'Mr.' )){

                                                            echo ' selected';
                                                        }

                                                    ?>

                                                    >Mr.</option> 
                                                    <option value="Ms." <?php 

                                                        if(isset($data->salutation) && ( $data->salutation == 'Ms.' )){

                                                            echo ' selected';
                                                        }

                                                    ?>>Ms.</option> 
                                                    <option value="Mrs." <?php 

                                                        if(isset($data->salutation) && ( $data->salutation == 'Mrs.' )){

                                                            echo ' selected';
                                                        }

                                                    ?>>Mrs.</option> 

                                                </select>
                                

                                </div>
                            </div>

                            
    <div class="col-md-3">
        <div class="form-group {{ $errors->has('first_name') ? 'error' : ''}}">
            <label> First Name<span class="asterisk">*</span> </label>
            <input class="form-control" placeholder="First Name" name="first_name" type="text" value="{{ isset($data->first_name) ? $data->first_name : old('first_name') }}">
            @if ($errors->has('first_name'))
                <label class="help-block">{{ $errors->first('first_name') }}</label>
            @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group {{ $errors->has('middle_name') ? 'error' : ''}}">
            <label> Middle Name </label>
            <input class="form-control" placeholder="Middle Name" name="middle_name" type="text" value="{{ isset($data->middle_name) ? $data->middle_name : old('middle_name') }}">
            @if ($errors->has('middle_name'))
                <label class="help-block">{{ $errors->first('middle_name') }}</label>
            @endif

        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group {{ $errors->has('last_name') ? 'error' : ''}}">
            <label> Last Name </label>
            <input class="form-control" placeholder="Last Name" name="last_name" type="text" value="{{ isset($data->last_name) ? $data->last_name : old('last_name') }}">
            @if ($errors->has('last_name'))
                <label class="help-block">{{ $errors->first('last_name') }}</label>
            @endif

        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('email') ? 'error' : ''}}">
            <label>Email<span class="asterisk">*</span></label>
            <input class="form-control" placeholder="Email" name="email" type="email" value="{{ isset($data->email) ? $data->email : old('email') }}" autocomplete="off">
            @if ($errors->has('email'))
                <label class="help-block">{{ $errors->first('email') }}</label>
            @endif

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('mobile') ? 'error' : ''}}">
            <label>Mobile<span class="asterisk">*</span></label>
            <input id="phone" class="form-control" name="mobile" type="tel" value="{{ isset($data->mobile) ? $data->mobile : old('mobile') }}">
            <span id="error-msg" class="hide error">Invalid phone number</span>
            <input type="hidden" name="country_code" value="{{ isset($data->country_code) ? $data->country_code : old('country_code') }}" class="selected-country-code"/>
            <input type="hidden" name="country_iso" value="{{ isset($data->country_iso) ? $data->country_iso : old('country_iso') }}" class="selected-country-iso"/>
            @if ($errors->has('mobile'))
                <label class="help-block">{{ $errors->first('mobile') }}</label>
            @endif

        </div>
    </div>
</div>
@if(!isset($data) && empty($data))
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('password') ? 'error' : ''}}">
            <label>Password<span class="asterisk">*</span></label>
            <input class="form-control" placeholder="Password" name="password" type="password" value="" autocomplete="off" id="password">
            @if ($errors->has('password'))
                <label class="help-block">{{ $errors->first('password') }}</label>
            @endif

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('confirm_password') ? 'error' : ''}}">
            <label>Confirm Password<span class="asterisk">*</span></label>
            <input class="form-control" placeholder="Confirm Password" name="confirm_password" type="password" autocomplete="off" value="">
            @if ($errors->has('confirm_password'))
                <label class="help-block">{{ $errors->first('confirm_password') }}</label>
            @endif

        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('company_name') ? 'error' : ''}}">
            <label>Company Name<span class="asterisk">*</span></label>
            <input class="form-control" placeholder="Company Name" name="company_name" type="text" value="{{ isset($data->company_name) ? $data->company_name : old('company_name') }}">
            @if ($errors->has('company_name'))
                <label class="help-block">{{ $errors->first('company_name') }}</label>
            @endif

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('company_city_location') ? 'error' : ''}}">
            <label>Company City Location<span class="asterisk">*</span></label>
            <input class="form-control" placeholder="Company City Location" name="company_city_location" type="text" value="{{ isset($data->company_city_location) ? $data->company_city_location : old('company_city_location') }}">
            @if ($errors->has('company_city_location'))
                <label class="help-block">{{ $errors->first('company_city_location') }}</label>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('state') ? 'error' : ''}}">
            <label>State<span class="asterisk">*</span></label>
            <input class="form-control" placeholder="State" name="state" type="text" value="{{ isset($data->state) ? $data->state : old('state') }}">
            @if ($errors->has('state'))
                <label class="help-block">{{ $errors->first('state') }}</label>
            @endif

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('country') ? 'error' : ''}}">
            <label>Country<span class="asterisk">*</span></label>
            <input class="form-control" placeholder="Country" name="country" type="text" value="{{ isset($data->country) ? $data->country : old('country') }}">
            @if ($errors->has('country'))
                <label class="help-block">{{ $errors->first('country') }}</label>
            @endif

        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('image') ? 'error' : ''}}">
            <label>Image</label>
            <input class="form-control upload_doc" name="image" type="file">
            <span class="">
                <p><small><b>Note: </b><i>Only .jpg, .jpeg and .png format are accepted.</i></small></p>
            </span>
            @if ($errors->has('image'))
                <label class="help-block">{{ $errors->first('image') }}</label>
            @endif

        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group {{ $errors->has('address') ? 'error' : ''}}">
            <label>Address</label>
            <textarea class="form-control" name="address">{{ isset($data->address) ? $data->address :old('address') }}</textarea>
            @if ($errors->has('address'))
                <label class="help-block">{{ $errors->first('address') }}</label>
            @endif

        </div>
    </div>
</div>





<div class="form-actions border-none">
    <button type="submit" class="btn btn-primary">Save</button>
    <a class="btn btn-warning mr-1" href="{{ asset($Prefix . '/participants/' )}}">Cancel</a>
</div>
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
