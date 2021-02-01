<div class="col-md-6">
    <div class="form-group {{ $errors->has('page_id') ? 'error' : ''}}">
        <label>Page<span class="asterisk">*</span></label>
        <select name="page_id" class="form-control form-control-line">
            <option value="">Select</option>
            @if(isset($data) && !empty($data))
            @foreach($data as $value)

                <option value="{{ $value->id }}" <?php echo ((isset($page->page_id) && $page->page_id == $value->id) || old('page_id')== $value->id) ? 'selected' : '' ?> >{{ $value->name }}</option>
            @endforeach
            @endif

        </select>

        @if ($errors->has('page_id'))
            <label class="help-block">{{ $errors->first('page_id') }}</label>
        @endif

    </div>
</div>
<div class="col-md-6">
    <div class="form-group {{ $errors->has('title') ? 'error' : ''}}">
        <label>Title<span class="asterisk">*</span></label>
        <input class="form-control" placeholder="Title" name="title" type="text" value="{{ isset($page->title) ? $page->title : old('title') }}">
        @if ($errors->has('title'))
            <label class="help-block">{{ $errors->first('title') }}</label>
        @endif

    </div>
</div>
<div class="col-md-12">
    <div class="form-group {{ $errors->has('description') ? 'error' : ''}}">
        <label>Description<span class="asterisk">*</span></label>
        <textarea id="summary-ckeditor" class="form-control" name="description" data-rule-required="true"  data-msg-required="Please enter description">
             {{ isset($page->description) ? $page->description :old('description') }}  
        </textarea>
        @if ($errors->has('description'))
            <label class="help-block">{{ $errors->first('description') }}</label>
        @endif

    </div>
</div>
<div class="form-actions border-none">
    <button type="submit" class="btn btn-primary">Save</button>
    <a class="btn btn-warning mr-1" href="{{ asset('admin/frontend-contents/') }}">Cancel</a>
</div>
