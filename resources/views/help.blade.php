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

@include('layouts.message')

<main class="help_section">
	<section class="help_section pt-3">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="left-side-textarea">
						<div class="ask_question">
							<h2>Ask a Question?</h2>
						</div>
						<div class="welcome-faq">
							<h2>AmbiPlatforms Online FAQ </h2>
						</div>
						@if(\Request::segment(1) == 'help')
							<form id="faqForm" action="{{ asset('faq') }}" method="POST">
						@endif  

	                    @if(\Request::segment(2) == 'help')
	                        @if(isset($Prefix))
	                            @php
	                                $vendor = $Prefix;
	                            @endphp
	                        @else
	                            @php
	                                $vendor = 'vendor';
	                            @endphp
	                        @endif
	                
	                        <form id="faqForm" action="{{ asset($vendor . '/faq') }}" method="POST">
	                    @endif

						@csrf
							
							<div class="text_area {{ $errors->has('question') ? 'error' : ''}}">
								<textarea id="editor" class="form-control text-area" name="question" placeholder="Type in Question here..."></textarea>
								@if ($errors->has('question'))
                                    <label class="help-block">{{ $errors->first('question') }}</label>
                                @endif

							</div>
							<div class="btn_enter">
								<button type="submit" class="btn_sec">Send</button>
							</div>
						</form>
					</div>

				</div>
				<div class="col-md-8">
					<div class="help_faq">
						@if(!empty($data))
							{!! $data->description !!}    
						@endif

					</div>
				</div>
			</div>
		</div>
	</section>
</main>
@endsection