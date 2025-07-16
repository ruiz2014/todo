<div class="row padding-1 p-1">

    <div class="col-md-6">    
        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label-2">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control-2 @error('name') is-invalid @enderror" value="{{ old('name', $user?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-2 mb20">
            <label for="email" class="form-label-2">{{ __('Email') }}</label>
            <input type="text" name="email" class="form-control-2 @error('email') is-invalid @enderror" value="{{ old('email', $user?->email) }}" id="email" placeholder="Email">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group mb-4">
            <label for="establishment" class="form-label-2">{{ __('Lugar') }}</label>
            <select name="establishment" id="establishment" class="form-control-2 line vld draw @error('establishment') is-invalid @enderror">
                <option value="">Seleccione</option>
            @foreach($establishments as $etb)    
                <option value="{{ $etb->id }}" {{ old('establishment') == $etb->id ? "selected" : "" }} {{ $workplace?->establishment_id == $etb->id ? 'selected' : '' }}>{{ $etb->name }}</option>
            @endforeach    
            </select>
            {!! $errors->first('establishment', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group mb-4">
            <label for="rol" class="form-label-2">{{ __('Rol') }}</label>
            <select name="rol" id="rol" class="form-control-2 line vld draw @error('rol') is-invalid @enderror">
                <option value="">Seleccione Rol</option>
        @if($workplace?->establishment_id )   
            @foreach($roles as $role)    
                <option value="{{ $role->id }}" {{ $user?->rol == $role->id ? 'selected' : ''}}>{{ $role->name }}</option>
            @endforeach 
        @endif       
            </select>
            {!! $errors->first('rol', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    

    <div class="col-md-6">
        <div class="form-group mb-4">
            <label for="local" class="form-label-2">{{ __('Locals') }}</label>
            <select name="local" id="local" class="form-control-2 line vld draw @error('local') is-invalid @enderror">
                <option value="">Seleccione Establecimiento</option>
            @if($user?->rol)   
                @foreach($locals as $local)    
                    <option value="{{ $local->id }}" {{ $workplace?->local_id == $local->id ? 'selected' : ''}}>{{ $local->local_name }}</option>
                @endforeach 
            @endif 
            </select>
            {!! $errors->first('local', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>

    
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
@section('script')  
    <script>
        let establishment = document.getElementById('establishment');
        // let local = document.getElementById('local');
        let rol = document.getElementById('rol');
        let local = document.getElementById('local');
        establishment.addEventListener('change', ()=>{
            const id = establishment.value;
            local.innerHTML = '<option value="">Seleccione Establecimiento</option>';
            // alert("hola");
            fetch(`{{ url('tool/role/${id}') }}`)
            .then(response => response.json())
            .then(data => {
                console.log(data)
                rol.innerHTML = '<option value="">Seleccione Rol</option>';
                 // Agregar nuevas opciones
                data.forEach(data => {
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.textContent = data.name;
                    rol.appendChild(option);
                });
            })

        });
        
        rol.addEventListener('change', ()=>{
            const id = rol.value;
            
            fetch(`{{ url('tool/establishment/${id}') }}`)
            .then(response => response.json())
            .then(data => {
                console.log(data)
                local.innerHTML = '<option value="">Seleccione Establecimiento</option>';
                 // Agregar nuevas opciones
                data.forEach(data => {
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.textContent = data.name;
                    local.appendChild(option);
                });
            })

        });
    </script>

@endsection