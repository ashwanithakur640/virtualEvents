<!DOCTYPE html>
<html lang="en">
    <head>
        <title>AmbiPlatforms</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/vendor/style.css') }}">
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
        <link rel="stylesheet" href="{{ asset('css/vendor/custom.css') }}">
        <link rel="stylesheet" href="{{ asset('css/media.css')}}">
        <link rel="stylesheet" href="{{ asset('css/vendor/page.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&family=Rubik:wght@300;400;500;600&display=swap" rel="stylesheet"> 
        <link rel="shortcut icon" href="{{asset('images/fav_icon.jpeg')}}" type="image/x-icon">
        <link rel="icon" href="{{asset('images/fav_icon.jpeg')}}" type="image/x-icon">
    </head>
    <body>
        <header class="custom-header">
            <div class="container">
                <div class="row">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="{{ asset('/') }}">AmbiPlatforms</a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            @include('vendor.layouts.header')

                        </div>
                    </nav>
                </div>
            </div>
        </header>
        <div class="content-wrapper">
            @include('layouts.message')
            
            @yield('content')

        </div> 
        <script src="{{asset('js/jquery.min.js')}}"></script>
        <!-- <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script> -->
        <script src="{{asset('js/bootstrap.min.js')}}"></script>

        <!-- Start jquery validate js -->
        <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('assets/js/additional-validate-methods.min.js') }}"></script>
        <!-- End jquery validate js -->

        <!-- Start custom js -->
        <script src="{{ asset('js/vendor/page.js') }}"></script>
        <!-- End custom js -->

        <!-- Start ckeditor Basic -->
        <script src="{{ asset('js/ckeditor_basic/ckeditor.js')}}" type="text/javascript"></script>
        <script src="{{ asset('js/ckeditor_basic/samples/js/sample.js')}}" type="text/javascript"></script>

        @if(\Request::segment(2) == 'help')
        <script>
            if ($('#editor').length > 0) {
                CKEDITOR.replace('editor', {
                    height: 400
                });
            }
        </script>
        @else
            <script>
                if ($('#editor').length > 0) {
                    initSample();
                }
            </script>
        @endif
        
        <!-- End ckeditor Basic -->
        @yield('script')

        <script>
        /* Start alert message fade out */
        $('.alert-success').delay(5000).fadeOut('slow');
        $('.alert-warning').delay(5000).fadeOut('slow');
        $('.alert-danger').delay(5000).fadeOut('slow');
        /* End alert message fade out */
        </script>
    </body>
</html>