<!DOCTYPE html>
<html>
<head>
    <title>AmbiPlatforms</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">
    <!-- <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}"> -->
    <link rel="stylesheet" href="{{ asset('assets/ven/assets/vendors/core/core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ven/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet"  href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/ven/assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ven/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ven/assets/css/demo_5/style.css') }}">
    <!-- <link rel="shortcut icon" href="../assets/images/favicon.png" /> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    
    <!-- Start dataTable -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTable/dataTables.bootstrap4.min.css') }}">
    <!-- End dataTable -->

    <link rel="stylesheet" href="{{ asset('css/vendor/custom.css') }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body data-base-url="{{url('/')}}">
    <div class="main-wrapper" id="app">
        @include('vendor.header')

        <div class="page-wrapper">
            <div class="page-content">
                @yield('content')

            </div>
            <footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between">
                <p class="text-muted text-center text-md-left">Copyright © 2020 
                    <a href="https://www.vep.com" target="_blank">Virtual Events</a>. All rights reserved
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
    <!-- @stack('plugin-scripts') -->

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

    <!-- Start ckeditor -->
    <script src="{{ asset('js/ckeditor/ckeditor.js')}}" type="text/javascript"></script>
    <script>
        if ($('#summary-ckeditor').length > 0) {
            CKEDITOR.replace( 'summary-ckeditor' );
        }
    </script>
    <!-- End ckeditor -->

    <!-- @stack('custom-scripts') -->

    @yield('script')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {


            // $(function () {
            //     $('#datetimepicker1').datetimepicker({
            //         format: 'YYYY-MM-DD HH:mm:ss',
            //         sideBySide: true,
            //         icons: {
            //             up: "fa fa-arrow-up",
            //             down: "fa fa-arrow-down",
            //             previous: 'fas fa-chevron-left',
            //             next: 'fas fa-chevron-right',
            //         }
            //     });
            //     $('#datetimepicker2').datetimepicker({
            //         format: 'YYYY-MM-DD HH:mm:ss',
            //         sideBySide: true,
            //         useCurrent: false, 
            //         icons: {
            //             up: "fa fa-arrow-up",
            //             down: "fa fa-arrow-down",
            //             previous: 'fas fa-chevron-left',
            //             next: 'fas fa-chevron-right',
            //         }
            //     });
            //     $("#datetimepicker1").on("dp.change", function (e) {
            //         $('#datetimepicker2').data("DateTimePicker").minDate(e.date);
            //     });
            //     $("#datetimepicker2").on("dp.change", function (e) {
            //         $('#datetimepicker1').data("DateTimePicker").maxDate(e.date);
            //     });
            // });


            /* Start date picker */
            // $( "#datepicker" ).datepicker({ 
            //     dateFormat: 'yy-mm-dd',
            //     minDate: new Date(),
            // });
            /* End date picker */

            /* Start time picker */
            // $(function () {
            //     $('#timepicker3').datetimepicker({
            //         // format: 'HH:mm:ss',
            //         format: 'LT'
            //     });
            // });

            // $(function () {
            //     $('#timepicker4').datetimepicker({
            //         format: 'HH:mm:ss',
            //     });
            // });
            /* End time picker */

            /* Start on change */
            $(document).on("change","#eventSelect", function(){
                var eventStartDate = $('option:selected', this).attr('data-attr-startdate');
                var eventEndDate = $('option:selected', this).attr('data-attr-enddate');
                var eventStartTime = $('option:selected', this).attr('data-attr-startTime');
                var eventEndTime = $('option:selected', this).attr('data-attr-endTime');
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
                    minDate: minDate,
                    maxDate: eventEndDate,
                });
                /* Start date picker */
                $("#datepicker").on("change",function(){
                    var dt = $("#datepicker").datepicker( 'getDate');
                    var startDayDate = d.getFullYear() + "-" + (String(d. getMonth() + 1). padStart(2, '0')) + "-" + String(d. getDate()). padStart(2, '0')+ ' ' + "00:00:00";
                    var endDayDate = d.getFullYear() + "-" + (String(d. getMonth() + 1). padStart(2, '0')) + "-" + String(d. getDate()). padStart(2, '0')+ ' ' + "23:59:59";
                    var sessionDate = $(this).val();
                    var todayStartDateTime = currentDate + ' ' + eventStartTime;
                    var todayEndDateTime = currentDate + ' ' + eventEndTime;
                    if(sessionDate == currentDate && sessionDate != eventEndDate && eventStartTime < currentTime){
                        startMaxTime = endDayDate;
                        endMaxTime = endDayDate;
                        startMinTime = currentDateTime;
                        console.log(1);
                    }
                    else if(sessionDate == currentDate && sessionDate != eventEndDate  && eventStartTime > currentTime){
                        startMaxTime = endDayDate;
                        endMaxTime = todayEndDateTime;
                        startMinTime = todayStartDateTime;
                        console.log(2);
                    }
                    else if(sessionDate == currentDate && sessionDate == eventEndDate && eventStartTime < currentTime){
                        startMaxTime = todayEndDateTime;
                        endMaxTime = todayEndDateTime;
                        startMinTime = currentDateTime;
                        console.log(3);
                    } 
                    else if(sessionDate == currentDate && sessionDate == eventEndDate && eventStartTime > currentTime){
                        startMaxTime = todayEndDateTime;
                        endMaxTime = todayEndDateTime;
                        startMinTime = todayStartDateTime;
                        console.log(4);
                    } 
                    else if(sessionDate > currentDate && sessionDate == eventStartDate && sessionDate != eventEndDate){
                        startMaxTime = endDayDate; 
                        endMaxTime = endDayDate; 
                        startMinTime = todayStartDateTime;
                        console.log(5);
                    }
                    else if(sessionDate > currentDate && sessionDate == eventStartDate && sessionDate == eventEndDate){
                        startMaxTime = todayEndDateTime; 
                        endMaxTime = todayEndDateTime; 
                        startMinTime = todayStartDateTime;
                        console.log(6);
                    }
                    else if(sessionDate > currentDate && sessionDate != eventStartDate && sessionDate != eventEndDate){
                        startMaxTime = endDayDate; 
                        endMaxTime = endDayDate; 
                        startMinTime = startDayDate;
                        console.log(7);
                    } 
                    else {
                        startMaxTime = todayEndDateTime;
                        endMaxTime = todayEndDateTime;
                        startMinTime = startDayDate;
                        console.log(8);
                    }
                    $('#timepicker3').val('');
                    $('#timepicker4').val('');
                    /* Start time picker */
                    $('#timepicker3').datetimepicker({
                        format: 'HH:mm:ss',
                        maxDate: startMaxTime,
                        minDate: startMinTime,
                        useCurrent: false,
                        icons: {
                            up: "fa fa-arrow-up",
                            down: "fa fa-arrow-down"
                        }
                    }).on('dp.change', function (event) {
                        // $('#timepicker4').val('');
                        if ( typeof event.date !== typeof undefined && event.date !== false && event.date !==''){
                            var endMinDate = event.date.format("YYYY-MM-DD HH:mm:ss");
                        }
                        $('#timepicker4').datetimepicker({
                            format: 'HH:mm:ss',
                            maxDate: endMaxTime,
                            minDate: endMinDate,
                            useCurrent: false,
                            icons: {
                                up: "fa fa-arrow-up",
                                down: "fa fa-arrow-down"
                            }
                        });                   
                    });
                    /* End time picker */
                });
                /* End date picker */
            });
            /* End on change */

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







