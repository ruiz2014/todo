@extends('layouts.app')

@section('template_title')
    Quotes
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Quotes') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('quotes.create') }}" class="btn btn-outline-dark btn-sm float-right"  data-placement="left">
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
                        <form class="d-flex">
                            <div class="input-group">
                                <input name="search" class="form-control form-control-sm" value="{{ $text }}" type="search" placeholder="Buscar" aria-label="Search">
                                <button class="btn btn-outline-dark px-4" type="submit">
                                    <ion-icon name="search"></ion-icon>
                                </button>
                            </div>
                        </form>
                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th >Cliente</th>
                                        <th >Identificador</th>
                                        <th >Moneda</th>
                                        <th >Total</th>
                                        <th >Codigo</th>
                                        <th >Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotes as $quote)
                                        <tr>
                                            <td >{{ $quote->name }}</td>
                                            <td >{{ $quote->identifier }}</td>
                                            <td >Soles</td>
                                            <td >{{ $quote->total }}</td>
                                            <td >{{ $quote->document_code }}</td>
                                            <td >{{ $quote->status }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary xs-margin" href="{{ route('quotes.generated', $quote->document_code) }}"><ion-icon name="eye"></ion-icon></a>
                                                <a class="btn btn-sm btn-success xs-margin" href="{{ route('quotes.edit', $quote->document_code) }}" ><ion-icon name="create"></ion-icon></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $quotes->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
