<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin\Role;
use App\Models\Admin\Local;
use App\Models\Admin\BelongLocal;
use App\Models\Admin\Staff\Establishment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $users = User::paginate();

        return view('user.index', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = new User();

        return view('user.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return Redirect::route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        // $user = User::find($id);

        // return view('user.edit', compact('user'));
        $user = User::find($id);
        
        $establishments = Establishment::where('company_id', 1)->get();
        $locals = Local::all();
        
        $workplace = BelongLocal::where('user_id', $user->id)->first(['establishment_id', 'local_id']);
        $roles = isset($workplace->establishment_id) ? Role::where('establishment_id', $workplace->establishment_id)->get() : Role::all()->skip(1);
        // $roles = isset($workplace->local_id) ? Role::where('establishment_id', $workplace->establishment_id)->get() : Role::all()->skip(1);
        
        return view('user.edit', compact('user', 'roles', 'locals', 'establishments', 'workplace'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        // dd($request, $request->validated());
        $user->update($request->validated());

        if(is_numeric(request('local'))){
            
            BelongLocal::updateOrCreate(['user_id' =>  $user->id], ['establishment_id'=>request('establishment'), 'local_id'=>request('local')]);
        }

        return Redirect::route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();

        return Redirect::route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
