<?php

namespace App\Http\Controllers;

use App\Models\Admin\Role;
use App\Models\Admin\Staff\Establishment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Mostrar si es super usuario 
        
        $roles = Role::where('company_id', request()->session()->get('company_id'))->paginate();
        // dd(request()->session()->get('company_id'), $roles, Role::class);
        // 
        return view('role.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * $roles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $role = new Role();
        $establishments = Establishment::where('company_id', request()->session()->get('company_id'))->get();

        return view('role.create', compact('role', 'establishments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request): RedirectResponse
    {
        // dd($request->validated(), $request->session()->get('company_id'));
        try{
            Role::create($request->validated() +['company_id'=> $request->session()->get('company_id')]);

            return Redirect::route('roles.index')->with('success', 'Role created successfully.');
            
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
        $role = Role::find($id);

        return view('role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $role = Role::find($id);
        $establishments = Establishment::where('company_id', request()->session()->get('company_id'))->get();

        return view('role.edit', compact('role', 'establishments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        try{
            $role->update($request->validated());

            return Redirect::route('roles.index')->with('success', 'Role updated successfully');

        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        }  
    }

    public function destroy($id): RedirectResponse
    {
        Role::find($id)->delete();

        return Redirect::route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
