@extends('layouts.app')

@section('content')
<main class="Session_hall">
    <section class="Session_section pt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4 pr-0">
                    <div class="left-side-demand res_que p-0">
                        <div class="resouce_question d-flex">
                            <h2><a href="{{url('edit-profile')}}">My profile</a></h2>
                            <h2><a href="{{url('my-events')}}">My events</a></h2>
                        </div>
                        <div class="welcome-faq text-center">
                            @if(isset($data->image) && !empty($data->image))
                            <img class ='profile-img' src="{{ asset('assets/images/profile_pic/'.$data->image) }}" alt="image">
                            @else
                            <img class ='profile-img' src="{{ asset('assets/images/profile_pic/user.png') }}" alt="image">
                            @endif
                            
                            <h2>{{$data->first_name}} {{$data->middle_name}} {{$data->last_name}}</h2>
                        </div>
                        <div class="demand_box">
                            <div class="individual_profile_text text_font">
                                <p class="mb-0">Email</p>
                                <p class="mb-0"><strong>{{$data->email}}</strong></p>
                            </div>
                            <div class="individual_profile_text">
                                <p class="mb-0">Mobile</p>
                                <p class="mb-0"><strong>{{$data->country_code}} {{$data->mobile}}</strong></p>
                            </div>
                            @if($data->company_name)
                            <div class="individual_profile_text text_font">
                                <p class="mb-0">Company Name</p>
                                <p class="mb-0"><strong>{{$data->company_name}}</strong></p>
                            </div>
                            @endif

                            @if($data->website)
                            <div class="individual_profile_text">
                                <p class="mb-0">Website</p>
                                <p class="mb-0"><strong>{{$data->website}}</strong></p>
                            </div>
                            @endif

                            @if($data->office_no)
                            <div class="individual_profile_text text_font">
                                <p class="mb-0">Office No</p>
                                <p class="mb-0"><strong>{{$data->office_no}}</strong></p>
                            </div>
                            @endif

                            @if($data->office_email_id)
                            <div class="individual_profile_text">
                                <p class="mb-0">Office Email ID</p>
                                <p class="mb-0"><strong>{{$data->office_email_id}}</strong></p>
                            </div>
                            @endif

                            <div class="cusPass">
                                <a href="{{url('change-password')}}">Change Password</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="help_faq resource-center">
                        @if(\Request::segment(1) == 'edit-profile')
                        <div class="welcome_heading text-center">
                            <h2>Edit Profile </h2>
                        </div>
                        <div class="individual_profile_text">
                            <div class="d-flex justify-content-between">
                                <form id="editCustomerProfile" action="{{ asset('update-profile') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                
                                    <div class="profile-input-outer">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="form-group ">
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
                                                        <span class="help-block">{{ $errors->first('first_name') }}</span>
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
                                                    <input class="form-control" placeholder="Email" name="email" type="email" value="{{ isset($data->email) ? $data->email : old('email') }}">
                                                    @if ($errors->has('email'))
                                                        <label class="help-block">{{ $errors->first('email') }}</label>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-md-6">
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
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Company Name</label>
                                                    <input type="text" class="form-control" name="company_name" placeholder="Company Name" value="{{ isset($data->company_name) ? $data->company_name : old('company_name') }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Company City Location</label>
                                                    <input type="text" class="form-control" name="company_city_location" placeholder="Company City Location" value="{{ isset($data->company_city_location) ? $data->company_city_location : old('company_city_location') }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>State</label>
                                                    <input type="text" class="form-control" name="state" placeholder="State" value="{{ isset($data->state) ? $data->state : old('state') }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Country</label>
                                                    <input type="text" class="form-control" name="country" placeholder="Country" value="{{ isset($data->country) ? $data->country : old('country') }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <textarea class="form-control" name="address">{{ isset($data->address) ? $data->address :old('address') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Website</label>
                                                    <input type="text" class="form-control" name="website" placeholder="Website" value="{{ isset($data->website) ? $data->website : old('website') }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Office No</label>
                                                    <input type="text" class="form-control" name="office_no" placeholder="Office No *" value="{{ isset($data->office_no) ? $data->office_no : old('office_no') }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Office Email ID</label>
                                                    <input type="text" class="form-control" name="office_email_id" placeholder="Office Email ID *" value="{{ isset($data->office_email_id) ? $data->office_email_id : old('office_email_id') }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group {{ $errors->has('image') ? 'error' : ''}}">
                                                    <input type="file" name="image">
                                                    <span class="">
                                                        <p><small><b>Note: </b><i>Only .jpg, .jpeg and .png format are accepted.</i></small></p>
                                                    </span>
                                                    @if ($errors->has('image'))
                                                        <label class="help-block">{{ $errors->first('image') }}</label>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-input-outer">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <div class="save-changes-btn">
                                                    <div class="form-actions border-none ">
                                                        <button type="submit" class="save-change-btn next-btn btn btn-primary">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif

                        @if(\Request::segment(1) == 'change-password')
                        <div class="welcome_heading text-center">
                            <h2>Change Password</h2>
                        </div>
                            <form id="changePasswordForm" action="{{ asset('change-password') }}" method="post">
                            @csrf 

                                <div class="form-group col-md-8 {{ $errors->has('current_password') ? 'error' : ''}}">
                                    <label>Current Password<span class="asterisk">*</span></label>
                                    <div class="">
                                       <input type="password" class="form-control" name="current_password" id="current_password" placeholder="**********">
                                        @if ($errors->has('current_password'))
                                            <label class="help-block">{{ $errors->first('current_password') }}</label>
                                        @endif

                                   </div>
                                </div>
                                <div class="form-group col-md-8 {{ $errors->has('new_password') ? 'error' : ''}}">
                                    <label>New Password<span class="asterisk">*</span></label>
                                    <div class="">
                                       <input id="new_password" type="password" class="form-control" name="new_password" id="new_password" placeholder="**********">
                                       @if ($errors->has('new_password'))
                                            <label class="help-block">{{ $errors->first('new_password') }}</label>
                                        @endif

                                    </div>
                                </div>
                                <div class="form-group col-md-8 {{ $errors->has('confirm_password') ? 'error' : ''}}">
                                    <label>Confirm Password<span class="asterisk">*</span></label>
                                    <div class="">
                                       <input type="password" class="form-control" name="confirm_password" placeholder="**********">
                                       @if ($errors->has('confirm_password'))
                                            <label class="help-block">{{ $errors->first('confirm_password') }}</label>
                                        @endif
                                        
                                    </div>
                                </div>
                                <div class="row col-md-12">
                                    <div class="col-md-8">
                                        <div class="save-changes-btn">
                                            <div class="form-actions border-none">
                                                <button type="submit" class="save-change-btn next-btn btn">Update Password</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif

                        @if(\Request::segment(1) == 'my-events')

                            
                            <div class="welcome_heading text-center">
                                <h2>My Events</h2>
                                <?php 

                              if(!empty($event)){



                                ?>
                                @foreach($events as $event)
                                    <div class="individual_profile_text">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                               <p class="mb-0">Event Name</p>
                                        <p class="mb-0"><a class="" href="{{ asset('exhibit-hall/event-detail/'.Helper::encrypt($event->id)) }}"> {{ $event->title }}</a></p>
                                            </div>
                                            <div>
                                               <p class="mb-0">Start Date</p>
                                        <p class="mb-0">{{ $event->start_date_time }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            <?php }else{
                                ?>
Not yet enrolled in any event
                                <?php
                            } ?>
                            </div>


                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('script')
<script src="{{ asset('js/changePasswordForm.js') }}"></script>
@if(\Request::segment(1) == 'edit-profile')
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
@endif

@endsection