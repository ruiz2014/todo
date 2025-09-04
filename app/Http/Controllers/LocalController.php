<?php

namespace App\Http\Controllers;

use App\Models\Admin\Local;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\LocalRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Helpers\CompanyHelper;

class LocalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $text = $request->search;
        $select = ['id', 'local_name', 'phone', 'address', 'company_id'];
        $where = ['company_id'=> ['=', request()->session()->get('company_id')]];
        $orWhere = ['local_name'=>['like', '%'.$text.'%'], 'phone'=>['like', '%'.$text.'%'], 'address'=>['like', '%'.$text.'%']];
        $join = [];

        $query  = Local::select($select);

        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $locals = $result->paginate(1);

        // $locals = Local::where('company_id', request()->session()->get('company_id'))->paginate();
        return view('local.index', compact('locals', 'text'))
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
        try{
            Local::create($request->validated() + ['user_id'=>$request->session()->get('user_id'), 'company_id'=>$request->session()->get('company_id')]);

            return Redirect::route('locals.index')->with('success', 'Local created successfully.');
            
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
        try{
            $local->update($request->validated());

            return Redirect::route('locals.index')->with('success', 'Local updated successfully');

        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        } 
    }

    public function destroy($id): RedirectResponse
    {
        Local::find($id)->delete();

        return Redirect::route('locals.index')
            ->with('success', 'Local deleted successfully');
    }
}
