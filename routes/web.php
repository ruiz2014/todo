<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\WarehouseProductController;
use App\Http\Controllers\Admin\LocalProductController;
use App\Http\Controllers\Tool\CommonController;
use App\Http\Controllers\Biller\AttentionController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\PaymentMethodController;

use App\Http\Controllers\Sector\Shop\ShopController;
use App\Http\Controllers\Sector\Hotel\HotelController;
use App\Http\Controllers\Sector\Restaurant\RestaurantController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])->name('auth.login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('panel', [HomeController::class, 'index'])->name('home');

Route::get('attention', [AttentionController::class, 'index'])->name('attention.index');

Route::resource('warehouses', WarehouseController::class);
Route::resource('locals', TiendaocalController::class);

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);

Route::get('user/register', [RegisterController::class, 'create'])->name('register.create');
Route::post('user/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('user/editPassword/{id}', [RegisterController::class, 'editPassword'])->name('register.edit');
Route::post('user/updatePassword/{id}', [RegisterController::class, 'updatePassword'])->name('register.update');
Route::resource('users', UserController::class);

Route::get('warehouse_products/{id}', [WarehouseProductController::class, 'whProducts'])->name('whp.show');
Route::post('warehouse_products', [WarehouseProductController::class, 'store'])->name('whp.store');
Route::get('history_products/{id}', [WarehouseProductController::class, 'viewHistory'])->name('whp.view');

Route::post('warehouse_stock', [WarehouseProductController::class, 'uploadStock'])->name('whp.upload');
Route::post('warehouse_transfer', [WarehouseProductController::class, 'transferStock'])->name('whp.transfer');

Route::get('temp', [WarehouseProductController::class, 'temp'])->name('whp.temp');
Route::post('temp_action', [WarehouseProductController::class, 'tempAction'])->name('whp.tempAction');


Route::get('tool/create_product', [CommonController::class, 'createProduct'])->name('tool.createProduct');
Route::post('tool/store_product', [CommonController::class, 'storeProduct'])->name('tool.storeProduct');
Route::post('tool/check', [CommonController::class, 'checkProduct'])->name('tool.checkProduct');

Route::get('tool/search', [CommonController::class, 'searchCustomer']);
Route::get('tool/role/{id}', [CommonController::class, 'getRole']);
Route::get('tool/establishment/{id}', [CommonController::class, 'getEstablishment']);


Route::get('local_products', [LocalProductController::class, 'index'])->name('lp.index');


Route::get('shop', [ShopController::class, 'index'])->name('Tienda');
Route::post('add_order', [ShopController::class, 'addOrder']);
Route::post('delete_order', [ShopController::class, 'deleteOrder']);
Route::post('modify_amount', [ShopController::class, 'modifyAmount']);
Route::post('save', [ShopController::class, 'store'])->name('shop.store');
Route::get('report', [ShopController::class, 'shopReport'])->name('shop.report');

Route::get('generado/{order}', [ShopController::class, 'generatedReceipt'])->name('shop.generated');




Route::get('restaurant', [RestaurantController::class])->name('j2');
Route::get('hotel', [HotelController::class, 'index'])->name('j3');

Route::resource('payment-methods', PaymentMethodController::class);

/**********************SUPER ADMIN ************** */    
Route::get('admin', [AdminController::class, 'index'])->name('admin');
Route::resource('companies', CompanyController::class);
Route::resource('establishments', EstablishmentController::class);
Route::resource('roles', RoleController::class);
Route::resource('sectors', SectorController::class);