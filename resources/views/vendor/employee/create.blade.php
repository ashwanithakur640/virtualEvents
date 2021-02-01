@extends('vendor.vendorLayout')

@section('content')

<section class="content-header">
    <h4>
        Create Employee
    </h4>
</section>
<div class="content">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="sessionForm" action="{{ asset($Prefix . '/employee/store-employee') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @include('vendor.employee.form')

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
