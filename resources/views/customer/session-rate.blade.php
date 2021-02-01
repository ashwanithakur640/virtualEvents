@extends('layouts.app')

@section('content')
<section class="helpful-tips-outer">
   <div class="container">
      <div class="row">
         <div class="col-md-12 col-sm-12">
            <div class="helpful-tips-right">
               <div class="row">
                  <div class="col-md-12 col-sm-12">
                     <div class="time-day-nav">
                        <p class="list-p">Rate The session</p>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 col-sm-12">
                     <div class="computer-tips-outer list-outer">

                       <div class="modal-content">
      <div class="modal-header">
        <h2>Review the session : {{ $firstData->name }}</h2>
        <h3 class="bg_Write_comment">Write comment</h3>
      </div>

      {!! Form::model($myRating, ['route' => ['rate'] , 'enctype'=>'multipart/form-data', 'method' => 'post' , 'class' => 'form_profile' , 'id' => 'rating_form']) !!}  
      <div class="modal-body pt-0">
        <input type="hidden" name="session_id" value='{{$confid}}'>

        <div class="ctn_review">

          <div class="container">
            <div class="row align-items-center">
             <!--  <div class="col-md-12">
                <h4>Give Rating to following </h4>
              </div> -->
              <div class="col-md-6">
                <div class="star_ctn ">
                  
                  <div class="star_rating_text">
                    <h4>Outstanding</h4>
                  </div>
                  <div class="star-rating review-star-outer">
                    <span class="fa fa-smile-o"></span>
                  </div>
                </div>
                <div class="star_ctn align-items-center">
                  
                  <div class="star_rating_text">
                    <h4>Average</h4>
                  </div>
                  <div class="star-rating review-star-outer">

                    <span class="fa fa-meh-o "></span>
                  </div>
                </div>
                <div class="star_ctn align-items-center">
                  
                  <div class="star_rating_text">
                    <h4>Bad</h4>
                  </div>
                  <div class="star-rating review-star-outer">

                    <span class="fa fa-frown-o"></span>
                  </div>
                </div>


              </div>
              <div class="col-md-6">
                <div class="star_ctn div_right d-flex align-items-center justify-content-end">
                  <div class="star_rating_text">
                    <h4>Overall Rating</h4>
                  </div>
                  <div class="star-rating review-star-outer">
<input type="radio" id="1-star" name="rating" value="1"  <?php if( (!empty($myRating)) && ($myRating->rating == 1) ){ echo 'checked'; } ?>/>
                    <label for="1-star" class="star"><span class="fa fa-frown-o"></span></label>
                   
                    <input type="radio" id="2-stars" name="rating" value="2"  <?php if( (!empty($myRating)) && ($myRating->rating == 2 )){ echo 'checked'; } ?> />
                    <label for="2-stars" class="star"><span class="fa fa-meh-o "></span></label>
                    

                     <input type="radio" id="3-stars" name="rating" value="3" <?php if( (!empty($myRating)) && ( $myRating->rating == 3) ){ echo 'checked'; } ?> />
                    <label for="3-stars" class="star"><span class="fa fa-smile-o"></span></label>
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
                    <div class="col-md-12 ">
                    <div class="submit-skip-outer"> 
                      {!! Form::submit('Submit', ['class' => 'next-btn']) !!}

                      <a href="{{ asset('session-hall') }}" class="btn next-btn btnskip" >Skip </a>
                    </div>
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
               </div>
            </div>
           
         </div>
      </div>
   </div>
   </div>
</section>
@endsection