@extends('admin.adminLayout')

@section('content')
	<section class="content- mb-2 d-flex align-items-center justify-content-between">
        <h1 class=" mb-0pull-left"> Rescheduled Events</h1>
        <h1 class="pull-right mb-0">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ asset('admin/events/create-event') }}">Add Event</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('layouts.message')

        <div class="clearfix"></div>
        <div class="box box-primary">

            <div class="box-body">
                <div class="card">
                <div class="table-responsive data_class">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th class="tableHeading">Id</th>
                                <th class="tableHeading">Name</th>
                                <th class="tableHeading">Title</th>
                                <th class="tableHeading">Type</th>
                                <th class="tableHeading">Amount</th>
                                <th class="tableHeading">Category</th>
                                <th class="tableHeading">Start Date & Time</th>
                                <th class="tableHeading">End Date & Time</th>
                                <th class="tableHeading">Status</th>
                                <th class="tableHeading">Action</th>
                            </tr>
                        </thead>
                        
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>

   
/* Start dataTable (using package) to make(true) */
$(document).ready(function(){

    
    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url: "{{ asset('admin/events/rescheduled-events') }}",
        },
        columns:[
            {
                data: 'id',
                id: 'id',
                visible: false,
            },
            {
                data: 'name',
                name: 'name',
            },
            {
                data: 'title',
                name: 'title',
            },
            {
                data: 'type',
                name: 'type',
            },
            {
                data: 'amount',
                name: 'amount',
            },
            {
                data: 'category_name',
                name: 'category_name',
            },
            {
                data: 'start_date_time',
                name: 'start_date_time',
            },
            {
                data: 'end_date_time',
                name: 'end_date_time',
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false
            }
        ],
        order: [[0, 'desc']],
    });
 
});

/* End dataTable (using package) to make(true) */
</script>  
@endsection