@extends('admin.adminLayout')

@section('content')
    <section class="content-header">
        <h1>
            Edit Vendor
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                                <div class="card-body">
                                    <form id="editVendorForm" action="{{ asset('admin/vendors/update-vendor/'.Helper::encrypt($data->id)) }}" method="post" enctype="multipart/form-data">
                                        @csrf

                                        @method('PATCH')
                                        
                                        @include('admin.vendors.form')

                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
