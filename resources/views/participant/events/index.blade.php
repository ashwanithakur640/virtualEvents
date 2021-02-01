@extends('vendors.adminLayout')

@section('content')
	<section class="content- mb-2 d-flex align-items-center justify-content-between">
        <h1 class=" mb-0pull-left">Events</h1>
        <h1 class="pull-right mb-0">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{  asset($Prefix . '/create-event/' )  }}">Add Event</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('layouts.message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="">
                Start Date : 
                <input id="search_start_date" class="form-control" placeholder="Start Date" autocomplete="off" name="start_date_time" type="text" value="{{ isset($data->start_date_time) ? $data->start_date_time : old('start_date_time') }}">
                End date : 
                <input id="search_end_date" class="form-control" placeholder="End Date" autocomplete="off" name="start_date_time" type="text" value="{{ isset($data->start_date_time) ? $data->start_date_time : old('start_date_time') }}">
                <div class="form-group mt-3" align="center">
                    <button type="button" name="filter" id="filter" class="btn btn-info">Filter</button>
                    <button type="button" name="reset" id="reset" class="btn btn-danger">Reset</button> 
                </div>
            </div>
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
    $('#search_start_date').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: true,
        sideBySide: true,
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fa fa-angle-up',
            down: 'fa fa-angle-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'far fa-times-circle'
        }
    });

    $('#search_end_date').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: true,
        sideBySide: true,
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fa fa-angle-up',
            down: 'fa fa-angle-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'far fa-times-circle'
        }
    });
/* Start dataTable (using package) to make(true) */
$(document).ready(function(){

    fill_datatable();

    function fill_datatable(start_date = '', end_date = '') {
        var dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url: "{{ asset($Prefix . '/events/' ) }}",
                data:{start_date:start_date, end_date:end_date}
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
    }

    $('#filter').click(function(){
        var start_date = $('#search_start_date').val();
        var end_date = $('#search_end_date').val();
        if(start_date != '' ||  end_date != ''){
            $('#dataTable').DataTable().destroy();
            fill_datatable(start_date, end_date);
        } else{
            alert('Select Both filter option');
        }
    });

    $('#reset').click(function(){
        $('#search_start_date').val('');
        $('#search_end_date').val('');
        $('#dataTable').DataTable().destroy();
        fill_datatable();
    });

});























/*
    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url: "{{ asset('admin/events/') }}",
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
*/
/* End dataTable (using package) to make(true) */
</script>  
@endsection