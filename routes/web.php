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
use App\Http\Controllers\Admin\BuyProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\WarehouseProductController;
use App\Http\Controllers\Admin\CashController;
use App\Http\Controllers\Admin\LocalProductController;
use App\Http\Controllers\Tool\CommonController;
use App\Http\Controllers\Biller\AttentionController;
use App\Http\Controllers\Biller\SumaryController;
use App\Http\Controllers\Biller\QuoteController;
use App\Http\Controllers\Biller\CreditController;
use App\Http\Controllers\Operation\OperationController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\PaymentMethodController;

use App\Http\Controllers\CustomerController;

use App\Http\Controllers\Sector\Shop\ShopController;
use App\Http\Controllers\Sector\Hotel\HotelController;
use App\Http\Controllers\Sector\Restaurant\RestaurantController;
use App\Http\Controllers\Operation\PdfController;

use App\Helpers\CompanyHelper;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])->name('auth.login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('prue/{type}', [QuoteController::class, 'mela']);

Route::get('quotes/generated/{order}', [QuoteController::class, 'generatedReceipt'])->name('quotes.generated');
Route::get('quotes/keep/{order}', [QuoteController::class, 'keep'])->name('quotes.keep');
Route::get('quotes/convert/{order}', [QuoteController::class, 'convert'])->name('quotes.convert');
Route::resource('quotes', QuoteController::class);

Route::get('cashes/seller/{user}', [CashController::class, 'getSeller']); //<--- AJAX
Route::get('cashes/display/{cash}', [CashController::class, 'getCash']);
Route::get('cashes/close/{id}', [CashController::class, 'close'])->name('cashes.close');
Route::post('cashes/io_cash', [CashController::class, 'ioCash'])->name('cashes.iocash');
Route::post('cashes/end_cash/{id}', [CashController::class, 'endCash'])->name('cashes.end');

Route::resource('cashes', CashController::class);
// Route::get('attention', [AttentionController::class, 'index'])->name('attention.index');
Route::post('credits/total', [CreditController::class, 'storeTotal'])->name('credits.total');
Route::resource('credits', CreditController::class);

Route::middleware(['auth', 'hasPermission:1,2,3'])->prefix('admin')->group(function () {

    Route::resource('warehouses', WarehouseController::class);
    Route::resource('locals', LocalController::class);

    Route::get('user/register', [RegisterController::class, 'create'])->name('register.create');
    Route::post('user/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('user/editPassword/{id}', [RegisterController::class, 'editPassword'])->name('register.edit');
    Route::post('user/updatePassword/{id}', [RegisterController::class, 'updatePassword'])->name('register.update');
    Route::patch('user/admin/{user}', [UserController::class, 'updateAdmin'])->name('users.updateAdmin');
    Route::resource('users', UserController::class);

    Route::resource('payment-methods', PaymentMethodController::class);

    Route::get('attentions/{type}', [AttentionController::class, 'index'])->name('attentions.index');

    Route::get('summary/create/{fecha?}', [SumaryController::class, 'create'])->name('summary.create');
    Route::get('summary', [SumaryController::class, 'index'])->name('summary.index');
    Route::get('summary/create/{fecha?}', [SumaryController::class, 'create'])->name('summary.create');
    Route::post('summary/search', [SumaryController::class, 'search'])->name('summary.search');
    Route::post('summary/accion', [SumaryController::class, 'summary'])->name('summary'); 
    Route::get('summary/show/{code}', [SumaryController::class, 'show'])->name('summary.show'); 

    Route::post('add_purchase', [BuyProductController::class, 'addOrder']);
    Route::post('delete_purchase', [BuyProductController::class, 'deleteOrder']);
    Route::post('modify_amount_purchase', [BuyProductController::class, 'modifyAmount']);
    Route::post('save_purchase', [BuyProductController::class, 'store'])->name('buy-products.store');
    Route::get('report', [BuyProductController::class, 'shopReport'])->name('shop.report');
    Route::get('buy-products', [BuyProductController::class, 'index'])->name('buy-products.index');
    Route::get('create_purchase', [BuyProductController::class, 'create'])->name('buy-products.create');
    Route::get('generado_purchase/{code}', [BuyProductController::class, 'generatedReceipt'])->name('buy-products.generated');
    // Route::resource('buy-products', BuyProductController::class); 

    Route::post('products/import', [ProductController::class, 'productImport'])->name('products.import');
    Route::resource('products', ProductController::class);

    Route::post('choose-location', [AdminController::class, 'chooseLocation'])->name('admin.chl');

});

Route::middleware(['auth', 'hasPermission:1,2,5'])->prefix('almacen')->group(function () {

    Route::get('warehouse_products/{id}', [WarehouseProductController::class, 'whProducts'])->name('whp.show');
    Route::post('warehouse_products', [WarehouseProductController::class, 'store'])->name('whp.store');
    Route::get('history_products/{id}', [WarehouseProductController::class, 'viewHistory'])->name('whp.view');
    Route::post('warehouse_stock', [WarehouseProductController::class, 'uploadStock'])->name('whp.upload');
    Route::post('warehouse_transfer', [WarehouseProductController::class, 'transferStock'])->name('whp.transfer');
    Route::get('temp', [WarehouseProductController::class, 'temp'])->name('whp.temp');
    Route::post('temp_action', [WarehouseProductController::class, 'tempAction'])->name('whp.tempAction');
    Route::get('warehouse/entry', [WarehouseProductController::class, 'newEntries'])->name('whp.entry');
    Route::get('warehouse/entry_action/{code}', [WarehouseProductController::class, 'entryAction'])->name('whp.entryAction');
    Route::post('warehouse/register/{code}', [WarehouseProductController::class, 'register'])->name('whp.register');
});


/**********************SUPER ADMIN ************** */  
Route::middleware(['auth', 'hasPermission:1'])->prefix('super_admin')->group(function () { 
   
    Route::get('admin', [AdminController::class, 'index'])->name('admin');

    Route::resource('companies', CompanyController::class);
    Route::resource('establishments', EstablishmentController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('sectors', SectorController::class);

    Route::post('choose-company', [AdminController::class, 'chooseCompany'])->name('admin.chc');

});


Route::group(['middleware' => 'auth'], function(){

    Route::get('panel', [HomeController::class, 'index'])->name('home');

    Route::resource('customers', CustomerController::class);
    Route::resource('categories', CategoryController::class);

    Route::post('local_products/import', [LocalProductController::class, 'localImport'])->name('lp.import');
    Route::get('local_products/formato', [LocalProductController::class, 'format'])->name('lp.format');
    Route::post('lp/register/{code}', [LocalProductController::class, 'register'])->name('lp.register');
    Route::get('lp/entry_action/{code}', [LocalProductController::class, 'entryAction'])->name('lp.entryAction');
    Route::get('local_products/entry', [LocalProductController::class, 'newEntries'])->name('lp.entry');
    Route::get('local_products', [LocalProductController::class, 'index'])->name('lp.index');

    Route::get('shop', [ShopController::class, 'index'])->name('shop.index');
    Route::post('add_order', [ShopController::class, 'addOrder']);
    Route::post('delete_order', [ShopController::class, 'deleteOrder']);
    Route::post('modify_amount', [ShopController::class, 'modifyAmount']);
    Route::post('save', [ShopController::class, 'store'])->name('shop.store');
    Route::get('report', [ShopController::class, 'shopReport'])->name('shop.report');

    Route::get('generado/{order}', [ShopController::class, 'generatedReceipt'])->name('shop.generated');

    Route::get('restaurant', [RestaurantController::class, 'index'])->name('j2');
    Route::get('hotel', [HotelController::class, 'index'])->name('j3');


    Route::get('report_date', [AttentionController::class, 'reportDate'])->name('report.date');
    Route::get('report_sales', [AttentionController::class, 'reportSale'])->name('report.sales');

    Route::get('tool/create_product', [CommonController::class, 'createProduct'])->name('tool.createProduct');
    Route::post('tool/store_product', [CommonController::class, 'storeProduct'])->name('tool.storeProduct');
    Route::post('tool/check', [CommonController::class, 'checkProduct'])->name('tool.checkProduct');
    Route::get('tool/search', [CommonController::class, 'searchCustomer']);
    Route::get('tool/role/{id}', [CommonController::class, 'getRole']);
    Route::get('tool/establishment/{id}', [CommonController::class, 'getEstablishment']);

    Route::get('descargarXml/{id}/{type}', function($id, $type){
        $result = CompanyHelper::downloadXml($id, $type);
        
        if($result){
        return response()->download(public_path()."/sunat_documents/$result"); 
        }

        return back()->with('info', 'No se encontro el documento a descargar ....');
    })->name('downloadXml');

    Route::get('descargarCdr/{id}/{type}', function($id, $type){
        $result = CompanyHelper::downloadCdr($id, $type);

        if($result){
        return response()->download(public_path()."/sunat_documents/$result"); 
        }

        return back()->with('info', 'No se encontro el documento a descargar ....');

    })->name('downloadCdr');
});

Route::post('add_order_quote', [OperationController::class, 'add']); 
Route::post('delete_order_2', [OperationController::class, 'delete']);
Route::post('modify_amount_2', [OperationController::class, 'modifyAmount']); 

Route::post('add_order_quote_edit', [OperationController::class, 'add_edit']); 
Route::post('modify_amount_edit', [OperationController::class, 'modifyAmount_edit']);
Route::post('delete_order_edit', [OperationController::class, 'delete_edit']);

// Route::post('save', [ShopController::class, 'store'])->name('shop.store');

Route::get('generate-pdf/{id}/{type}', [PDFController::class, 'generatePDF']);
Route::get('generate-pdf-ticket/{id}/{type}', [PDFController::class, 'generatePDF']);

Route::view('pichi', 'pru');

Route::post('mierda', [HomeController::class, 'mierda']);



// Route::get('salon', [DiningHallController::class, 'hall'])->name('hall');

Route::post('check', [RestaurantController::class, 'check']);
Route::post('add_order', [RestaurantController::class, 'addOrder']);
Route::post('modify_amount', [RestaurantController::class, 'modifyAmount']);
Route::post('delete_order', [RestaurantController::class, 'deleteOrder']);
// Route::post('add_note', [DiningHallController::class, 'addNote']);
Route::post('send_kitchen', [RestaurantController::class, 'sendToKitchen']);
// Route::post('qr_debt', [DiningHallController::class, 'qrDebt']);
// Route::get('see_debt/{code}/{type}', [DiningHallController::class, 'seeDebt']);
// // Route::get('pdf_debt/{code}', [DiningHallController::class, 'pdfDebt']);
// Route::post('finalize_order', [DiningHallController::class, 'finalizeOrder'])->name('finalizeOrder');


// Route::post('dish_ready', [KitchenController::class, 'dishReady']);
// Route::get('kitchen', [KitchenController::class, 'index']);