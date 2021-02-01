@extends('superadmin.superadminLayout')

@section('content')
    <section class="content-header">
        <h1>
            Edit Frontend Content
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <form id="frontendContent" action="{{ asset('superadmin/frontend-contents/update-frontend-content/'.Helper::encrypt($page->id)) }}" method="post" enctype="multipart/form-data">
                                    @csrf

                                    @method('PATCH')

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
