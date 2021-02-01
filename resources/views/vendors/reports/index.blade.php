@extends('vendors.adminLayout')

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
                                <th class="tableHeading">Customer Ip</th>
                                <th class="tableHeading">Transaction Id</th>
                                <th class="tableHeading">Vendor</th>
                                <th class="tableHeading">Event Name</th>
                                <th class="tableHeading">Event Cost</th>
                                <th class="tableHeading">Enrolled On</th>
                                
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
        buttons: [{
            extend: 'pdf',
            autoFilter: true,
            sheetName: 'Exported data',
            orientation : 'landscape',
            pageSize : 'LEGAL',
            exportOptions: {
              columns: [ 1,2,3,4,5,6,7,8 ]
         }
        } ,  'csv' ],
        processing: true,
        serverSide: true,
        ajax:{
            url: "{{ asset($Prefix . '/customer-enrolled/') }}",
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
                data: 'ip',
                name: 'ip',
            },
            {
                data : 'transaction_id',
                name : 'transaction_id',
            },
            {
                data: 'event_vendor',
                name: 'event_vendor',
            },
            {
                data: 'event_name',
                name: 'event_name',
            },
             {
                data: 'event_cost',
                name: 'event_cost'
            },
            {
                data: 'created_at',
                name: 'created_at',
            }
        ],
        order: [[0, 'desc']],
    });
});
/* End dataTable (using package) to make(true) */    
</script>  
@endsection



