@extends('vendors.adminLayout')

@section('content')
    <section class="content-header">
        <h1>View Detail</h1>
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

                                            <h6>{{ $data->first_name }} {{ $data->middle_name }} {{ $data->last_name }}</h6>
                                            <h6>{{ $data->email }}</h6>
                                            <h6>{{ $data->country_code }} {{ $data->mobile }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Company Name</b></label>
                                            <p>{{ !empty($data->company_name) ? $data->company_name : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Company City Location</b></label>
                                            <p>{{ !empty($data->company_city_location) ? $data->company_city_location : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>State</b></label>
                                            <p>{{ !empty($data->state) ? $data->state : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Country</b></label>
                                            <p>{{ !empty($data->country) ? $data->country : 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Address</b></label>
                                            <p>{{ !empty($data->address) ? $data->address : 'N/A' }}</p>
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

                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Total events attended</b></label>
                                            <p>{{ $count }}</p>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>



                            @if($data->role_id == 2)         
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
@endsection