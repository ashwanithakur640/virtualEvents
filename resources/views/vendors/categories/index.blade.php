@extends('vendors.adminLayout')

@section('content')
    <section class="content- mb-2 d-flex align-items-center justify-content-between">
        <h1 class=" mb-0pull-left">Categories</h1>
        <h1 class="pull-right mb-0">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ asset($Prefix . '/create-category/' )}}">Add Category</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        @include('layouts.message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="card">
                <div class="table-responsive data_class">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th class="tableHeading">Name</th>
                                <th class="tableHeading">Status</th>
                                <th class="tableHeading">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($data->count() > 0)
                            @foreach($data as $key => $value)
                            <tr>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->status }}</td>
                                <td>
                                    {!! Form::open(['url'=>url($Prefix. '/categories/delete-category', Helper::encrypt($value->id)), 'method' => 'delete']) !!}
                                    <div class='btn-group'>
                                        <a href="{{ asset($Prefix.'/edit-category/'.Helper::encrypt($value->id)) }}" class='btn btn-primary btn-xs' data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                        {!! Form::button('<i class="fa fa-trash" data-toggle="tooltip" title="Delete"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                    </div>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

