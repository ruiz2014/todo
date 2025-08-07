<div class="row padding-1 p-1">
    <div class="col-md-6">
        
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label-2">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control-2 @error('name') is-invalid @enderror" value="{{ old('name', $role?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>

    <div class="col-md-6">
        <div class="form-group mb-4">
            <label for="establishment" class="form-label-2">{{ __('Lugar') }}</label>
            <select name="establishment_id" id="establishment" class="form-control-2 line vld draw @error('establishment_id') is-invalid @enderror">
                <option value="">Seleccione</option>
            @foreach($establishments as $etb)    
                <option value="{{ $etb->id }}" {{ old('establishment_id') == $etb->id ? "selected" : "" }} {{ $role?->establishment_id == $etb->id ? 'selected' : '' }}>{{ $etb->name }}</option>
            @endforeach    
            </select>
            {!! $errors->first('establishment_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>