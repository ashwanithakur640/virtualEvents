<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? 'error' : ''}}">
            <label> Category Name<span class="asterisk">*</span> </label>
            <input class="form-control" placeholder="Category Name" name="name" type="text" value="{{ isset($data->name) ? $data->name : old('name') }}">
            @if ($errors->has('name'))
                <span class="help-block">{{ $errors->first('name') }}</span>
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
</div>
@if(isset($data) && !empty($data))
<div class="row">
    <div class="col-md-6 mb-3">
        @if(isset($data->image) && !empty($data->image))
            <img class ='' width="100%" height="auto" src="{{ asset('assets/images/categories/'.$data->image) }}" alt="image">
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
    <a class="btn btn-warning mr-1" href="{{ asset('admin/events/') }}">Cancel</a>
</div>

