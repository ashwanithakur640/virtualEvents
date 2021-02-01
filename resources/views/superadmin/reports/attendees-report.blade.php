@extends('superadmin.superadminLayout')

@section('content')
    <section class="content- mb-2 d-flex align-items-center justify-content-between">
        <h1 class=" mb-0pull-left">Customer Participated in events</h1>
        
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
                                <th class="tableHeading">Customer Name</th>
                                <th class="tableHeading">Customer Email</th>
                                <th class="tableHeading">Session Name</th>
                                <th class="tableHeading">Event Name</th>
                                <th class="tableHeading">Joining Time</th>
                                <th class="tableHeading">Leaving Time</th>
                                
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

<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script>





/* Start dataTable (using package) to make(true) */
$(document).ready(function(){
    var table = $('#dataTable').DataTable({
         dom: 'Bfrtip',
        buttons: [ {
            extend: 'pdf',
            autoFilter: true,
            sheetName: 'Exported data'
        } ,  'csv' ],
        processing: true,
        serverSide: true,
        ajax:{
            url: "{{ asset('superadmin/attendees-report/') }}",
        },
        columns:[
            {
                data: 'id',
                id: 'id',
                visible: false,
            },
            {
                data: 'customer_name',
                name: 'customer_name',
            },
            {
                data: 'customer_email',
                name: 'customer_email',
            },
            {
                data: 'session_name',
                name: 'session_name',
            },
            {
                data: 'event_name',
                name: 'event_name',
            },
            {
                data: 'created_at',
                name: 'created_at',
            },
            {
                data: 'left_at',
                name: 'left_at',
            }
        ],
        order: [[0, 'desc']],
    });
});
/* End dataTable (using package) to make(true) */    
</script>  
@endsection