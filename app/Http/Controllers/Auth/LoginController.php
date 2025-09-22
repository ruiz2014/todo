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
use App\Models\Admin\Staff\Subscription;

class LoginController extends Controller
{
    public function login(LoginRequest $req){
        // dd("llego a loggin");
        $message = 'Usuario no registrado o no tiene un rol asignado...';
        $email = $req->email;
        $pass = $req->password;

        $user =  User::where('email', $email)->first();
        
        if($user){
            // dd(Auth::user()->rol);
            $rol = $user->id == 1 ? 1 : ($user->rol == 2 ? $user->rol : null);
            // $rol = $user->id == 1 ? 
            // dd($rol);
            $local = 0;
            $workplace = 0;
            $company = $user->company_id;
             
            if(isset($user->rol) && $user->rol > 2){
                /*****************ARREGLAR ESTA PARTE***************** */
                $workplace = BelongLocal::where('user_id', $user->id)->first(['establishment_id', 'local_id']);
                // dd($workplace);
                if(!$workplace){
                    return redirect()->route('login')->with('danger', 'Este usuario no esta en ningun local'); 
                }
                $local = $workplace->local_id;
                $workplace = $workplace->establishment_id;
                $company = $user->company_id; 
                $rol = $user->rol;
            }

            if(Hash::check($pass, $user->password2)){
                Auth::login($user);
                $req->session()->regenerate();
                // Session::put('local_id', $local); 

                session(['company_id'=>$company, 'local_id' => $local, 'workplace' => $workplace, 'user_id'=>Auth::user()->id, 'role'=>$rol]);
                Subscription::where('user_id', $req->session()->get('user_id'))->update(['status' => 1 ]);
                switch ($rol) {
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

            if(Auth::attempt(['email' => $email, 'password' => $pass])){
                $req->session()->regenerate();

                session(['company_id'=>$company, 'local_id' => $local, 'workplace' => $workplace, 'user_id'=>Auth::user()->id, 'role'=>$rol]); 
                Subscription::where('user_id', $req->session()->get('user_id'))->update(['status' => 1 ]);
                switch ($rol) {
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
            
            $message = 'Error en el Usuario o en la contraseña...';
        }
        
        return redirect('/')->with('danger', $message );
    }

    public function logout(Request $req, Redirector $redirect){        
        if (Auth::check()) {
            Subscription::where('user_id', $req->session()->get('user_id'))->update(['status' => 0 ]);
            Auth::logout();
            $req->session()->invalidate();
            $req->session()->regenerateToken();
            return $redirect->to('/');
        } else {
            session()->flush();
            return $redirect->to('/');
        }
        
    }
}
