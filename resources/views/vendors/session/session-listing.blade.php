@extends('vendors.adminLayout')

@section('content')

<section class="content- mb-2 d-flex align-items-center justify-content-between">
    <h4 class=" mb-0pull-left">Session</h4>
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
                            <th class="tableHeading">Event</th>
                            <th class="tableHeading">Date</th>
                            <th class="tableHeading">Start Time</th>
                            <th class="tableHeading">End Time</th>
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
            url: "{{ asset($Prefix .'/session-listings/') }}",
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
                data: 'event_name',
                name: 'event_name',
            },
            {
                data: 'date',
                name: 'date',
            },
            {
                data: 'start_time',
                name: 'start_time',
            },
            {
                data: 'end_time',
                name: 'end_time',
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