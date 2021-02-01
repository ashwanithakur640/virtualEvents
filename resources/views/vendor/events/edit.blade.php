@extends('vendor.vendorLayout')

@section('content')

<section class="content-header">
    <h4>
        Edit Event
    </h4>
</section>
<div class="content">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="eventForm" action="{{ asset($Prefix . '/events/update-event/'.Helper::encrypt($data->id)) }}" method="post" enctype="multipart/form-data">
                                @csrf

                                @method('PATCH')
                                
                                @include('vendor.events.form')

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection