@extends('vendors.adminLayout')

@section('content')
    
   <div class="content">
       @include('layouts.message')
      
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">

                <div class="col-xl-12">
                                <!-- Account details card-->
                                <div class="card mb-4">
                                    <div class="card-header">Details</div>
                                    <div class="card-body">
                   {!! Form::model($getDetail, ['enctype'=>'multipart/form-data', 'method' => 'post']) !!}
                  <div class="row">
                  <!-- Short Video Field -->
                  <div class="form-group col-sm-6">
				  <h4>Short Video </h4>
				 @if(!empty($getDetail->short_video))


            <div class="thumb_div"> 
               <video width="320" height="240" controls>
                  <source src="/sample_video/{{$getDetail->short_video}}" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
             </div>


        @endif

		
                      {!! Form::file('short_video', null, ['class' => 'form-control']) !!}
                      @if($getDetail->short_video)
                       
                      @endif
                  </div>

                  <!-- Welcome ext Field -->
                  <div class="form-group col-sm-12">
                      {!! Form::label('welcome_text', 'Welcome Text:') !!}
                      {!! Form::text('welcome_text', null, ['class' => 'form-control']) !!}
                  </div>

                  <div class="form-group col-sm-12">
                    <hr>
                  </div>
				   <div class="form-group col-sm-6">
<div class="row">
                  <!-- Case Studies Field -->
				      <div class="form-group col-sm-12">
					       <h4>Case Study</h4>
								 @if(!empty($getDetail->case_study))

       
             <div class="thumb_div"> 
                <a href="/assets/images/document/{{$getDetail->case_study}}" target="_blank"  class="btn btn-success view_btn">View Document</a>
             </div>
        @endif
									</div>
									<!-- Case Studies Field -->
                  <div class="form-group col-sm-12">
                      {!! Form::label('case_study', 'Case Study:') !!}
                      {!! Form::file('case_study', null, ['class' => 'form-control']) !!}
                      
                  </div>
            
                  
				                
</div>
</div>
 <div class="form-group col-sm-6">
<div class="row">
                  <!-- Resources Field -->
				  <div class="form-group col-sm-12">
					   <h4>Resource Link</h4>
             @if($getDetail->resource)
									<div class="thumb_div"> 
				<a href="/assets/images/document/{{$getDetail->resource}}" target="_blank"  class="btn btn-success view_btn">View content</a>
				 </div>
          @endif
									</div>
									 <!-- Resources Field -->
                  <div class="form-group col-sm-6">
                      
                      {!! Form::file('resource', null, ['class' => 'form-control']) !!}
                     
                  </div>
               

                 
</div>
                  
				  </div>
				   <div class="form-group col-sm-6">
<div class="row">
 <div class="form-group col-sm-12">
					   <h4>Presentation Link</h4>
              @if($getDetail->presentation)
									<div class="thumb_div"> 
				
                       
                          <a href="/assets/images/document/{{$getDetail->presentation}}" target="_blank"  class="btn btn-success view_btn">View content</a>
                     
                    
				 </div>
           @endif
									</div>
									 <!-- Presentation Lisitng Field -->
                  <div class="form-group col-sm-6">
                      {!! Form::label('presentation', 'Presentation:') !!}
                      {!! Form::file('presentation', null, ['class' => 'form-control']) !!}
                      
                  </div>

                 

                 
</div>
</div>
 <!-- Description Field -->
                  <div class="form-group col-sm-12">
                      {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control tinymce-editor', 'rows' => '10','cols'=>'40']) !!}
                  </div>


                  @push('scripts')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>  
    <script type="text/javascript">
        tinymce.init({
            selector: 'textarea.tinymce-editor',
            height: 100,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_css: '//www.tiny.cloud/css/codepen.min.css'
        });
    </script>
@endpush


                  <!-- Submit Field -->
                  <div class="form-group col-sm-12">
                      {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                      <a href="{{ asset($Prefix .'/video-content/') }}" class="btn btn-danger">Cancel</a>
                  </div>
                  

                   {!! Form::close() !!}
               </div>
                            </div>     </div>
                            </div>
               </div>
           </div>
       </div>
   </div>
@endsection