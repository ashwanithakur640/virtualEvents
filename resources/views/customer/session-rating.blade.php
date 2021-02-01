@extends('layouts.app')

@section('content')
<section class="helpful-tips-outer">
   <div class="container">
      <div class="row">
         <div class="col-md-12 col-sm-12">
            <div class="helpful-tips-right">
               <div class="row">
                  <div class="col-md-12 col-sm-12">
                     <div class="time-day-nav review-rating-mainouter">
                        <p class="list-p">Review and Rating</p>
                        <a type="button" class="btn btn-light rtngrw" href="{{ asset('session-hall') }}">Go Back</a>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12 col-sm-12">
                     <div class="computer-tips-outer list-outer">

                      <?php 

                        if($ratings->isEmpty()){

                          echo '<div class="computer-tips-inner list-inner review-inner">No rating given yet.
                        </div>';

                        }else{

                      ?>
                        



                         @foreach($ratings as $key => $rating)


                        <div class="computer-tips-inner list-inner review-inner">
                           <div class="review-upper">
                              <div class="listing-left review-img">
                                @php 

                                    $url = ( isset($rating->user) && !empty($rating->user->image) ) ? "/assets/images/profile_pic/".$rating->user->image : '/images/default-user.png';  

                                @endphp
                                 <img src="<?= $url ?>" alt="">
                              </div>
                              <div class="listing-center">
                                 <div class="review-title">{{ isset($rating->user) ? $rating->user->first_name.' '. $rating->user->middle_name.' '. $rating->user->last_name : '' }}</div>
                                 <ul class="list-ul">
                                    <li> {{date('d-m-Y', strtotime( $rating->created_at))}}</li>
                                    <li>{{date('H:i', strtotime( $rating->created_at))}}</li>
                                 </ul>
                              </div>
                              <div class="star-rating review-star-outer">
                                 <?php 

                                      $avg = round($rating->rating);
 
                                      $count = 3; 

                                      if($avg == 1){

                                        echo '<span class="fa fa-frown-o   checked"></span> Bad';

                                      }

                                      if($avg == 2){

                                        echo '<span class="fa fa-meh-o    checked"></span> Average';

                                      }

                                      if($avg == 3){

                                        echo '<span class="fa  fa-smile-o   checked"></span> Good';

                                      }

                                  ?>

                              </div>
                           </div>
                           <div class="review-bottom">
                             
                              <p>{{ $rating->comment }}</p>
                           </div>
                        </div>
                        
                        @endforeach
<?php
                       }

                      ?>
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