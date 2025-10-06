<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="category_name" class="form-label-2">{{ __('Name') }}</label>
            <input type="text" name="category_name" class="form-control-2 @error('category_name') is-invalid @enderror" value="{{ old('category_name', $category?->category_name) }}" id="category_name" placeholder="Category Name">
            {!! $errors->first('category_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-outline-primary">{{ __('Submit') }}</button>
    </div>
</div>