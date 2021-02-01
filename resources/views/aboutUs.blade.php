@if(\Request::segment(1) == 'vendor')
    @php
        $layout = 'vendor.layouts.app';
    @endphp
@elseif(isset($Prefix) && \Request::segment(1) == $Prefix)
	@php
    	$layout = 'vendor.vendorLayout';
    @endphp	
@else
    @php
        $layout = 'layouts.app';
    @endphp
@endif    

@extends($layout)

@section('content')
@if(!empty($data))
	{!! $data->description !!}    
@endif

@endsection