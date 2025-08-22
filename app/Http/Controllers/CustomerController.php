<?php

namespace App\Http\Controllers;

use App\Models\Admin\Staff\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Helpers\CompanyHelper;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $text = $request->search;
        $select = ['id', 'name', 'document', 'phone', 'address', 'email'];
        $where = ['local_id'=> ['=', request()->session()->get('local_id')]];
        $orWhere = ['name'=>['like', '%'.$text.'%'], 'document'=>['like', '%'.$text.'%'], 'phone'=>['like', '%'.$text.'%'], 'address'=>['like', '%'.$text.'%'], 'email'=>['like', '%'.$text.'%']];
        $join = [];

        $query  = Customer::select($select);

        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $customers = $result->paginate();

        return view('customer.index', compact('customers', 'text'))
            ->with('i', ($request->input('page', 1) - 1) * $customers->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $customer = new Customer();

        return view('customer.create', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request): RedirectResponse
    {
        Customer::create($request->validated() + ['company_id' => $request->session()->get('company_id'), 'user_id' => $request->session()->get('user_id'), 'local_id' => $request->session()->get('local_id')]);

        return Redirect::route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $customer = Customer::find($id);

        return view('customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $customer = Customer::find($id);

        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        return Redirect::route('customers.index')
            ->with('success', 'Customer updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Customer::find($id)->delete();

        return Redirect::route('customers.index')
            ->with('success', 'Customer deleted successfully');
    }
}
