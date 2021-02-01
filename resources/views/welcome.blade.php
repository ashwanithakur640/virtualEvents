@extends('layouts.app')

@section('content')
<main class="welcome_page">
    <section class="Welcome_section">
        <img src="{{asset('images/welcome_screen.png')}}">
        <div class="video_play">
            <img src="{{asset('images/video_play_background.png')}}">
            <div class="welcome_ctn">
                <h2>AmbiPlatforms for <br>IT Companies <br>Aug 09-11</h2>
                <p>Data Literacy, and Architecture</p>
                <img src="{{asset('images/play_iocon.png')}}">
            </div>
        </div>
        <div class="our_partners" style="top: 125px;">
            <h2>Our Partners</h2>
            <img src="{{asset('images/info_logo.png')}}">
            <img src="{{asset('images/del_logo.png')}}">
            <img src="{{asset('images/acc_logo.png')}}">
            <img src="{{asset('images/ora_logo.png')}}">
            <img src="{{asset('images/sap_logo.png')}}">
        </div>
        <!-- <div class="log_section">
            <div class="d-flex">
                <img src="{{asset('images/log_icon.png')}}">
                <h2>Log in to enter<br>the Enviroment</h2>
            </div>
        </div> -->
        <div class="three_box">
            <div class="d-flex align-items-center justify-content-between">
                <div class="chat_suppot">
                    <img src="{{asset('images/chat_icon.png')}}">
                    <h2 class="mb-0">Chats</h2>
                </div>
                <div class="chat_center">
                    <img src="{{asset('images/img-center-suport.png')}}">
                    <div class="d-flex">
                        <h2 class="mb-0">Resource <br>Center</h2>
                        <img src="{{asset('images/right_arroW_grd.png')}}">
                    </div>
                </div>
                <div class="chat_suppot">
                    <img src="{{asset('images/support-icon.png')}}">
                    <h2 class="mb-0">Support</h2>
                </div>
            </div>
        </div>
        <div class="left_banner_heading_sec">
            <div class="banner_bt">
                 <a href="{{ url('session-hall') }}">
                <img src="{{asset('images/left_book_banner.png')}}">
                <div class="tt_bg">
                    <img src="{{asset('images/white-arrow.png')}}">
                    <h2 class="mb-0">Session Hall</h2>
                </div>
            </a>
            </div>
        </div>
        <div class="right_banner_heading_sec ">
            <div class="banner_bt">
                <a href="{{ url('exhibit-hall') }}">
                <img src="{{asset('images/right_book_banner.png')}}">
                <div class="tt_bg">
                    <h2 class="mb-0">Exhibit Hall</h2>
                    <img src="{{asset('images/white-arrow_right.png')}}">
                </div>
            </a>
            </div>
        </div>
        <div class="ptn_pos postion_1">
            <h2>AmbiPlatforms</h2>
        </div>
        <div class="ptn_pos postion_2">
            <h2>AmbiPlatforms</h2>
        </div>
        <div class="ptn_pos postion_3">
            <img src="{{asset('images/info-banner.png')}}">
        </div>
        <div class="ptn_pos postion_4">
            <img src="{{asset('images/sap_banner.png')}}">
        </div>
    </section>
</main>
@endsection
