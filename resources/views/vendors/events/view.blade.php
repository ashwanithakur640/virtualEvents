@extends('vendors.adminLayout')

@section('content')
    <section class="content-header">
        <h1>View Event</h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-body">
                              
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Event Name</b></label>
                                            <p>{{ $data->name }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Event Title</b></label>
                                            <p>{{ $data->title }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Amount</b></label>
                                            <p>{{ $data->amount}}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>description</b></label>
                                            <p>{{ strip_tags($data->description)  }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Start Date Time</b></label>
                                            <p>{{ $data->start_date_time }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>End Date Time</b></label>
                                            <p>{{ $data->end_date_time }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Category</b></label>
                                            <p>{{ !empty($data->category_name) ? $data->category_name : 'N/A' }}</p>
                                        </div>
                                    </div>


                                   

                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Status</b></label>
                                            <p>{{ !empty($data->status) ? $data->status : 'N/A' }}</p>
                                        </div>
                                    </div>


                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Rescheduled</b></label>
                                            <p>{{ ($data->rescheduled == 0) ? 'NO' : 'Yes' }}</p>
                                        </div>
                                    </div>


                                    <?php 

                                        if($data->rescheduled == 1){
                                    ?>

                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Rescheduled Comments</b></label>
                                            <p>{!! $data->reschedule_comments  !!}</p>
                                        </div>
                                    </div>

                                    <?php
                                        }
                                    ?>
  <div class="form-group col-md-12">
                                    Change status
@if($data->status != 'Active')
    <a href="{{ route('change-status', ['id' => $data->id , 'status' => 'Active']) }}" class="btn btn-primary ml-3 mb-3 custom">
        Active
    </a>
@endif 

@if($data->status != 'Inactive')
    <a href="{{ route('change-status', ['id' =>$data->id , 'status' => 'Inactive']) }}" class="btn btn-primary ml-3 mb-3 custom">
        In Active
    </a>
@endif 

@if($data->status != 'Onhold')
    <a href="{{ route('change-status',  ['id' => $data->id, 'status' => 'Onhold']) }}" class="btn btn-primary ml-3 mb-3 custom">
        On Hold
    </a>
@endif     

@if($data->status != 'Cancelled')
    <a href="{{ route('change-status' ,  ['id' => $data->id, 'status' => 'Cancelled']) }}" class="btn btn-primary ml-3 mb-3 custom">
       Cancelled
    </a>
@endif     
</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection