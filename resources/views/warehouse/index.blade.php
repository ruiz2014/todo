@extends('layouts.app')

@section('template_title')
    Warehouses
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Warehouses') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('warehouses.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
									<th >User Id</th>
									<th >Warehouse Name</th>
									<th >Phone</th>
									<th >Address</th>
									<th >Company Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warehouses as $warehouse)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $warehouse->user_id }}</td>
										<td >{{ $warehouse->warehouse_name }}</td>
										<td >{{ $warehouse->phone }}</td>
										<td >{{ $warehouse->address }}</td>
										<td >{{ $warehouse->company_id }}</td>

                                            <td>
                                                <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST">
                                                   
                                                    <a class="btn btn-sm btn-primary " href="{{ route('whp.show', $warehouse->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('warehouses.edit', $warehouse->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $warehouses->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
