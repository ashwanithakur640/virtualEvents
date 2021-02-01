<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? 'error' : ''}}">
            <label> Event Name<span class="asterisk">*</span> </label>
            <input class="form-control" placeholder="Event Name" name="name" type="text" value="{{ isset($data->name) ? $data->name : old('name') }}">
            @if ($errors->has('name'))
                <span class="help-block">{{ $errors->first('name') }}</span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('type') ? 'error' : ''}}">
            <label> Event Type<span class="asterisk">*</span> </label>
            <select id="eventType" name="type" class="form-control form-control-line">
                <option value="">Select</option>
                <option value="Free" <?php echo ((isset($data->type) && $data->type == 'Free') || old('type')== 'Free') ? 'selected' : '' ?> >Free</option>
                <option value="Paid" <?php echo ((isset($data->type) && $data->type == 'Paid') || old('type')== 'Paid') ? 'selected' : '' ?> >Paid</option>
            </select>
            @if ($errors->has('type'))
                <span class="help-block">{{ $errors->first('type') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-6 eventAmount" style="display: none;">
        <div class="form-group {{ $errors->has('amount') ? 'error' : ''}}">
            <label>Amount<span class="asterisk">*</span> </label>
            <input class="form-control freeEvent" placeholder="Amount" name="amount" type="text" value="{{ isset($data->amount) ? $data->amount : old('amount') }}">
            @if ($errors->has('amount'))
                <span class="help-block">{{ $errors->first('amount') }}</span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('title') ? 'error' : ''}}">
            <label> Event Title<span class="asterisk">*</span> </label>
            <input class="form-control" placeholder="Event Title" name="title" type="text" value="{{ isset($data->title) ? $data->title : old('title') }}">
            @if ($errors->has('title'))
                <span class="help-block">{{ $errors->first('title') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
    <div class="form-group {{ $errors->has('category_id') ? 'error' : ''}}">
        <label>Category<span class="asterisk">*</span></label>
        <select name="category_id" class="form-control form-control-line">
            <option value="">Select</option>
            @if(isset($categories) && !empty($categories))
            @foreach($categories as $value)
                <option value="{{ $value->id }}" <?php echo ((isset($data->category_id) && $data->category_id == $value->id) || old('category_id')== $value->id) ? 'selected' : '' ?> >{{ $value->name }}</option>
            @endforeach
            @endif

        </select>
        @if ($errors->has('category_id'))
            <label class="help-block">{{ $errors->first('category_id') }}</label>
        @endif

    </div>
</div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('start_date_time') ? 'error' : ''}}">
            <label> Start Date & Time<span class="asterisk">*</span> </label>
            <input id="start_date" class="form-control" placeholder="Start Date & Time" name="start_date_time" type="text" value="{{ isset($data->start_date_time) ? $data->start_date_time : old('start_date_time') }}">
            @if ($errors->has('start_date_time'))
                <span class="help-block">{{ $errors->first('start_date_time') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('end_date_time') ? 'error' : ''}}">
            <label> End Date & Time<span class="asterisk">*</span> </label>
            <input id="end_date" class="form-control" placeholder="End Date & Time" name="end_date_time" type="text" value="{{ isset($data->end_date_time) ? $data->end_date_time : old('end_date_time') }}" data-parsley-daterangevalidation>
            @if ($errors->has('end_date_time'))
                <span class="help-block">{{ $errors->first('end_date_time') }}</span>
            @endif
        </div>
    </div>
</div>
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
    <button type="submit" id="submit" class="btn btn-primary">Save</button>
    <a class="btn btn-warning mr-1" href="{{ asset($Prefix . '/events/') }}">Cancel</a>
</div>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>  
<script type="text/javascript">
    tinymce.init({
        selector: 'textarea.tinymce-editor',
        height: 300,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        content_css: '//www.tiny.cloud/css/codepen.min.css'
    });
</script>


@section('script')
<script>
    $(document).on('change','#eventType', function(){
        var selectValue = $(this).val();
        if(selectValue == 'Paid'){
            $(".freeEvent").removeAttr("disabled");
            $('.eventAmount').show();
        } else{
            $(".freeEvent").attr("disabled", "disabled");
            $('.eventAmount').hide();
        }
    });
    var selectedValue = $('#eventType').val();
    if(selectedValue == 'Paid'){
        $(".freeEvent").removeAttr("disabled");
        $('.eventAmount').show();
    }
</script>
@endsection
        
    
        