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
        try{
            Establishment::create($request->validated());

            return Redirect::route('establishments.index')->with('success', 'Establishment created successfully.');

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
        try{
            $establishment->update($request->validated());

            return Redirect::route('establishments.index')->with('success', 'Establishment updated successfully');

        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        }
    }

    public function destroy($id): RedirectResponse
    {
        Establishment::find($id)->delete();

        return Redirect::route('establishments.index')
            ->with('success', 'Establishment deleted successfully');
    }
}
