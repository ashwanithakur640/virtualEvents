@extends('vendors.adminLayout')
@section('content')
    <section class="content-header">
        <h1>View Session</h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-xl-12">
                    
                        <div class="card mb-4 ">
                            
                            <div class="ml-3 mr-3"> 
                                

                                <div class="btn"> <a href="{{ asset($Prefix.'/export-chats/'.Helper::encrypt($data->id)) }}" class="btn btn-primary">Export</a> </div>

                                <h5>Session Conversation </h5>
                                <div class="mb-3 mt-3" >
                                <?= $chat ?>
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
    h4.mb-0 {
        font-size: 16px;
    }
</style>

 @yield('script')


<script>

    
    
</script>

@endsection