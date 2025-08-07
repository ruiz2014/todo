<?php

namespace App\Http\Controllers\Biller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Biller\TempSale;
use App\Models\Biller\Attention;
use App\Models\Admin\Product;

class AttentionController extends Controller
{
    public function index(Request $request, $type){
        $types = Attention::where('sunat_code', $type)->get();

        return view('biller.voucher.index', compact('types'));
    }
}

