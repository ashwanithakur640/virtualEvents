@extends('admin.adminLayout')

@section('content')
    <section class="content-header">
        <h1>View Contact Us Report</h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h2 class="d-flex"><b>?.</b> <small> {!! $data->description !!}</small></h2>
                                <span>{!! $data->answer !!}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection