<div class="row padding-1 p-1">
    <div class="col-md-6">
        <div class="form-group mb-2 mb20">
            <label for="local_name" class="form-label-2">{{ __('Local Name') }}</label>
            <input type="text" name="local_name" class="form-control-2 @error('local_name') is-invalid @enderror" value="{{ old('local_name', $local?->local_name) }}" id="local_name" placeholder="Local Name">
            {!! $errors->first('local_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-6">    
        <div class="form-group mb-2 mb20">
            <label for="phone" class="form-label-2">{{ __('Phone') }}</label>
            <input type="text" name="phone" class="form-control-2 @error('phone') is-invalid @enderror" value="{{ old('phone', $local?->phone) }}" id="phone" placeholder="Phone">
            {!! $errors->first('phone', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-6">    
        <div class="form-group mb-2 mb20">
            <label for="address" class="form-label-2">{{ __('Address') }}</label>
            <input type="text" name="address" class="form-control-2 @error('address') is-invalid @enderror" value="{{ old('address', $local?->address) }}" id="address" placeholder="Address">
            {!! $errors->first('address', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>