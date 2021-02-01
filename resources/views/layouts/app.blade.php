<!DOCTYPE html>
<html lang="en">
    <head>
        <title>AmbiPlatforms</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/media.css')}}">
        <link rel="stylesheet" href="{{asset('css/slick.css')}}">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&family=Rubik:wght@300;400;500;600&display=swap" rel="stylesheet"> 
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
        <link rel="shortcut icon" href="{{asset('images/fav_icon.jpeg')}}" type="image/x-icon">
        <link rel="icon" href="{{asset('images/fav_icon.jpeg')}}" type="image/x-icon">
         
        @yield('css')

        <style>
            .payment-form-icon {
              line-height: 46px;
              margin-right: 10px;
              color: #dddddd !important;
            }
        </style>

    </head>
    <body>
        @if(\Request::segment(1) == null)
            @include('layouts.header1')
        @else
            @include('layouts.header')
        @endif

        <div class="content-wrapper">
            @yield('content')

            @if(\Request::segment(1) == null)
                @include('layouts.footer1')
            @else
                @include('layouts.footer')
            @endif

        </div>    
        <script src="{{asset('js/jquery.min.js')}}"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.4.3/jquery.payment.min.js"></script>
        <script src="{{asset('js/slick.min.js')}}"></script>

        <!-- Start jquery validate js -->
        <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('assets/js/additional-validate-methods.min.js') }}"></script>
        <!-- End jquery validate js -->

        <!-- Start custom js -->
        <script src="{{ asset('js/customer.js') }}"></script>
        <!-- End custom js -->

        <!-- Start ckeditor Basic -->
        <script src="{{ asset('js/ckeditor_basic/ckeditor.js')}}" type="text/javascript"></script>
        <script src="{{ asset('js/ckeditor_basic/samples/js/sample.js')}}" type="text/javascript"></script>
        
        @if(\Request::segment(1) == 'help')
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

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        @yield('script')

        <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch(type){
            case 'info':

            toastr.options.timeOut = 5000;
            toastr.info("{{Session::get('message')}}");
            break;

            case 'success':

            toastr.options.timeOut = 5000;
            toastr.success("{{Session::get('message')}}");
            break;

            case 'warning':

            toastr.options.timeOut = 5000;
            toastr.warning("{{Session::get('message')}}");
            break;

            case 'error':

            toastr.options.timeOut = 5000;
            toastr.error("{{Session::get('message')}}");
            break;
            }
        @endif
        </script>

        <script>
        /* Start alert message fade out */
        $('.alert-success').delay(5000).fadeOut('slow');
        $('.alert-warning').delay(5000).fadeOut('slow');
        $('.alert-danger').delay(5000).fadeOut('slow');
        /* End alert message fade out */
        </script>
    </body>
</html>