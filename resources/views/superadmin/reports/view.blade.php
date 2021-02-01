@extends('superadmin.superadminLayout')
@section('content')
    <section class="content-header">
        <h1>View FAQ Report</h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h2 class="d-flex"><b>?.</b> <small>{!! $data->question !!}</small></h2>
                                <span>{!! $data->answer !!}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection