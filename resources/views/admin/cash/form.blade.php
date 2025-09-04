<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="company_id" class="form-label">{{ __('Company Id') }}</label>
            <input type="text" name="company_id" class="form-control @error('company_id') is-invalid @enderror" value="{{ old('company_id', $cash?->company_id) }}" id="company_id" placeholder="Company Id">
            {!! $errors->first('company_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="local_id" class="form-label">{{ __('Local Id') }}</label>
            <input type="text" name="local_id" class="form-control @error('local_id') is-invalid @enderror" value="{{ old('local_id', $cash?->local_id) }}" id="local_id" placeholder="Local Id">
            {!! $errors->first('local_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="user_id" class="form-label">{{ __('User Id') }}</label>
            <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{ old('user_id', $cash?->user_id) }}" id="user_id" placeholder="User Id">
            {!! $errors->first('user_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="amount" class="form-label">{{ __('Amount') }}</label>
            <input type="text" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $cash?->amount) }}" id="amount" placeholder="Amount">
            {!! $errors->first('amount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="type" class="form-label">{{ __('Type') }}</label>
            <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type', $cash?->type) }}" id="type" placeholder="Type">
            {!! $errors->first('type', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="local_cash" class="form-label">{{ __('Local Cash') }}</label>
            <input type="text" name="local_cash" class="form-control @error('local_cash') is-invalid @enderror" value="{{ old('local_cash', $cash?->local_cash) }}" id="local_cash" placeholder="Local Cash">
            {!! $errors->first('local_cash', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="seller" class="form-label">{{ __('Seller') }}</label>
            <input type="text" name="seller" class="form-control @error('seller') is-invalid @enderror" value="{{ old('seller', $cash?->seller) }}" id="seller" placeholder="Seller">
            {!! $errors->first('seller', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>