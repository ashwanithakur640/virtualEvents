@extends('layouts.app')

@section('content')
<main class="help_section-s">
      <section class="help_section pt-3">
        <div class="container">
            <div class="row">
            <div class="col-md-4">
            <div class="left-side-textarea">
                <div class="ask_question">
                    <h2>Question and Answers?</h2>
                </div>
                <div class="welcome-faq">
                    <h2 class="mb-0"> </h2>
                </div>
                <div class="text_question">
                    <div class="box_text_color color1">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-0">Nihal Joseph</h4>
                        <h4 class="mb-0">Today | 10:30PM</h4>
                    </div>
                    <p class="mb-0">At vero eos et accusamus et iusto odio dignissim osduci m us qui bland itiis praesentium voluptatum deleniti atque.</p>
                    </div>
                    <div class="box_text_color color2">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-0">Nihal Joseph</h4>
                        <h4 class="mb-0">Today | 10:30PM</h4>
                    </div>
                    <p class="mb-0">At vero eos et accusamus et iusto odio dignissim osduci m us qui bland itiis praesentium voluptatum deleniti atque.</p>
                    </div>
                    <div class="box_text_color color3">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-0">Nihal Joseph</h4>
                        <h4 class="mb-0">Today | 10:30PM</h4>
                    </div>
                    <p class="mb-0">At vero eos et accusamus et iusto odio dignissim osduci m us qui bland itiis praesentium voluptatum deleniti atque.</p>
                    </div>
                    <div class="box_text_color color4">
                     <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-0">Nihal Joseph</h4>
                        <h4 class="mb-0">Today | 10:30PM</h4>
                    </div>
                    <p class="mb-0">At vero eos et accusamus et iusto odio dignissim osduci m us qui bland itiis praesentium voluptatum deleniti atque.</p>
                    </div>
                </div>
                <div class="btn_enter">
                    <button type="submit" class="btn_sec">Enter</button>
                </div>
                </div>
            
            </div>
            <div class="col-md-8">
                <div class="help_faq">
                    <div class="help_heading">
                        <h2>SLIDES</h2>
                    </div>
                    <div class="watct_on">
                        <h4>My Feed :</h4>
<div id="me"></div>

<h4>Remote Feeds :</h4>
<div id="remote-container">

</div>
                        <img src="{{ asset('images/watch-on.jpg') }}">
                        <div class="watct_img">
                            <img src="{{ asset('images/watch-on_other.jpg') }}">
                        </div>
                    </div>
                </div>
            </div>
                </div>
          </div>
        </section>
    </main>
@endsection

@section('script')
<script src="https://cdn.agora.io/sdk/release/AgoraRTCSDK-3.1.1.js"></script>
<script src="{{ asset('agora/js/app-custom.js') }}"></script>
@endsection