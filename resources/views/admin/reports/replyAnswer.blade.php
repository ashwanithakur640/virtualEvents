@extends('admin.adminLayout')

@section('content')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">FAQ Report Answer</h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="{{ asset('admin/faq-report/update-answer/'.Helper::encrypt($data->id)) }}" method="post">
                                    @csrf

                                    <h2 class="d-flex"><b>?.</b> <small>{!! $data->question !!}</small></h2>
                                    <div class="form-group {{ $errors->has('answer') ? 'error' : ''}}">
                                        <label>Answer<span class="asterisk">*</span></label>
                                        <textarea id="editor" class="form-control" name="answer">{{ old('answer') }}</textarea>
                                        @if ($errors->has('answer'))
                                            <label class="help-block">{{ $errors->first('answer') }}</label>
                                        @endif

                                    </div>
                                    <div class="form-actions border-none">
                                        <button type="submit" id="submit" class="btn btn-primary">Save</button>
                                        <a class="btn btn-warning mr-1" href="{{ asset('admin/faq-report/') }}">Cancel</a>
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