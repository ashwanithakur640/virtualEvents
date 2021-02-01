@extends('superadmin.superadminLayout')

@section('content')

<section class="content- mb-2 d-flex align-items-center justify-content-between">
    <h4 class=" mb-0pull-left">Reports</h4>
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
                            <th class="tableHeading">Rating</th>
                            <th class="tableHeading">Comment</th>
                            <th class="tableHeading">Created On</th>
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
            sheetName: 'Exported data'
        } ,  'csv' ],
        processing: true,
        serverSide: true,
        ajax:{
            url: "{{ asset('superadmin/feedback-report/'.$encryptId) }}",
        },
        columns:[
            {
                data: 'id',
                id: 'id',
                visible: false,
            },
            {
                data: 'user_name',
                name: 'user_name',
            },
            {
                data: 'user_email',
                name: 'user_email',
            },
            {
                data: 'rating',
                name: 'rating',
            },
            {
                data: 'comment',
                name: 'comment',
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