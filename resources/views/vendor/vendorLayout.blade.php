<!DOCTYPE html>
<html>
<head>
    <title>AmbiPlatforms</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/ven/assets/vendors/core/core.css') }}">
    <link rel="stylesheet"  href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/ven/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ven/assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ven/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ven/assets/css/demo_5/style.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Start dataTable -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTable/dataTables.bootstrap4.min.css') }}">
    <!-- End dataTable -->

    <link rel="stylesheet" href="{{ asset('css/vendor/custom.css') }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="shortcut icon" href="{{asset('images/fav_icon.jpeg')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('images/fav_icon.jpeg')}}" type="image/x-icon">
</head>
<body data-base-url="{{url('/')}}">
    <div class="main-wrapper" id="app">
        @include('vendor.header')

        <div class="page-wrapper">
            <div class="page-content">
                @yield('content')

            </div>
            <footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between">
                <a href="{{ asset($Prefix . '/about-us') }}">About Us</a>
                <a href="{{ asset($Prefix . '/privacy-policy') }}">Privacy & Policy</a>
                <a href="{{ asset($Prefix . '/contact-us') }}">Contact Us</a>
                <a href="{{ asset($Prefix . '/help') }}">Help</a>
                <p class="text-muted text-center text-md-left">Copyright © {{ date("Y") }} 
                    <a href="#" target="_blank">AmbiPlatforms</a>. All rights reserved
                </p>
            </footer>
        </div>
    </div>
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/ven/assets/vendors/core/core.js') }}"></script>
    <script src="{{ asset('assets/ven/assets/vendors/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/ven/assets/vendors/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('assets/ven/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/ven/assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <script src="{{ asset('assets/ven/assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/ven/assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/ven/assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/ven/assets/js/datepicker.js') }}"></script>

    <script src="{{asset('assets/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('assets/js/parsley.js')}}"></script>

    <!-- Start jquery validate js -->
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/additional-validate-methods.min.js') }}"></script>
    <!-- End jquery validate js -->

    <!-- Start custom js -->
    <script src="{{ asset('js/vendor/vendor.js') }}"></script>
    <!-- End custom js -->

    <!-- Start dataTables -->
    <script src="{{ asset('assets/js/dataTable/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTable/dataTables.bootstrap.min.js') }}"></script>
    <!-- End dataTables -->

    @if(\Request::segment(2) == 'contact-us' || \Request::segment(2) == 'help')
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
    @endif

    <!-- Start ckeditor -->
    <script src="{{ asset('js/ckeditor/ckeditor.js')}}" type="text/javascript"></script>
    <script>
        if ($('#summary-ckeditor').length > 0) {
            CKEDITOR.replace( 'summary-ckeditor' );
        }
    </script>
    <!-- End ckeditor -->
    @yield('script')

    @if(\Request::segment(2) == 'session')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
    $(document).ready(function() {
        $( "#datepicker" ).datepicker({ 
            dateFormat: 'yy-mm-dd',
            minDate: new Date(),
        });

        $('#timepicker3').datetimepicker({
            format: 'HH:mm:ss',
            icons: {
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            }
        });

        $('#timepicker4').datetimepicker({
            format: 'HH:mm:ss',
            icons: {
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            }
        }); 

        /* Start event on change */
        $(document).on("change","#eventSelect", function(){
            var eventStartDate = $('option:selected', this).attr('data-attr-startdate');
            var eventEndDate = $('option:selected', this).attr('data-attr-enddate');
            var eventStartTime = $('option:selected', this).attr('data-attr-startTime');
            var eventStartDateTime = $('option:selected', this).attr('data-attr-startDateTime');
            var eventEndDateTime = $('option:selected', this).attr('data-attr-EndDateTime');
            var d = new Date();
            var currentDateTime = d.getFullYear() + "-" + (String(d. getMonth() + 1). padStart(2, '0')) + "-" + String(d. getDate()). padStart(2, '0')+ ' ' + d.getHours() + ":" + (String(d. getMinutes()). padStart(2, '0')) + ":" + String(d. getSeconds()). padStart(2, '0');
            var currentDate = d.getFullYear() + "-" + (String(d. getMonth() + 1). padStart(2, '0')) + "-" + String(d. getDate()). padStart(2, '0');
            var currentTime = d.getHours() + ":" + (String(d. getMinutes()). padStart(2, '0')) + ":" + String(d. getSeconds()). padStart(2, '0');
            
            if(eventStartDate >= currentDate){
                minDate = eventStartDate;
            } else{
                minDate = new Date();
            }
            $('#datepicker').datepicker('destroy');
            $('#datepicker').val('');

            $('#timepicker3').val('');
            $('#timepicker4').val('');

            $( "#datepicker" ).datepicker({ 
                dateFormat: 'yy-mm-dd',
                maxDate: eventEndDate,
                minDate: minDate,
            });

            $("#datepicker").on("change",function(){ 

                var sessionDate = $(this).val(); 
                var startDayDate = sessionDate + ' ' +"00:00:00";
                var endDayDate = sessionDate + ' ' + "23:59:59";
                if(sessionDate == currentDate && sessionDate != eventEndDate && eventStartTime < currentTime){
                    startMaxTime = endDayDate;
                    endMaxTime = endDayDate;
                    startMinTime = currentDateTime;
                }
                else if(sessionDate == currentDate && sessionDate != eventEndDate  && eventStartTime > currentTime){
                    startMaxTime = endDayDate;
                    endMaxTime = endDayDate;
                    startMinTime = eventStartDateTime;
                }
                else if(sessionDate == currentDate && sessionDate == eventEndDate && eventStartTime < currentTime){
                    startMaxTime = eventEndDateTime;
                    endMaxTime = eventEndDateTime;
                    startMinTime = currentDateTime;
                } 
                else if(sessionDate == currentDate && sessionDate == eventEndDate && eventStartTime > currentTime){
                    startMaxTime = eventEndDateTime;
                    endMaxTime = eventEndDateTime;
                    startMinTime = eventStartDateTime;
                } 
                else if(sessionDate > currentDate && sessionDate == eventStartDate && sessionDate != eventEndDate){
                    startMaxTime = endDayDate; 
                    endMaxTime = endDayDate; 
                    startMinTime = eventStartDateTime;
                }
                else if(sessionDate > currentDate && sessionDate == eventStartDate && sessionDate == eventEndDate){
                    startMaxTime = eventEndDateTime; 
                    endMaxTime = eventEndDateTime; 
                    startMinTime = eventStartDateTime;
                }
                else if(sessionDate > currentDate && sessionDate != eventStartDate && sessionDate != eventEndDate){
                    startMaxTime = endDayDate; 
                    endMaxTime = endDayDate; 
                    startMinTime = startDayDate;
                } 
                else {
                    startMaxTime = eventEndDateTime;
                    endMaxTime = eventEndDateTime;
                    startMinTime = startDayDate;
                }   


                $('#timepicker3').datetimepicker('destroy');

                $('#timepicker3').datetimepicker({
                    format: 'HH:mm:ss',
                    maxDate: startMaxTime,
                    minDate: startMinTime,
                    icons: {
                        up: "fa fa-arrow-up",
                        down: "fa fa-arrow-down"
                    }
                }).on('dp.change', function (event) {
                    var endMinDate = event.date.format("YYYY-MM-DD HH:mm:ss");
                    $('#timepicker4').datetimepicker('destroy');
                    $('#timepicker4').datetimepicker({
                        format: 'HH:mm:ss',
                        maxDate: endMaxTime,
                        minDate: endMinDate,
                        icons: {
                            up: "fa fa-arrow-up",
                            down: "fa fa-arrow-down"
                        }
                    });   
                });

                $('#timepicker4').datetimepicker('destroy');

                $('#timepicker4').datetimepicker({
                    format: 'HH:mm:ss',
                    maxDate: endMaxTime,
                    minDate: startMinTime,
                    icons: {
                        up: "fa fa-arrow-up",
                        down: "fa fa-arrow-down"
                    }
                });

            });
        });
        /* End event on change */ 
    });
    </script>
    @endif
    
    <script>
        $(document).ready(function() {
            
            /* Start delete record confirmation */
            $(document).on('click','.deleteRecord', function(){
                var data =  confirm("Are you sure you want to delete?");
                if(data == true){
                    $(this).next().submit();
                }
                return false;
            });
            /* End delete record confirmation */

            /* Start alert message fade out */
            $('.alert-success').delay(5000).fadeOut('slow');
            $('.alert-warning').delay(5000).fadeOut('slow');
            $('.alert-danger').delay(5000).fadeOut('slow');
            /* End alert message fade out */
        });    
    </script>
</body>
</html>