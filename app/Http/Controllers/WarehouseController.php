<?php

namespace App\Http\Controllers;

use App\Models\Admin\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\WarehouseRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $warehouses = Warehouse::where('company_id', request()->session()->get('company_id'))->paginate();

        return view('warehouse.index', compact('warehouses'))
            ->with('i', ($request->input('page', 1) - 1) * $warehouses->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $warehouse = new Warehouse();

        return view('warehouse.create', compact('warehouse'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WarehouseRequest $request): RedirectResponse
    {
        Warehouse::create($request->validated());

        return Redirect::route('warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $warehouse = Warehouse::find($id);

        return view('warehouse.show', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $warehouse = Warehouse::find($id);

        return view('warehouse.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WarehouseRequest $request, Warehouse $warehouse): RedirectResponse
    {
        $warehouse->update($request->validated());

        return Redirect::route('warehouses.index')
            ->with('success', 'Warehouse updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Warehouse::find($id)->delete();

        return Redirect::route('warehouses.index')
            ->with('success', 'Warehouse deleted successfully');
    }
}
