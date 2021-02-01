@if(\Request::segment(1) == 'vendor')
    @php
        $layout = 'vendor.layouts.app';
    @endphp
@elseif(isset($Prefix) && \Request::segment(1) == $Prefix)
    @php
        $layout = 'vendor.vendorLayout';
    @endphp 
@else
    @php
        $layout = 'layouts.app';
    @endphp
@endif    

@extends($layout)

@section('content')

@include('layouts.message')

<main>
    @if(!empty($data))
        {!! $data->description !!}    
    @endif

    <section class="padd-row conForm">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="heading_section">
                        <h2>Let's Keep in Touch</h2>
                    </div>
                </div>
                <div class="col-md-12">
                    @if(\Request::segment(1) == 'contact-us')
                    <form id="contactUs" action="{{ asset('contact-us') }}" method="POST">
                    @endif  

                    @if(\Request::segment(2) == 'contact-us')
                        @if(isset($Prefix))
                            @php
                                $vendor = $Prefix;
                            @endphp
                        @else
                            @php
                                $vendor = 'vendor';
                            @endphp
                        @endif
                
                        <form id="contactUs" action="{{ asset($vendor . '/contact-us') }}" method="POST">
                    @endif   
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('first_name') ? 'error' : ''}}">
                                    <input class="form-control" placeholder="First Name" name="first_name" type="text" value="{{ old('first_name') }}">
                                    @if ($errors->has('first_name'))
                                        <label class="help-block">{{ $errors->first('first_name') }}</label>
                                    @endif

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('middle_name') ? 'error' : ''}}">
                                    <input class="form-control" placeholder="Middle Name" name="middle_name" type="text" value="{{ old('middle_name') }}">
                                    @if ($errors->has('middle_name'))
                                        <label class="help-block">{{ $errors->first('middle_name') }}</label>
                                    @endif

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('last_name') ? 'error' : ''}}">
                                    <input class="form-control" placeholder="Last Name" name="last_name" type="text" value="{{ old('last_name') }}">
                                    @if ($errors->has('last_name'))
                                        <label class="help-block">{{ $errors->first('last_name') }}</label>
                                    @endif

                                </div>
                            </div>
                            <div class="col-md-6 {{ $errors->has('email') ? 'error' : ''}}">
                                <input type="text" class="form-control" name="email" placeholder="Email">
                                @if ($errors->has('email'))
                                    <label class="help-block">{{ $errors->first('email') }}</label>
                                @endif

                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('mobile') ? 'error' : ''}}">
                                    <input id="phone" class="form-control" name="mobile" type="tel" value="{{ isset($data->mobile) ? $data->mobile : old('mobile') }}">
                                    <span id="error-msg" class="hide error">Invalid phone number</span>
                                    <input type="hidden" name="country_code" value="{{ isset($data->country_code) ? $data->country_code : old('country_code') }}" class="selected-country-code"/>
                                    <input type="hidden" name="country_iso" value="{{ isset($data->country_iso) ? $data->country_iso : old('country_iso') }}" class="selected-country-iso"/>
                                    @if ($errors->has('mobile'))
                                        <label class="help-block">{{ $errors->first('mobile') }}</label>
                                    @endif

                                </div>
                            </div>
                            <div class="col-md-12 {{ $errors->has('mobile') ? 'error' : ''}}">
                                <textarea id="editor" class="form-control text-area" name="description" placeholder="How can we help you?"></textarea>
                                @if ($errors->has('description'))
                                    <label class="help-block">{{ $errors->first('description') }}</label>
                                @endif
                                
                            </div>
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn_sec">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
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
    var countryCode = "in";
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