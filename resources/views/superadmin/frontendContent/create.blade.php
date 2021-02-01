@extends('superadmin.superadminLayout')

@section('content')
    <section class="content-header">
        <h1>
            Create Frontend Content
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <form id="frontendContent" action="{{ asset('superadmin/frontend-contents/store-frontend-content') }}" method="post" enctype="multipart/form-data">
                                    @csrf

                                    @include('superadmin.frontendContent.form')

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
