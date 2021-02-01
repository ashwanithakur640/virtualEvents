@extends('layouts.app')

@section('content')
    <style>
 
    .mutiple-linkouter {
    background-image: url("{{ asset('images/Vendor_page/links-bg.jpg') }}"); 
    padding: 100px 0px 100px 0px;
    min-height: 100vh;
    background-size: cover;
    position: relative;
}

    </style>
<main>
<section class="mutiple-linkouter">
            <div class="container custom-container">
                <div class="row">
                  <div class="col-md-10 offset-md-1 col-sm-12">
                    <div class="group_chat_box links-chat-outer">
                      <div class="welcome_heading">
                          <h2>About </h2>
                      </div>
                      <!-- <div class="virtual_chat d-flex align-items-center justify-content-between">
                          <h4>121 Candidates</h4>
                      </div> -->
                      <div class="virtual_vendor_box">
                          <div class="vendor_box_in">

                            <?php
  if( (isset($data->vendor_details)) &&  ($data->vendor_details['description'])){
                            echo $data->vendor_details['description'];
}
                            ?>
                            </div> 
                      </div>
                  </div>
                  </div>
                </div>
          </div>
        </section>

        </main>
@endsection