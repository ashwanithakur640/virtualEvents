@extends('participant.adminLayout')

@section('content')
    
   <div class="content">
       @include('layouts.message')
      
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">

                  <div class="col-xl-12">
                      <div class="card-body">            

                      @foreach($data as $parti)

   
        <b>Event : {{ $parti->event->name }}</b>
        <input type="hidden" name="data_id" value="{{ $parti->id  }}">

<?php 
    
    if(!(is_null($parti->document))){
?>
            <div>
                Previous Doc : <a href="{{ asset('assets/images/documents/'.$parti->document) }}" target="_blank">{{ $parti->document  }}</a>
            </div>
<?php } ?>
          


       


    

    <hr class="mt-2">
@endforeach
</div>
                  </div>
               </div>
           </div>
       </div>
   </div>
@endsection