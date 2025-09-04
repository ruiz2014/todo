<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\SectorRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $sectors = Sector::paginate();

        return view('sector.index', compact('sectors'))
            ->with('i', ($request->input('page', 1) - 1) * $sectors->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $sector = new Sector();

        return view('sector.create', compact('sector'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectorRequest $request): RedirectResponse
    {
        try{
            Sector::create($request->validated());

            return Redirect::route('sectors.index')->with('success', 'Sector created successfully.');

        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $sector = Sector::find($id);

        return view('sector.show', compact('sector'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $sector = Sector::find($id);

        return view('sector.edit', compact('sector'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectorRequest $request, Sector $sector): RedirectResponse
    {
        try{
            $sector->update($request->validated());

            return Redirect::route('sectors.index')->with('success', 'Sector updated successfully');

        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        }  
    }

    public function destroy($id): RedirectResponse
    {
        Sector::find($id)->delete();

        return Redirect::route('sectors.index')
            ->with('success', 'Sector deleted successfully');
    }
}
