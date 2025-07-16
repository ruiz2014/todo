@extends('layouts.app')

@section('template_title')
    Companies
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Companies') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('companies.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Name</th>
									<th >Company Name</th>
									<th >Document</th>
									<th >Address</th>
									<th >Ubigeo</th>
									<th >Sector Id</th>
									<th >Number Employees</th>
									<th >Number Subsidiary</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($companies as $company)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $company->name }}</td>
										<td >{{ $company->company_name }}</td>
										<td >{{ $company->document }}</td>
										<td >{{ $company->address }}</td>
										<td >{{ $company->ubigeo }}</td>
										<td >{{ $company->sector_id }}</td>
										<td >{{ $company->number_employees }}</td>
										<td >{{ $company->number_subsidiary }}</td>

                                            <td>
                                                <form action="{{ route('companies.destroy', $company->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('companies.show', $company->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('companies.edit', $company->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $companies->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
