@extends('vendors.adminLayout')

@section('content')
    <section class="content-header">
        <h1>
            Edit Event
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <form id="eventForm" action="{{ asset($Prefix . '/update-events/'.Helper::encrypt($data->id)) }}" method="post" enctype="multipart/form-data">
                                    @csrf

                                    @method('PATCH')
                                    
                                    @include('vendors.events.form')

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