@section('script')
<script>
    $(document).ready(function() { 
        /* Start on document ready */
        var eventStartDate = $('#eventSelect').find('option:selected', this).attr('data-attr-startdate');
        var eventEndDate = $('#eventSelect').find('option:selected', this).attr('data-attr-enddate');
        var eventStartTime = $('#eventSelect').find('option:selected', this).attr('data-attr-startTime');
        var eventStartDateTime = $('#eventSelect').find('option:selected', this).attr('data-attr-startDateTime');
        var eventEndDateTime = $('#eventSelect').find('option:selected', this).attr('data-attr-EndDateTime');
        var d = new Date();
        var currentDateTime = d.getFullYear() + "-" + (String(d. getMonth() + 1). padStart(2, '0')) + "-" + String(d. getDate()). padStart(2, '0')+ ' ' + d.getHours() + ":" + (String(d. getMinutes()). padStart(2, '0')) + ":" + String(d. getSeconds()). padStart(2, '0');
        var currentDate = d.getFullYear() + "-" + (String(d. getMonth() + 1). padStart(2, '0')) + "-" + String(d. getDate()). padStart(2, '0');
        var currentTime = d.getHours() + ":" + (String(d. getMinutes()). padStart(2, '0')) + ":" + String(d. getSeconds()). padStart(2, '0');
        
        if(eventStartDate >= currentDate){
            minDate = eventStartDate;
        } else{
            minDate = new Date();
        }

        $( "#datepicker" ).datepicker({ 
            dateFormat: 'yy-mm-dd',
            maxDate: eventEndDate,
            minDate: minDate,
        });

        /* Start date picker */
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
            $('#timepicker4').val('');

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
        /* End date picker */
        
        $(document).on("focus","#timepicker3", function(){

            var sessionDate = $('#datepicker').val(); 
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
</script>
@endsection