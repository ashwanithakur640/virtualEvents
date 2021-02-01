@extends('vendor.vendorLayout')

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
       
        <th>Name</th>
       
        <th>Email Address</th>
        <th>Phone Number</th>
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
            url: "{{ asset($Prefix . '/employee/') }}",
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
                data: 'email',
                name: 'email',
            },
            {
                data: 'phone',
                name: 'phone',
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