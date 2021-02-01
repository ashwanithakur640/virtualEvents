@extends('vendors.adminLayout')

@section('content')

<section class="content- mb-2 d-flex align-items-center justify-content-between">
    <h4 class=" mb-0pull-left">Employee</h4>

    <h1 class="pull-right mb-0">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ asset($Prefix . '/create-employee/') }}">Create Employee</a>
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
                data: 'first_name',
                name: 'first_name',
            },
            {
                data: 'email',
                name: 'email',
            },
            {
                data: 'mobile',
                name: 'mobile',
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