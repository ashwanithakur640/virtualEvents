@extends('superadmin.superadminLayout')

@section('content')
    <section class="content-header">
        <h1>
            Set Payment Amount
        </h1>
    </section>
    <div class="content">
        @include('layouts.message')

        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <form id="paymentAmount" action="{{ asset('superadmin/update-payment-amount') }}" method="post">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group {{ $errors->has('amount') ? 'error' : ''}}">
                                                <label>Amount<span class="asterisk">*</span> </label>
                                                <input class="form-control" placeholder="Amount" name="amount" type="text" value="{{ isset($data->amount) ? $data->amount : old('amount') }}">
                                                @if ($errors->has('amount'))
                                                    <span class="help-block">{{ $errors->first('amount') }}</span>
                                                @endif
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions border-none">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
