@extends('vendors.adminLayout')
@section('content')

<section class="content-header">
    <h4>
        Edit Employee
    </h4>
</section>
<div class="content">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form id="sessionForm" action="{{ asset($Prefix . '/update-employee/'.Helper::encrypt($data->id)) }}" method="post" enctype="multipart/form-data">
                                @csrf

                                @method('PATCH')
                                
                                @include('vendors.employee.form')

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
            if(sessionDate){
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
            }

        }); 
        /* End date picker */
        
        $(document).on("focus","#timepicker3", function(){

            var sessionDate = $('#datepicker').val(); 
            if(sessionDate){
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
            }

        });

        $(document).on("focus","#timepicker4", function(){
            var sessionDate = $('#datepicker').val(); 
            if(sessionDate){
                $('#timepicker4').datetimepicker('destroy');
                var startTime = $('#timepicker3').val();
                var endTime = sessionDate + ' ' + startTime;

                $('#timepicker4').datetimepicker({
                    format: 'HH:mm:ss',
                    maxDate: endMaxTime,
                    minDate: endTime,
                    icons: {
                        up: "fa fa-arrow-up",
                        down: "fa fa-arrow-down"
                    }
                });
            }
        });

    });
</script>
@endsection