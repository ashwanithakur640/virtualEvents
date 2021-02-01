@extends('vendors.adminLayout')

@section('content')
    <section class="content-header">
        <h1>View Detail</h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-body">
                              
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Session Name</b></label>
                                            <p>{{ $data->name }}</p>
                                        </div>
                                    </div>
                                
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Description</b></label>
                                            <p>{{ strip_tags($data->description)  }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                     <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Date</b></label>
                                            <p>{{ $data->date }}</p>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Start Time</b></label>
                                            <p>{{ $data->start_time }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>End Time</b></label>
                                            <p>{{ $data->end_time }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="border_text">
                                            <label><b>Sharable Url</b></label>
                                            <p id="myInput" >{{ url('/webinar/'.$data->u_id) }}</p>

                                            <span onclick="copyToClipboard('#myInput')"><i class="fa fa-clipboard" aria-hidden="true"></i>
</span>

<div id="status-area"></div>
                                        </div>
                                    </div>

                                 


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<style>

#status-area .flash_message {
    padding: 5px;
    color: green;
}
</style>

 @yield('script')


<script>

    function copyToClipboard(element) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();


      $('#status-area').flash_message({
            text: 'Text Copied to clipboard',
            how: 'append'
        });
    }
    
</script>
@endsection