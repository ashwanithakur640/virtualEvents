@extends('superadmin.superadminLayout')

@section('content')
	<section class="content- mb-2 d-flex align-items-center justify-content-between">
        <h1 class=" mb-0pull-left"> Rescheduled Events</h1>
        
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
                                <th class="tableHeading">Vendor</th>
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
            url: "{{ asset('superadmin/events/rescheduled-events') }}",
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
                data: 'user_name',
                name: 'user_name',
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