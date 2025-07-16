<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label-2">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control-2 @error('name') is-invalid @enderror" value="{{ old('name', $company?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="company_name" class="form-label-2">{{ __('Company Name') }}</label>
            <input type="text" name="company_name" class="form-control-2 @error('company_name') is-invalid @enderror" value="{{ old('company_name', $company?->company_name) }}" id="company_name" placeholder="Company Name">
            {!! $errors->first('company_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="document" class="form-label-2">{{ __('Document') }}</label>
            <input type="text" name="document" class="form-control-2 @error('document') is-invalid @enderror" value="{{ old('document', $company?->document) }}" id="document" placeholder="Document">
            {!! $errors->first('document', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="address" class="form-label-2">{{ __('Address') }}</label>
            <input type="text" name="address" class="form-control-2 @error('address') is-invalid @enderror" value="{{ old('address', $company?->address) }}" id="address" placeholder="Address">
            {!! $errors->first('address', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="ubigeo" class="form-label-2">{{ __('Ubigeo') }}</label>
            <input type="text" name="ubigeo" class="form-control-2 @error('ubigeo') is-invalid @enderror" value="{{ old('ubigeo', $company?->ubigeo) }}" id="ubigeo" placeholder="Ubigeo">
            {!! $errors->first('ubigeo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="sector_id" class="form-label-2">{{ __('Sector Id') }}</label>
            <input type="text" name="sector_id" class="form-control-2 @error('sector_id') is-invalid @enderror" value="{{ old('sector_id', $company?->sector_id) }}" id="sector_id" placeholder="Sector Id">
            {!! $errors->first('sector_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="number_employees" class="form-label-2">{{ __('Number Employees') }}</label>
            <input type="text" name="number_employees" class="form-control-2 @error('number_employees') is-invalid @enderror" value="{{ old('number_employees', $company?->number_employees) }}" id="number_employees" placeholder="Number Employees">
            {!! $errors->first('number_employees', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="number_subsidiary" class="form-label-2">{{ __('Number Subsidiary') }}</label>
            <input type="text" name="number_subsidiary" class="form-control-2 @error('number_subsidiary') is-invalid @enderror" value="{{ old('number_subsidiary', $company?->number_subsidiary) }}" id="number_subsidiary" placeholder="Number Subsidiary">
            {!! $errors->first('number_subsidiary', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>