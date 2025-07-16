<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\BelongLocal;
use Session;

class AdminController extends Controller
{
    public function index(Request $req){
        // dd("hola", $req);
        // Config::set('joder.supportEmail', "andate a la mierda");
    $sesiones = Session::get('user_id');
    $rol = Session::get('role');

    $local = BelongLocal::where('user_id', $sesiones)->first();


    dd($local, $rol, $req->user(), Auth::user(), $req->session()->get('company_id'), $sesiones, $req->session()->get('local_id'), $req->session()->get('workplace'));
    }
}