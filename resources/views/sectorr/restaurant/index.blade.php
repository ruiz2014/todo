@extends('layouts.app')

@section('template_title')
    Restaurant
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" /> -->
@endpush

@section('content')

        @if ($message = Session::get('success'))
            <div class="alert alert-success m-4" role="alert">
                <p>{{ $message }}</p>
            </div>
        @endif

        @if ($message = Session::get('danger'))
            <div class="alert alert-danger m-4" role="alert">
                <p>{{ $message }}</p>
            </div>
        @endif


    <ul class="nav nav-tabs wrapper-tabs" id="myTab" role="tablist" style="">
        @foreach($rooms as $room)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $room?->id == 1 ? 'active':'' }}" id="{{ preg_replace('/\s+/', '', $room?->name)}}-tab" data-bs-toggle="tab" data-bs-target="#{{ preg_replace('/\s+/', '', $room?->name)}}" type="button" role="tab" aria-controls="{{ preg_replace('/\s+/', '', $room?->name)}}" aria-selected="true">{{ $room?->name }}</button>
        </li>
        @endforeach
    </ul>
    <div class="tab-content wrapper-tabs" id="myTabContent" style="">
        @foreach($rooms as $room)
        <div class="tab-pane fade {{ $room?->id == 1 ? 'show active':'' }}" id="{{ preg_replace('/\s+/', '', $room?->name)}}" role="tabpanel" aria-labelledby="{{ preg_replace('/\s+/', '', $room?->name)}}-tab">
            <h4 class="text-capitalize text-center mb-3">{{ $room->name }}</h4>
            <div class="mierda"> 
            @foreach($tables as $table) 
                @if($room?->id == $table?->room_id)
                <div class="col-3 col-lg-2 col-xl-2 mesa1 p-2 p-sm-3">
                    <span class="table-tag">{{ $table?->identifier }}</span>
                    <div class="wrapper-mesa p-2 p-md-3 shadow btnModal" id="{{ $table?->id }}">
                        <img src="img/table.png" alt="">
                    </div>
                </div> 
                @endif
            @endforeach   
            </div>     
        </div>
        @endforeach
    </div>

    <div class="container">
        <div class="row">

        </div>


    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    <script src="https://unpkg.com/ionicons@latest/dist/ionicons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endpush

<!-- </body>
</html> -->