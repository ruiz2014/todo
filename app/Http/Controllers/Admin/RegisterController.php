<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class RegisterController extends Controller
{
    public function create()
    {
        return view('admin.register.register');
    }

    public function store(Request $request)
    {
        /* 
        Validation
        */
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        /*
        Database Insert
        */
        // dd($request);

        $company_id = 1;

        if(session()->has('company_id')) {
            $company_id = request()->session()->get('company_id');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $company_id,
            'password2' => Hash::make('@secret2024.'),
        ]);

        return redirect()->route('users.index')
                ->with('success', 'User created successfully.'); 
    }

    public function editPassword($id){
        
        $user = User::find($id);
        // dd($user);
        return view('admin.register.updatePassword', compact('user'));
    }

    public function updatePassword(Request $request, $id){
        
        $user = User::find($id);
        $current_pass = $request->current_password;
        if($user){
            if(Hash::check($current_pass, $user->password)){
                
                // $request->validate([
                //     'password' => 'required|confirmed|min:8',
                // ]);

                $user->update($request->validate([
                    'password' => 'required|confirmed|min:8'
                ]));
                return Redirect::route('users.index')->with('success', 'Se actuslizo el password');

                // return redirect('admin')->with('status', 'You are loggead');
            }
            return back()->with('status', 'El password incorrecto');
        }
        return back()->with('status', 'No se encontro usuario');
    }
}