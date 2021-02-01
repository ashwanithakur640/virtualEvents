<?php
use App\Session;
?>
<div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="vertual-inputs">
       <label>{{__('Presenters')}} <a href="javascript:void(0)" data-toggle="tooltip" title="This is a multi selection option to select participants which you have created in users."><i class="fa fa-question-circle"></i></a></label>
     <select class="multiple-select-speaker-conference" name="speakers[]" multiple="multiple">
            @forelse($presenters as $speaker)
             <option value="{{$speaker->id}}" {{(!empty($usersIdArr) && in_array($speaker->id,$usersIdArr))? 'selected':''}}>{{$speaker->first_name}} <small>{{$speaker->email}}</small></option>
            @empty
            <option value="" disabled="true">You have not yet created participants.Create them first</option>
            @endforelse
               
            </select>
           
      </div>
    </div>
 </div>

 
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
                
                @foreach($events as $value)
                    <option value="{{ $value->id }}" <?php echo ((isset($data->event_id) && $data->event_id == $value->id) ) ? 'selected' : '' ?> data-attr-startdate='{{ date("Y-m-d",strtotime($value->start_date_time)) }}'data-attr-enddate='{{ date("Y-m-d",strtotime($value->end_date_time)) }}' data-attr-startTime='{{ date("H:i:s",strtotime($value->start_date_time)) }}' data-attr-endTime='{{ date("H:i:s",strtotime($value->end_date_time)) }}' data-attr-startDateTime='{{ $value->start_date_time }}' data-attr-EndDateTime='{{ $value->end_date_time }}'>{{ $value->name }}</option>  
                @endforeach
               

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

      

