<?php
  $url = url('');
  $url .= '/webinar/'.$firstData->u_id;
?>


<div class="session_hall"> <div class="row"> <div class="col-md-4"> <img src="{{ asset('assets/images/session/'.$firstData->image) }}"></div><div class="col-md-7"><div class="tt_btx-box mb-0 mt-0"><h5>   {{ $firstData->name }}</h5><p class="mb-0">{{ $firstData->date 
}}    |   {{  $firstData->start_time }} -  {{ $firstData->end_time }} </p>


<div class="btn_session">

<?php
  if( in_array($firstData->event_id,$participatedEvents) ){
                                                
      ?>

      <a class="{{ $sessionStartButton }}" href="{{$url}}" target="_blank">Click To Watch</a>

      <?php
          
          }else{

              $eventUrl = 'exhibit-hall/event-detail/'.Helper::encrypt($firstData->event_id)

      ?>

      <a class="" href="{{$eventUrl}}" target="_blank">Join Event</a>

      <?php

          }

      ?>

</div>

<div class="ratiing">
<ul class="d-flex align-items-center">
  <?php
      for ($i=0; $i <= 4 ; $i++) { 
          if($avg > $i){
              echo '<li class="mb-0 pl-2"><i class="fa fa-star" aria-hidden="true"></i></li>';
          } else{
              echo '<li class="mb-0 pl-2"><i class="fa fa-star-o" aria-hidden="true"></i></li>';
          }
      }
  ?>
  <li class="mb-0 pl-2"><p class="mb-0">{{ isset($avg) ? $avg : 0 }} ({{$count}})</p></li>
</ul>
</div></div></div></div></div>
<div class="review_btn d-flex"><p class="mb-0"><a href="{{ url('reviews',  Helper::encrypt($firstData->id) ) }}">Read Comment</a></p>  


<?php
  if( in_array($firstData->event_id,$participatedEvents) ){
                                                
      ?>

 | <button type="button" class="writeComment mb-0" data-toggle="modal" data-target="#myModal{{$firstData->id}}">Write Comment</button>

<?php } ?>

</div>
<div class="pre p-3"><p>{!! $firstData->description !!}</p></div>

<div id="myModal{{$firstData->id}}" class="modal_ctn_review modal fade sdfsdfsd" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h2>Review and Comment</h2>
        <h3 class="bg_Write_comment">Write comment</h3>
      </div>

      {!! Form::model($myRating, ['route' => ['rate'] , 'enctype'=>'multipart/form-data', 'method' => 'post' , 'class' => 'form_profile' , 'id' => 'rating_form']) !!}  
      <div class="modal-body pt-0">
        <input type="hidden" name="session_id" value='{{$firstData->id}}'>

        <div class="ctn_review">

          <div class="container">
            <div class="row align-items-center">
              <div class="col-md-12">
                <h4>Give Rating to following Title</h4>
              </div>
              <div class="col-md-6">
                <div class="star_ctn d-flex align-items-center">
                  <div class="star-rating review-star-outer">
                    <span class="fa fa-smile-o"></span>
                  </div>
                  <div class="star_rating_text">
                    <h4>Outstanding</h4>
                  </div>
                </div>
                <div class="star_ctn d-flex align-items-center">
                  <div class="star-rating review-star-outer">

                    <span class="fa fa-meh-o "></span>
                  </div>
                  <div class="star_rating_text">
                    <h4>Average</h4>
                  </div>
                </div>
                <div class="star_ctn d-flex align-items-center">
                  <div class="star-rating review-star-outer">

                    <span class="fa fa-frown-o"></span>
                  </div>
                  <div class="star_rating_text">
                    <h4>Bad</h4>
                  </div>
                </div>


              </div>
              <div class="col-md-6">
                <div class="star_ctn div_right d-flex align-items-center justify-content-end">
                  <div class="star_rating_text">
                    <h4>Overall Rating</h4>
                  </div>
                  <div class="star-rating review-star-outer">

                    <input type="radio" id="3-stars" name="rating" value="3" <?php if( (!empty($myRating)) && ( $myRating->rating == 3) ){ echo 'checked'; } ?> />
                    <label for="3-stars" class="star">&#9733;</label>
                    <input type="radio" id="2-stars" name="rating" value="2"  <?php if( (!empty($myRating)) && ($myRating->rating == 2 )){ echo 'checked'; } ?> />
                    <label for="2-stars" class="star">&#9733;</label>
                    <input type="radio" id="1-star" name="rating" value="1"  <?php if( (!empty($myRating)) && ($myRating->rating == 1) ){ echo 'checked'; } ?>/>
                    <label for="1-star" class="star">&#9733;</label>
                  </div>
                  <div class="star_rating_text">
                    <h4><?=  !empty($myRating) ? $myRating->rating : 0; ?>/5</h4>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
        <div class="review_fill_Sec">
          <div class="container">
            <div class="row">
              <div class="col-md-12 mt-4 mb-4">
                <div class="fill_form">

                  <div class="row">

                    <div class="col-md-12">
                      <label>Feedback</label>

                      {!! Form::textarea('comment', null, ['class' => 'form-control' , 'required' => '' , 'placeholder' =>"Enter your description here"   ]) !!}
                    </div>
                    <div class="col-md-12">

                      {!! Form::submit('Submit', ['class' => 'next-btn']) !!}

                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      {!! Form::close() !!}
    </div>

  </div>
</div>


