@extends('vendors.adminLayout')

@section('content')

<section class="content-header">
    <h4>
        Create Session
    </h4>
</section>
<div class="content">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="sessionForm" action="{{ asset($Prefix . '/store-session') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @include('vendors.session.form')

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
