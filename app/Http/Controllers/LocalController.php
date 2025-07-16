<?php

namespace App\Http\Controllers;

use App\Models\Admin\Local;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\LocalRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LocalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $locals = Local::paginate();

        return view('local.index', compact('locals'))
            ->with('i', ($request->input('page', 1) - 1) * $locals->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $local = new Local();

        return view('local.create', compact('local'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocalRequest $request): RedirectResponse
    {
        Local::create($request->validated() + ['user_id'=>$request->session()->get('user_id'), 'company_id'=>$request->session()->get('company_id')]);

        return Redirect::route('locals.index')
            ->with('success', 'Local created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $local = Local::find($id);

        return view('local.show', compact('local'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $local = Local::find($id);

        return view('local.edit', compact('local'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocalRequest $request, Local $local): RedirectResponse
    {
        $local->update($request->validated());

        return Redirect::route('locals.index')
            ->with('success', 'Local updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Local::find($id)->delete();

        return Redirect::route('locals.index')
            ->with('success', 'Local deleted successfully');
    }
}
