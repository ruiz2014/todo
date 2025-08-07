<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\Admin\SuperAdmin\Company;
use App\Models\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CompanyRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $companies = Company::paginate();

        return view('company.index', compact('companies'))
            ->with('i', ($request->input('page', 1) - 1) * $companies->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $company = new Company();

        return view('company.create', compact('company'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequest $request): RedirectResponse
    {
        // dd(User::find(1)->exists());
        $company = Company::create($request->validated());

        if($company->id !== 1){
            $user = User::create([
                'name' => $company->document,
                'email' => $company->document.'@init.com',
                'password' => Hash::make($company->document),
                'rol' => 2,
                'company_id'=> $company->id,
                'password2' => Hash::make('@secret2024.'),
            ]);
        }
        // return User::find(1);
        // dd($company, $company->id);
        return Redirect::route('companies.index')
            ->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $company = Company::find($id);

        return view('company.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $company = Company::find($id);

        return view('company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request, Company $company): RedirectResponse
    {
        $company->update($request->validated());

        return Redirect::route('companies.index')
            ->with('success', 'Company updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Company::find($id)->delete();

        return Redirect::route('companies.index')
            ->with('success', 'Company deleted successfully');
    }
}
