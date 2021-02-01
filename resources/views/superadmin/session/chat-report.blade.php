@extends('superadmin.superadminLayout')

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
                                <h3>Session Conversation </h3>

                                <div class="btn"> <a href="{{ asset('superadmin/export-chat/'.Helper::encrypt($data->id)) }}">Export</a> </div>
                                <?= $chat ?>
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

    
    
</script>

@endsection