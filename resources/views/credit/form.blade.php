<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="local_id" class="form-label">{{ __('Local Id') }}</label>
            <input type="text" name="local_id" class="form-control @error('local_id') is-invalid @enderror" value="{{ old('local_id', $attention?->local_id) }}" id="local_id" placeholder="Local Id">
            {!! $errors->first('local_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="customer_id" class="form-label">{{ __('Customer Id') }}</label>
            <input type="text" name="customer_id" class="form-control @error('customer_id') is-invalid @enderror" value="{{ old('customer_id', $attention?->customer_id) }}" id="customer_id" placeholder="Customer Id">
            {!! $errors->first('customer_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="sunat_code" class="form-label">{{ __('Sunat Code') }}</label>
            <input type="text" name="sunat_code" class="form-control @error('sunat_code') is-invalid @enderror" value="{{ old('sunat_code', $attention?->sunat_code) }}" id="sunat_code" placeholder="Sunat Code">
            {!! $errors->first('sunat_code', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="document_code" class="form-label">{{ __('Document Code') }}</label>
            <input type="text" name="document_code" class="form-control @error('document_code') is-invalid @enderror" value="{{ old('document_code', $attention?->document_code) }}" id="document_code" placeholder="Document Code">
            {!! $errors->first('document_code', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="reference_document" class="form-label">{{ __('Reference  Document') }}</label>
            <input type="text" name="reference _document" class="form-control @error('reference _document') is-invalid @enderror" value="{{ old('reference _document', $attention?->reference _document) }}" id="reference_document" placeholder="Reference  Document">
            {!! $errors->first('reference _document', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="currency" class="form-label">{{ __('Currency') }}</label>
            <input type="text" name="currency" class="form-control @error('currency') is-invalid @enderror" value="{{ old('currency', $attention?->currency) }}" id="currency" placeholder="Currency">
            {!! $errors->first('currency', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="type_payment" class="form-label">{{ __('Type Payment') }}</label>
            <input type="text" name="type_payment" class="form-control @error('type_payment') is-invalid @enderror" value="{{ old('type_payment', $attention?->type_payment) }}" id="type_payment" placeholder="Type Payment">
            {!! $errors->first('type_payment', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="total" class="form-label">{{ __('Total') }}</label>
            <input type="text" name="total" class="form-control @error('total') is-invalid @enderror" value="{{ old('total', $attention?->total) }}" id="total" placeholder="Total">
            {!! $errors->first('total', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="seller" class="form-label">{{ __('Seller') }}</label>
            <input type="text" name="seller" class="form-control @error('seller') is-invalid @enderror" value="{{ old('seller', $attention?->seller) }}" id="seller" placeholder="Seller">
            {!! $errors->first('seller', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="serie" class="form-label">{{ __('Serie') }}</label>
            <input type="text" name="serie" class="form-control @error('serie') is-invalid @enderror" value="{{ old('serie', $attention?->serie) }}" id="serie" placeholder="Serie">
            {!! $errors->first('serie', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="identifier" class="form-label">{{ __('Identifier') }}</label>
            <input type="text" name="identifier" class="form-control @error('identifier') is-invalid @enderror" value="{{ old('identifier', $attention?->identifier) }}" id="identifier" placeholder="Identifier">
            {!! $errors->first('identifier', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="numeration" class="form-label">{{ __('Numeration') }}</label>
            <input type="text" name="numeration" class="form-control @error('numeration') is-invalid @enderror" value="{{ old('numeration', $attention?->numeration) }}" id="numeration" placeholder="Numeration">
            {!! $errors->first('numeration', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="hash" class="form-label">{{ __('Hash') }}</label>
            <input type="text" name="hash" class="form-control @error('hash') is-invalid @enderror" value="{{ old('hash', $attention?->hash) }}" id="hash" placeholder="Hash">
            {!! $errors->first('hash', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="resume" class="form-label">{{ __('Resume') }}</label>
            <input type="text" name="resume" class="form-control @error('resume') is-invalid @enderror" value="{{ old('resume', $attention?->resume) }}" id="resume" placeholder="Resume">
            {!! $errors->first('resume', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cdr" class="form-label">{{ __('Cdr') }}</label>
            <input type="text" name="cdr" class="form-control @error('cdr') is-invalid @enderror" value="{{ old('cdr', $attention?->cdr) }}" id="cdr" placeholder="Cdr">
            {!! $errors->first('cdr', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="success" class="form-label">{{ __('Success') }}</label>
            <input type="text" name="success" class="form-control @error('success') is-invalid @enderror" value="{{ old('success', $attention?->success) }}" id="success" placeholder="Success">
            {!! $errors->first('success', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="message" class="form-label">{{ __('Message') }}</label>
            <input type="text" name="message" class="form-control @error('message') is-invalid @enderror" value="{{ old('message', $attention?->message) }}" id="message" placeholder="Message">
            {!! $errors->first('message', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="low_motive" class="form-label">{{ __('Low Motive') }}</label>
            <input type="text" name="low_motive" class="form-control @error('low_motive') is-invalid @enderror" value="{{ old('low_motive', $attention?->low_motive) }}" id="low_motive" placeholder="Low Motive">
            {!! $errors->first('low_motive', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="low" class="form-label">{{ __('Low') }}</label>
            <input type="text" name="low" class="form-control @error('low') is-invalid @enderror" value="{{ old('low', $attention?->low) }}" id="low" placeholder="Low">
            {!! $errors->first('low', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="guide" class="form-label">{{ __('Guide') }}</label>
            <input type="text" name="guide" class="form-control @error('guide') is-invalid @enderror" value="{{ old('guide', $attention?->guide) }}" id="guide" placeholder="Guide">
            {!! $errors->first('guide', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="completed" class="form-label">{{ __('Completed') }}</label>
            <input type="text" name="completed" class="form-control @error('completed') is-invalid @enderror" value="{{ old('completed', $attention?->completed) }}" id="completed" placeholder="Completed">
            {!! $errors->first('completed', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="dispatched" class="form-label">{{ __('Dispatched') }}</label>
            <input type="text" name="dispatched" class="form-control @error('dispatched') is-invalid @enderror" value="{{ old('dispatched', $attention?->dispatched) }}" id="dispatched" placeholder="Dispatched">
            {!! $errors->first('dispatched', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="received" class="form-label">{{ __('Received') }}</label>
            <input type="text" name="received" class="form-control @error('received') is-invalid @enderror" value="{{ old('received', $attention?->received) }}" id="received" placeholder="Received">
            {!! $errors->first('received', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="status" class="form-label">{{ __('Status') }}</label>
            <input type="text" name="status" class="form-control @error('status') is-invalid @enderror" value="{{ old('status', $attention?->status) }}" id="status" placeholder="Status">
            {!! $errors->first('status', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>