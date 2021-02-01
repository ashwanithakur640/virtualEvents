<?php
use App\Session;
?>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? 'error' : ''}}">
            <label> Session Name<span class="asterisk">*</span> </label>
            <input class="form-control" placeholder="Session Name" name="name" type="text" value="{{ isset($data->name) ? $data->name : old('name') }}">
            @if ($errors->has('name'))
                <span class="help-block">{{ $errors->first('name') }}</span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('event_id') ? 'error' : ''}}">
            <label>Event<span class="asterisk">*</span></label>
            <select id="eventSelect" name="event_id" class="form-control form-control-line">
                <option value="">Select</option>
                @if(isset($events) && !empty($events))
                @foreach($events as $value)
                    <option value="{{ $value->id }}" <?php echo ((isset($data->event_id) && $data->event_id == $value->id) || old('event_id')== $value->id) ? 'selected' : '' ?> data-attr-startdate='{{ date("Y-m-d",strtotime($value->start_date_time)) }}'' data-attr-enddate='{{ date("Y-m-d",strtotime($value->end_date_time)) }}' data-attr-startTime='{{ date("H:i:s",strtotime($value->start_date_time)) }}' data-attr-endTime='{{ date("H:i:s",strtotime($value->end_date_time)) }}' data-attr-startDateTime='{{ $value->start_date_time }}' data-attr-EndDateTime='{{ $value->end_date_time }}'>{{ $value->name }}</option>  
                @endforeach
                @endif

            </select>
            @if ($errors->has('event_id'))
                <label class="help-block">{{ $errors->first('event_id') }}</label>
            @endif

        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('date') ? 'error' : ''}}">
            <label> Session Date<span class="asterisk">*</span> </label>
            <input id="datepicker" class="form-control" autocomplete="off" placeholder="Session Date" name="date" type="text" value="{{ isset($data->date) ? $data->date : old('date') }}">
            @if ($errors->has('date'))
                <span class="help-block">{{ $errors->first('date') }}</span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('start_time') ? 'error' : ''}}">
            <label> Start Time<span class="asterisk">*</span> </label>
            <input id='timepicker3' autocomplete="off" class="form-control" placeholder="00:00:00" name="start_time" type="text" value="{{ isset($data->start_time) ? $data->start_time : old('start_time') }}">
            @if ($errors->has('start_time'))
                <span class="help-block">{{ $errors->first('start_time') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('end_time') ? 'error' : ''}}">
            <label> End Time<span class="asterisk">*</span> </label>
            <input id='timepicker4' autocomplete="off" class="form-control" placeholder="00:00:00" name="end_time" type="text" value="{{ isset($data->end_time) ? $data->end_time : old('end_time') }}">
            @if ($errors->has('end_time'))
                <span class="help-block">{{ $errors->first('end_time') }}</span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('image') ? 'error' : ''}}">
            <label>Image<span class="asterisk">*</span></label>
            <input class="form-control upload_doc" name="image" type="file">
            <span class="">
                <p><small><b>Note: </b><i>Only .jpg, .jpeg and .png format are accepted.</i></small></p>
            </span>
            @if ($errors->has('image'))
                <label class="help-block">{{ $errors->first('image') }}</label>
            @endif

        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group {{ $errors->has('type_id') ? 'error' : ''}}">
            <label>Session Type<span class="asterisk">*</span></label>


            <select class="multiple-select-speaker-conference" name="type_id" >
                                
                <option value="<?= Session::TYPE_WEBINAR ?>" {{(!empty($data->type_id) && ($data->type_id == Session::TYPE_WEBINAR ))? ' selected':''}}>Webinar</option>
             
                <option value="<?= Session::TYPE_VC ?>" {{(!empty($data->type_id) && ($data->type_id == Session::TYPE_VC ))? ' selected':''}} >Video Conferencing</option>

            </select>

            @if ($errors->has('type_id'))
                <label class="help-block">{{ $errors->first('type_id') }}</label>
            @endif

        </div>
    </div>



</div>
@if(isset($data) && !empty($data))
<div class="row">
    <div class="col-md-6 mb-3">
        @if(isset($data->image) && !empty($data->image))
            <img class ='' width="100%" height="auto" src="{{ asset('assets/images/session/'.$data->image) }}" alt="image">
        @endif

    </div>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="form-group {{ $errors->has('description') ? 'error' : ''}}">
            <label>Description<span class="asterisk">*</span></label>
            <textarea id="summary-ckeditor" class="form-control" name="description" data-rule-required="true"  data-msg-required="Please enter description">
                 {{ isset($data->description) ? $data->description :old('description') }}  
            </textarea>
            @if ($errors->has('description'))
                <label class="help-block">{{ $errors->first('description') }}</label>
            @endif

        </div>
    </div>
</div>

<div class="form-actions border-none">
    <button type="submit" class="btn btn-primary">Save</button>
    <a class="btn btn-warning mr-1" href="{{ asset($Prefix . '/session/') }}">Cancel</a>
</div>

      


       @yield('script')

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