@extends('superadmin.superadminLayout')

@section('content')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Customers</h1>
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
                                <th class="tableHeading">Email</th>
                                <th class="tableHeading">Mobile</th>
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
            url: "{{ asset('superadmin/customers/') }}",
        },
        columns:[
            {
                data: 'id',
                id: 'id',
                visible: false,
            },
            {
                data: 'full_name',
                name: 'full_name',
            },
            {
                data: 'email',
                name: 'email',
            },
            {
                data: 'full_phone_number',
                name: 'full_phone_number',
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