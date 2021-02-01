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
            <input id="start_date" class="form-control" autocomplete="off" placeholder="Start Date & Time" name="start_date_time" type="text" value="{{ isset($data->start_date_time) ? $data->start_date_time : old('start_date_time') }}">
            @if ($errors->has('start_date_time'))
                <span class="help-block">{{ $errors->first('start_date_time') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('end_date_time') ? 'error' : ''}}">
            <label> End Date & Time<span class="asterisk">*</span> </label>
            <input id="end_date" class="form-control" autocomplete="off" placeholder="End Date & Time" name="end_date_time" type="text" value="{{ isset($data->end_date_time) ? $data->end_date_time : old('end_date_time') }}" data-parsley-daterangevalidation>
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
@if(isset($data) && !empty($data))
    @php
        $disable = '';
    @endphp
@else
    @if(isset($payment))
        @php
            $disable = '';
        @endphp
    @else
        @php
            $disable = 'disabled';
        @endphp    
    @endif
@endif

<div class="form-actions border-none">
    <button type="submit" id="submit" class="btn btn-primary" {{ $disable }}>Save</button>
    <a class="btn btn-warning mr-1" href="{{ asset($Prefix . '/events/') }}">Cancel</a>
</div>
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

<!-- <script>
    $(function () {
        $('#start_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            sideBySide: true,
            useCurrent: false,
            minDate: new Date(),
            icons: {
                up: 'fa fa-angle-up',
                down: 'fa fa-angle-down',
                previous: 'far fa-chevron-left',
                next: 'far fa-chevron-right',
            }
        });
    });
    $(function () {
        $('#end_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            sideBySide: true,
            useCurrent: false,
            icons: {
                up: 'fa fa-angle-up',
                down: 'fa fa-angle-down',
                previous: 'far fa-chevron-left',
                next: 'far fa-chevron-right',
            }
        });
    });
    $("#start_date").on("dp.change", function (e) {
        $('#end_date').data("DateTimePicker").minDate(e.date);
    });
    $("#end_date").on("dp.change", function (e) {
        $('#start_date').data("DateTimePicker").maxDate(e.date);
    });
</script> -->

<!-- Start date and time picker -->
<script type="text/javascript">
    $('#start_date').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        useCurrent: true,
        sideBySide: true,
        minDate: new Date(),
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fa fa-angle-up',
            down: 'fa fa-angle-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'far fa-times-circle'
        }
    })
</script>
<script type="text/javascript">
    $('#end_date').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        useCurrent: true,
        sideBySide: true,
        minDate: new Date(),
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
             up: 'fa fa-angle-up',
            down: 'fa fa-angle-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'far fa-times-circle'
        }
    })
</script>

<script type="text/javascript">
    $(document).ready(function () {
        window.Parsley.addValidator('daterangevalidation', {
            validateString: function(value) {
                var allowed = true;

                var date1 = new Date($('#start_date').val());
                var date2 = new Date($('#end_date').val());

                if(date1.getTime() >= date2.getTime()){
                    return false;
                }
                return true;
            },
            messages: {
                en: 'End date must be after start date.',
            }
        });

        $('#eventForm #submit').on('click', function () {
            $('#eventForm').parsley().validate();
            validateFront();
        });

        var validateFront = function () {
            if (true === $('#eventForm').parsley().isValid()) {
                $('.bs-callout-info').removeClass('hidden');
                $('.bs-callout-warning').addClass('hidden');
            } else {
                $('.bs-callout-info').addClass('hidden');
                $('.bs-callout-warning').removeClass('hidden');
            }
        };
    });
</script>
<!-- End date and time picker -->

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
@endsection
               