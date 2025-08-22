<?php

namespace App\Http\Controllers;

use App\Models\Admin\Product;
use App\Models\Admin\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Helpers\CompanyHelper;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $text = $request->search;
        $select = ['products.id', 'products.name', 'products.description', 'products.price', 'products.category_id', 'products.stock', 'products.minimo', 'c.category_name'];
        $where = ['products.company_id'=> ['=', request()->session()->get('company_id')]];
        $orWhere = ['products.name'=>['like', '%'.$text.'%'], 'products.description'=>['like', '%'.$text.'%'], 'products.price'=>['like', '%'.$text.'%'], 'products.stock'=>['like', '%'.$text.'%'], 'products.minimo'=>['like', '%'.$text.'%'], 'c.category_name'=>['like', '%'.$text.'%']];
        $join = ['categories as c' => ['products.category_id', '=', 'c.id']];

        $query  = Product::select($select);

        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $products = $result->paginate(2);

        // $products = Product::paginate();

        return view('product.index', compact('products', 'text'))
            ->with('i', ($request->input('page', 1) - 1) * $products->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $product = new Product();
        $categories = Category::pluck('category_name', 'id');

        return view('product.create', compact('product', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $id_user = $request->session()->get('user_id');
        $id_company = $request->session()->get('company_id');
        // dd($id_user,  $id_company);
        Product::create($request->validated() + ['user_id' => $id_user , 'company_id'=>$id_company]);

        return Redirect::route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $product = Product::find($id);

        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $product = Product::find($id);
        $categories = Category::pluck('category_name', 'id');

        return view('product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return Redirect::route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Product::find($id)->delete();

        return Redirect::route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}
