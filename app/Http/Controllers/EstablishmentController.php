<?php

namespace App\Http\Controllers;

use App\Models\Admin\Staff\Establishment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EstablishmentRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EstablishmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $establishments = Establishment::where('company_id', request()->session()->get('company_id'))->paginate();

        return view('establishment.index', compact('establishments'))
            ->with('i', ($request->input('page', 1) - 1) * $establishments->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $establishment = new Establishment();

        return view('establishment.create', compact('establishment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EstablishmentRequest $request): RedirectResponse
    {
        Establishment::create($request->validated());

        return Redirect::route('establishments.index')
            ->with('success', 'Establishment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $establishment = Establishment::find($id);

        return view('establishment.show', compact('establishment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $establishment = Establishment::find($id);

        return view('establishment.edit', compact('establishment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EstablishmentRequest $request, Establishment $establishment): RedirectResponse
    {
        $establishment->update($request->validated());

        return Redirect::route('establishments.index')
            ->with('success', 'Establishment updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Establishment::find($id)->delete();

        return Redirect::route('establishments.index')
            ->with('success', 'Establishment deleted successfully');
    }
}
