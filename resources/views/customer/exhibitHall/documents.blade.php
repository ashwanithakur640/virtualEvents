@extends('layouts.app')

@section('content')
    <style>
        section.vendor_section {
        background-image: url("{{ asset('images/Vendor_page/vendor_background.jpg') }}");
        padding: 100px 0px 150px 0px;
        min-height: 100vh;
        background-size: cover;
    }

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
                          <h2>ALL PARTICIPATIONS</h2>
                      </div>
                      <!-- <div class="virtual_chat d-flex align-items-center justify-content-between">
                          <h4></h4>
                      </div> -->
                      <div class="virtual_vendor_box">

<?php 

    if( $count > 0  ){

      foreach($arr as $req => $val){

        $docsArray = array();
        foreach($required as $docs){

          if($req == $docs->user_id){

            $docsArray[] = $docs->document;
          }

        }

?>
                          <div class="vendor_box_in">
                              <h4 class="candidate-title"><?= $val ?></h4>

                              <?php
                              foreach($docsArray as $docum){
                               ?>
                              
                              <div class="attachment-div">
                                <i class="fa fa-paperclip" aria-hidden="true"></i>
                                <p class="attached-text"><a href="{{ asset('assets/images/documents/'.$docum) }}"><?= $docum ?></a></p>
                              </div>

                            <?php } ?>
                              
                          </div>

<?php 
 }
  } 

?>
                         
                      </div>
                  </div>
                  </div>
                </div>
          </div>
        </section>

        </main>
@endsection