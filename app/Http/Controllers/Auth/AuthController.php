<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;             //ESTO ES PARA LA AUTENTIFICACION
use Illuminate\Validation\ValidationException;   //ESTO PARA EL TROW VALIDATIONERROR
use Illuminate\Routing\Redirector;

class AuthController extends Controller
{
    public function registerUser(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email', 'max:250'],
            'password' => ['required', "min:8", 'confirmed']
        ]);

        User::create([
            'name' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();

        // Ruta donde queremos que se redirija el usuario registrado
        return redirect()->route('/');
    }
}

