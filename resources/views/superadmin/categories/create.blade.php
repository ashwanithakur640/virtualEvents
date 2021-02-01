@extends('superadmin.superadminLayout')

@section('content')
    <section class="content-header">




        
        <h1>
            Create Category
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                                <div class="card-body">
                                    <form id="categoryForm" action="{{ asset('superadmin/categories/store-category') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @include('superadmin.categories.form')

                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
