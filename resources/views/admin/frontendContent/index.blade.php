@extends('admin.adminLayout')

@section('content')
    <section class="content- mb-2 d-flex align-items-center justify-content-between">
        <h1 class=" mb-0pull-left">Frontend Contents</h1>
        <h1 class="pull-right mb-0">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{{ asset('admin/frontend-contents/create-frontend-content') }}">Add Content</a>
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
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="tableHeading">Page</th>
                                <th class="tableHeading">Title</th>
                                <th class="tableHeading">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           @if($data->count() > 0)
                                @foreach($data as $key => $value)
                                <tr>
                                    <td>
                                        @if(isset($value['page']) && !empty($value['page'])) 
                                            {{ $value['page']->name }}
                                        @endif    
                                    </td>
                                    <td>{{ $value->title }}</td>
                                    <td>
                                        <div class='btn-group'>
                                            <a href="{{ asset('admin/frontend-contents/edit-frontend-content/'.Helper::encrypt($value->id)) }}" class='btn btn-primary btn-xs' data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                                        </div>
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
