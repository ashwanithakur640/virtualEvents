@extends('layouts.app')

@section('content')
<section class="banner_landing_page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 text-center">
                <h2>VIRTUAL CONFERENCE PLATFORM</h2>
                <p>Communique Conferencing's enterprise-grade, cloud-based, virtual conference and event platform functions as a traditional off-line trade show translating exhibit halls, booths, presentations & networking into a highly customizable 3D virtual environment</p>
            </div>
            <div class="col-md-10">
                <div class="position-relative">
                    <img src="{{asset('images/landing_page/desktop.png')}}">
                    <div class="btn_desktop down-fall-2">
                        <a href="">
                            See A Demo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="Reasons_why">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-5 text-center">
                <div class="heading_reasons">
                    <h2>Reasons Why You Should Choose Our App</h2>
                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium dolo remque laudantium</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="reasons_box d-flex">
                    <img src="{{asset('images/landing_page/reason_icon_1.png')}}">
                    <div class="reasons_ctn">
                        <h2>No Download Required</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="reasons_box d-flex">
                    <img src="{{asset('images/landing_page/reason_icon_2.png')}}">
                    <div class="reasons_ctn">
                        <h2>No Download Required</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="reasons_box d-flex">
                    <img src="{{asset('images/landing_page/reason_icon_3.png')}}">
                    <div class="reasons_ctn">
                        <h2>No Download Required</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="reasons_box d-flex">
                    <img src="{{asset('images/landing_page/reason_icon_4.png')}}">
                    <div class="reasons_ctn">
                        <h2>No Download Required</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="reasons_box d-flex">
                    <img src="{{asset('images/landing_page/reason_icon_5.png')}}">
                    <div class="reasons_ctn">
                        <h2>No Download Required</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="reasons_box d-flex">
                    <img src="{{asset('images/landing_page/reason_icon_6.png')}}">
                    <div class="reasons_ctn">
                        <h2>No Download Required</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="paltform_section">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-md-7 mx-auto text-center">
                <h2>VIRTUAL CONFERENCE PLATFORM FEATURES</h2>
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem </p>
            </div>
        </div>
        <div class="row no-gutters align-items-center">
            <div class="col-md-6">
                <div class="platfrm_box">
                    <h2>Main Hall or Lobby</h2>
                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium 
                        doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inve ntore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed 
                    </p>
                    <p>The Virtual Trade Show Exhibit Hall gives attendees the ability to browse 
                        exhibitor booths. An exhibitor directory makes it easy to locate specific booths all with a click of their mouse.
                    </p>
                    <p class="mb-0">Attendees can easily find matching booths based on multiple search criteria.</p>
                </div>
            </div>
            <div class="slider_vd">
                <div id="example">
                    <carousel-3d>
                        <slide v-for="(slide, i) in slides" :index="i">

                            <img v-bind:src="slide.src" />
                        </slide>
                    </carousel-3d>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="trusted_seciotn">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex ">
                    <i class="fa fa-trophy" aria-hidden="true"></i>
                    <div class="cnt_trust">
                        <h2>Trusted by 4000+</h2>
                        <p>High performing team worldwide</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-center">
                <h2>123456</h2>
                <h4>virtual events hosted </h4>
            </div>
            <div class="col-md-2 text-center">
                <h2>123456+</h2>
                <h4>Attendees</h4>
            </div>
            <div class="col-md-2 text-center">
                <h2>123456+</h2>
                <h4>Paid Users</h4>
            </div>
        </div>
    </div>
</section>
<section class="See-a-demo_btn">
    <img src="{{asset('images/landing_page/video-banner.png')}}">
    <a id="play-video" class="video-play-button" href="#">
        <span></span>
    </a>
    <div id="video-overlay" class="video-overlay">
        <a class="video-overlay-close">&times;</a>
    </div>
    <div class="btn_desktop_demo">
        <a href="">
            See A Demo
        </a>
    </div>
</section>
<section class="client_review">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="heading_text">
                    <h2>CLIENT <span>REVIEWS</span></h2>
                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem </p>
                </div>
                <div class="client_box_review">
                    <div class="testi_slider">
                        <div class="box_slider_section">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <img src="{{asset('images/landing_page/testimonial.jpg')}}">
                                </div>
                                <div class="col-md-8 text-center">
                                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae.</p>
                                    <h5>Lorem Ipsum</h5>
                                    <p class="mb-0">Lorem Ipsum</p>
                                </div>
                            </div>
                        </div>
                        <div class="box_slider_section">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <img src="{{asset('images/landing_page/testimonial.jpg')}}">
                                </div>
                                <div class="col-md-8 text-center">
                                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae.</p>
                                    <h5>Lorem Ipsum</h5>
                                    <p class="mb-0">Lorem Ipsum</p>
                                </div>
                            </div>
                        </div>
                        <div class="box_slider_section">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <img src="{{asset('images/landing_page/testimonial.jpg')}}">
                                </div>
                                <div class="col-md-8 text-center">
                                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae.</p>
                                    <h5>Lorem Ipsum</h5>
                                    <p class="mb-0">Lorem Ipsum</p>
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

@section('script')
<script>
    jQuery(document).ready(function($) {
        $('.testi_slider').slick({
            dots: false,
            infinite: true,
            speed: 500,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            arrows: true,
            responsive: [{
                breakpoint: 991,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 400,
                settings: {
                    arrows: false,
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }]
        });
    });
</script>
<script>
    $('#play-video').on('click', function(e){
        e.preventDefault();
        $('#video-overlay').addClass('open');
        $("#video-overlay").append('<iframe width="560" height="315" src="https://www.youtube.com/embed/ngElkyQ6Rhs" frameborder="0" allowfullscreen></iframe>');
    });

    $('.video-overlay, .video-overlay-close').on('click', function(e){
        e.preventDefault();
        close_video();
    });

    $(document).keyup(function(e){
        if(e.keyCode === 27) { close_video(); }
    });

    function close_video() {
        $('.video-overlay.open').removeClass('open').find('iframe').remove();
    };
</script>

<script src="{{asset('js/vue.js')}}"></script>
<script src="{{asset('js/vue-carousel-3d.min.js')}}"></script>
<script>
    new Vue({
        el: '#example',
        data: {
            slides: [{
                title: 'Seungri\'s Alleged',
                src: "{{asset('images/landing_page/session_slider.png')}}",
                desc: '1 dummy description text here...'
            }, {
                title: 'Emma',
                src: "{{asset('images/landing_page/session_slider-2.png')}}",
                desc: '2 dummy description text here...'
            }, {
                title: 'Kim Tae Hee',
                src: "{{asset('images/landing_page/session_slider.png')}}",
                desc: '3 dummy description text here...'
            }, {
                title: 'Kate',
                src: "{{asset('images/landing_page/session_slider-2.png')}}",
                desc: '5 dummy description text here...'
            }]
        },
        components: {
            'carousel-3d': Carousel3d.Carousel3d,
            'slide': Carousel3d.Slide
        }
    })
</script>
@endsection