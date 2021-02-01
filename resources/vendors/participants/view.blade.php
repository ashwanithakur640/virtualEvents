<?php
    use App\User;
?>
@extends('vendors.adminLayout')

@section('content')
    <section class="content-header">
        <h1>View Vendor</h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-lg-5 left_side_profile">
                                        <div class="form-group {{ $errors->has('image') ? 'error' : ''}}">
                                            @if(isset($data->image) && !empty($data->image))
                                            <img class ='preview_img' src="{{ asset('assets/images/profile_pic/'.$data->image) }}" alt="image">
                                            @else
                                            <img class ='preview_img' src="{{ asset('assets/images/profile_pic/user.png') }}" alt="image">
                                            @endif

                                            <h3>{{ $data->first_name }} {{ $data->middle_name }} {{ $data->last_name }}</h3>
                                            <h5>{{ $data->email }}</h5>
                                            <h6>{{ $data->country_code }} {{ $data->mobile }}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-7">
                                        <div class="form-group">
                                            <!-- <label> Short Video </label> -->
                                            @if(isset($data['vendor_details']) && !empty($data['vendor_details']))
                                            <div class="thumb_div"> 
                                                <video width="480" height="260" controls>
                                                    <source src="{{ asset('assets/short_videos/'.$data['vendor_details']->short_video) }}" type="video/mp4">Your browser does not support the video tag.
                                                </video>
                                            </div>
                                            @endif 

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Company Name</b></label>
                                            <p>{{ $data->company_name }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Company City Location</b></label>
                                            <p>{{ $data->company_city_location }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>State</b></label>
                                            <p>{{ $data->state }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Country</b></label>
                                            <p>{{ $data->country }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Company Business Domain</b></label>
                                            <p>{{ isset($data['vendor_details']) ? $data['vendor_details']->company_business_domain : '' }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Website</b></label>
                                            <p>{{ !empty($data->website) ? $data->website : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Office No</b></label>
                                            <p>{{ !empty($data->office_no) ? $data->office_no : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Office Email ID</b></label>
                                            <p>{{ !empty($data->office_email_id) ? $data->office_email_id : 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <div class="border_text">
                                            <label><b>Address</b></label>
                                            <p>{{ !empty($data->address) ? $data->address : 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            @if($data->role_id == 1)         
       <a href="{{ route('change-state', $data->id) }}" class="btn btn-primary ml-3 mb-3 custom">

 @if($data->status == 'Active')
            Make Inactive
            @else
                @if($data->status == 'Inactive' || $data->status == 'Not-verified' )
            Make Active
             @endif
        @endif
        </a>    
@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection