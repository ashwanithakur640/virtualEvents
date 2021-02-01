@extends('layouts.app')

@section('content')
    <style>
        section.vendor_section {
        background-image: url("{{ asset('images/Vendor_page/vendor_background.jpg') }}");
        padding: 100px 0px 150px 0px;
        min-height: 100vh;
        background-size: cover;
    }

    </style>
<main>
	<section class="vendor_section">
            <div class="container custom-container">
                <div class="row">
                  <div class="col-md-10 offset-md-1 col-sm-12">
                    <div class="vendor-upper-outer">
                      <div class="vendor-upper-outerleft">
                        <img src="{{ asset('images/Vendor_page/profile_sec.jpg') }}">
                        <div class="vendor-upper-outerleftright">
                          <div class="vendor-title">Yana Paniet</div>
                          <img src="{{ asset('images/Vendor_page/sigma-logo.jpg') }}">
                        </div>
                      </div>
                      <div class="vendor-upper-outerright">
                        <div class="support">
                            <a href="">
                                <img src="{{ asset('images/support-icon.png') }}">
                                <p class="mb-0">Support</p>
                            </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">

                    
                  <div class="col-md-10 offset-md-1 col-sm-12">
                    <div class="virtual-entertainment-title">{{ isset($data->vendor_details) ? $data->vendor_details['welcome_text'] : 'Welcome' }}</div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mx-auto">
                        <div class="vendor_box">
                            <div class="row">
                                <div class="col-md-3">
                <div class="exhibit-inner-left">
                    <ul>

                        <?php 

                            if( (isset($data->vendor_details)) &&  ($data->vendor_details['description'])){

                          
                            //give link of vendor about us section
                        ?>
                        <li class="active"><a href="{{ asset('exhibit-hall/vendor-about/'.Helper::encrypt($data->id))  }}">About Us</a></li>
                        <?php } ?>

                        <li><a href="#">Chat with Tdwi</a></li>

                        <li><a href="{{ asset('exhibit-hall/documents/'.Helper::encrypt($data->id))  }}">Documents and Links</a></li>

                        <?php 
                        
                            if( (isset($data->vendor_details)) &&  ($data->vendor_details['case_study'])){

                            
                            //give link of vendor about us section
                        ?>

                        <li><a href="/assets/images/document/{{ $data->vendor_details['case_study'] }}" target="_blank">Customer Case Studies</a></li>

                        <?php 
                        }
                            if( (isset($data->vendor_details)) &&  ($data->vendor_details['resource'])){

                          
                            //give link of vendor about us section
                        ?>

                        <li><a href="/assets/images/document/{{$data->vendor_details['resource']}}" target="_blank">Resources</a></li>
                        <?php } ?>
                        <?php 
                        
                            if( (isset($data->vendor_details)) &&  ($data->vendor_details['presentation'])){

                           
                            //give link of vendor about us section
                        ?>
                        <li><a href="/assets/images/document/{{$data->vendor_details['presentation']}}" target="_blank">View our Presentation</a></li>
                        <?php } ?>
                    </ul>
<!--                     <img src="../images/Vendor_page/stand.png" alt=""> -->
                </div>
                    </div>
                    <div class="col-md-6 pr-0 pl-0">
                    <div class="exhibit-video-outer-bottom">
<?php 
                        
                            if( (isset($data->vendor_details)) &&  ($data->vendor_details['short_video'])){

                           
                            //give link of vendor about us section
                        ?>

                <video  controls="controls"><source src="/sample_video/{{$data->vendor_details['short_video']}}"><source src="/sample_video/{{$data->vendor_details['short_video']}}"><source src="/sample_video/{{$data->vendor_details['short_video']}}">Your browser does not support the video tag.</video>
<?php } ?>

                  <!-- <div class="video-text">
                    <h1>Virtual Entertainment Aug 09-11</h1>
                    <p class="mb-0">Data Literacy, and Architecture</p>
                  </div> -->
              </div>

                    </div>
                                <div class="col-md-3 pl-0 pr-0">
                                    <div class="group_chat_box">
                                        <div class="welcome_heading">
                        <h2>Group Chat </h2>
                    </div>
                                        <div class="virtual_chat d-flex align-items-center justify-content-between">
                        <h4>121 Members</h4>
                        <div class="icon_right">
                            <img src="{{ asset('images/iocn_right.png') }} ">
                        </div>
                    </div>
                                        <div class="virtual_vendor_box">
                                            <div class="vendor_box_in">
                                                <h4>TDWI Research</h4>
                                                <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
                                            </div>
                                            <div class="vendor_box_in">
                                                <h4>Uapajur  lkjioj</h4>
                                                <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
                                            </div>
                                            <div class="vendor_box_in">
                                                <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
                                            </div>
                                            <div class="vendor_box_in">
                                                <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
                                            </div>
                                        </div>
                                        <div class="virtual_form_box">
                                            <form>
                                              <div class="form-group">
                                                <input type="email" class="form-control mb-0" aria-describedby="emailHelp" placeholder="Write here...">
                                                <button type="submit"><img src="{{ asset('images/Vendor_page/write-here.png') }} "></button>
                                              </div>
                                              
                                              
                                              
                                            </form>
                                        </div>
                                        <img class="chat-stand" src="{{ asset('images/Vendor_page/chat-stand.png') }} " alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
          </div>
          <div class="left-bottom-div">
          <img class="left-bottom" src="{{ asset('images/Vendor_page/left-bottom.png') }} " alt="">
          <div class="support chat-outer-support">
              <a href="">
                  <img src="{{ asset('images/chat_icon.png') }} ">
                  <p class="mb-0">Chat</p>
              </a>
          </div>
        </div>
        <img class="man-img1" src="{{ asset('images/Exhibit-Hall-ptp-8.png') }} " alt="">
        <img class="man-img2" src="{{ asset('images/Exhibit-Hall-ptp-4.png') }} " alt="">
        <img class="man-img3" src="{{ asset('images/Exhibit-Hall-ptp-9.png') }} " alt="">
        </section>
</main>
@endsection

