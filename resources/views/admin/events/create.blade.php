@extends('admin.adminLayout')

@section('content')
    <section class="content-header">
        <h1>
            Create Event
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                                <div class="card-body">
                                    <form id="eventForm" action="{{ asset('admin/events/store-event') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @include('admin.events.form')

                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
