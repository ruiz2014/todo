<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin\Local;

class HomeController extends Controller
{
    public function index(Request $request){
        $local = Local::select('local_name')->where('id', $request->session()->get('local_id'))->first();
        return view('admin.home.index', compact('local'));
    }
}
