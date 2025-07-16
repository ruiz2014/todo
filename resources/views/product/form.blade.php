<div class="row padding-1 p-1">
  
    <div class="col-md-6">
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label-2">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control-2 @error('name') is-invalid @enderror" value="{{ old('name', $product?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">    
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label-2">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control-2 @error('description') is-invalid @enderror" value="{{ old('description', $product?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">    
        <div class="form-group mb-2 mb20">
            <label for="price" class="form-label-2">{{ __('Price') }}</label>
            <input type="text" name="price" class="form-control-2 @error('price') is-invalid @enderror" value="{{ old('price', $product?->price) }}" id="price" placeholder="Price">
            {!! $errors->first('price', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    @if($stock == 0)
    <div class="col-md-6">
        <div class="form-group mb-4 mb20">
            <label for="stock" class="form-label-2">{{ __('Stock') }}</label>
            <span style="display:block;border-bottom: 1px solid #ced4da;" >{{ $product?->stock }}</span>
        </div>
    </div>
    @else
    <div class="col-md-6">    
        <div class="form-group mb-2 mb20">
            <label for="price" class="form-label-2">{{ __('Stock') }}</label>
            <input type="number" name="stock" class="form-control-2 @error('stock') is-invalid @enderror" value="{{ old('stock') }}" id="stock" placeholder="Stock">
            {!! $errors->first('stock', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    @endif

    <div class="col-md-6">
        <div class="form-group mb-4">
            <label for="category_id" class="form-label-2">{{ __('Category Id') }}</label>
            <select name="category_id" id="" class="form-control-2 line vld draw">
                @foreach($categories as $key => $value)
                <option value="{{ $key }}" {{$product?->category_id == $key ? 'selected' : ''}}>{{ $value }}</option>
                @endforeach
            </select>
            {!! $errors->first('category_id', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}  
        </div>
    </div>


    <div class="col-md-6"> 
        <div class="form-group mb-2 mb20">
            <label for="minimo" class="form-label-2">{{ __('Minimo') }}</label>
            <input type="text" name="minimo" class="form-control-2 @error('minimo') is-invalid @enderror" value="{{ old('minimo', $product?->minimo) }}" id="minimo" placeholder="Minimo">
            {!! $errors->first('minimo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>