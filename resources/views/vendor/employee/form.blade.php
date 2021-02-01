<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? 'error' : ''}}">
            <label>Name<span class="asterisk">*</span> </label>
            <input class="form-control" placeholder=" Name" name="name" type="text" value="{{ isset($data->name) ? $data->name : old('name') }}">
            @if ($errors->has('name'))
                <span class="help-block">{{ $errors->first('name') }}</span>
            @endif
        </div>
    </div>

    <div class="col-md-6">
       <div class="form-group {{ $errors->has('email') ? 'error' : ''}}">
            <label>Email<span class="asterisk">*</span> </label>
            <input class="form-control" placeholder=" Email" name="email" type="text" value="{{ isset($data->email) ? $data->email : old('email') }}">
            @if ($errors->has('email'))
                <span class="help-block">{{ $errors->first('email') }}</span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">

        <div class="form-group {{ $errors->has('country_code') ? 'error' : ''}}">
            <label>Country Code<span class="asterisk">*</span></label>
            <select name="country_code" class="form-control form-control-line">
                <option value="">Select</option>
                @if(isset($country_code) && !empty($country_code))
                @foreach($phoneCode as $value)

                    <option value="{{ $value->id }}" <?php echo ((isset($data->country_code) && $data->country_code == $value->id) || old('country_code')== $value->id) ? 'selected' : '' ?> >{{ $value->name }}</option>
                @endforeach
                @endif

            </select>

            @if ($errors->has('country_code'))
                <label class="help-block">{{ $errors->first('country_code') }}</label>
            @endif

        </div>


        
    </div>
    <div class="col-md-6">
    <div class="form-group {{ $errors->has('phone') ? 'error' : ''}}">
            <label> Phone Number<span class="asterisk">*</span> </label>
            <input class="form-control" placeholder="Phone number" name="phone" type="text" value="{{ isset($data->phone) ? $data->phone : old('phone') }}">
            @if ($errors->has('phone'))
                <span class="help-block">{{ $errors->first('phone') }}</span>
            @endif
        </div>
</div>
</div>


<div class="form-actions border-none">
    <button type="submit" id="submit" class="btn btn-primary">Save</button>
    <a class="btn btn-warning mr-1" href="{{ asset($Prefix . '/employee/') }}">Cancel</a>
</div>


