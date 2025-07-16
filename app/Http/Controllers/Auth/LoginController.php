<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;             //ESTO ES PARA LA AUTENTIFICACION
use Illuminate\Validation\ValidationException;   //ESTO PARA EL TROW VALIDATIONERROR
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Redirector;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\Admin\BelongLocal;

class LoginController extends Controller
{
    public function login(LoginRequest $req){
        // dd("llego a loggin");
        $email = $req->email;
        $pass = $req->password;

        $user =  User::where('email', $email)->first();
        
        if(isset($user->rol)){
            $local = 0;
            $workplace = 0;
            $company = $user->company_id;
             
            if($user->rol > 2){
                /*****************ARREGLAR ESTA PARTE***************** */
                $workplace = BelongLocal::where('user_id', $user->id)->first(['establishment_id', 'local_id']);
                // dd($workplace);
                if(!$workplace){
                    return redirect()->route('login')->with('danger', 'Este usuario no esta en ningun local'); 
                }
                $local = $workplace->local_id;
                $workplace = $workplace->establishment_id;
                $company = $user->company_id; 
            }


            if(Hash::check($pass, $user->password2)){
                Auth::login($user);
                $req->session()->regenerate();
                // Session::put('local_id', $local); 

                session(['company_id'=>$company, 'local_id' => $local, 'workplace' => $workplace, 'user_id'=>Auth::user()->id, 'role'=>Auth::user()->rol ]);
                switch (Auth::user()->rol) {
                    case 1:
                        return redirect()->route('admin'); 
                    // case 2:
                    //     return redirect()->route('home'); //admin
                    // case 3:
                    //     return redirect()->route('pay.index'); //cajero
                    // case 4:
                    //     return redirect()->route('kitchen.index'); //cocinero
                    case 5:
                        return redirect()->route('login');
                    default:
                        return redirect()->route('home');
                        
                }
                // return redirect('salon')->with('status', 'You are loggead');
            }
        }
        
        if(Auth::attempt(['email' => $email, 'password' => $pass])){
            $req->session()->regenerate();

            session(['company_id'=>$company, 'local_id' => $local, 'workplace' => $workplace, 'user_id'=>Auth::user()->id, 'role'=>Auth::user()->rol]); 
            switch (Auth::user()->rol) {
                case 1:    
                    return redirect()->route('admin');
                case 5:
                    return redirect()->route('warehouses.index');
                default:
                    return redirect()->route('home');
            }
            // return redirect()->intended('salon')->with('status', 'You are loggead');
            // return redirect('salon')->with('status', 'You are loggead');

            // redirect()->intended()->with();
            // return redirect()->route('warehouses.index');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed')
        ]);
        
        return redirect('login');
    }

    public function logout(Request $req, Redirector $redirect){
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return $redirect->to('/');
    }
}
