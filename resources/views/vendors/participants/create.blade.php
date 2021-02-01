@extends('vendors.adminLayout')

@section('content')
    <section class="content-header">
        <h1>
            Create Participant
        </h1>
    </section>
    @include('layouts.message')
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">



                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                                <div class="card-body">
                                    <form id="createVendorForm" action="{{ asset($Prefix . '/store-participant/') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @include('vendors.participants.form')

                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
