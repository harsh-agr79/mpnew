<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FixController;
use App\Http\Controllers\ChalanController;
use App\Http\Controllers\OrderAdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SubcategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[LoginController::class, 'login']);
Route::post('/auth', [LoginController::class, 'auth'])->name('auth');

   //LOGOUT FUNCTION
Route::get('/logout', function(){
    session()->flush();
    session()->flash('error','Logged Out');
    return redirect('/');
});

Route::group(['middleware'=>'AdminAuth'], function(){
    //PAGES
    Route::get('/dashboard', [LoginController::class, 'dashboard']);

    //DETAIL PAGES permission for staff required
    Route::get('/detail/{id}', [OrderAdminController::class, 'details']);
    Route::get('/appdetail/{id}', [OrderAdminController::class, 'appdetails']);
    Route::post('/detailupdate', [OrderAdminController::class, 'detailupdate'])->name('detailupdate');
    Route::post('seenupdate', [OrderAdminController::class, 'seenupdate']);

    //ORDER VIEW PAGES
    Route::get('orders', [OrderAdminController::class, 'orders']);
    Route::get('approvedorders', [OrderAdminController::class, 'approvedorders']);
    Route::get('pendingorders', [OrderAdminController::class, 'pendingorders']);
    Route::get('rejectedorders', [OrderAdminController::class, 'rejectedorders']);
    Route::get('deliveredorders', [OrderAdminController::class, 'deliveredorders']);

    //ORDERS CRUD
    Route::get('createorder', [OrderAdminController::class, 'createorder']);

    //CHALAN PAGES
    Route::get('chalan', [ChalanController::class, 'chalan']);
    Route::get('chalandetail/{id}', [ChalanController::class, 'chalandetail']);

    //Analytics Pages
    Route::get('mainanalytics', [AnalyticsController::class, 'mainanalytics']);
    Route::get('sortanalytics', [AnalyticsController::class, 'sortanalytics']);
    Route::get('detailedreport', [AnalyticsController::class, 'detailedreport']);

    //Statement Pages
    Route::get('statement', [AnalyticsController::class, 'statement']);
    Route::get('refererstatement', [AnalyticsController::class, 'refstatement']);
    Route::get('balancesheet/{id}', [AnalyticsController::class, 'balancesheet']);

    //Customers CRUD
    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('addcustomer', [CustomerController::class, 'addcustomer']);
    Route::get('editcustomer/{id}', [CustomerController::class, 'addcustomer']);
    // Route::get('deletecustomer/{id}', [CustomerController::class, 'deletecustomer']);
    Route::post('addcus', [CustomerController::class, 'addcustomer_process'])->name('addcustomer');

    //Payments CRUD
    Route::get('payments', [PaymentController::class, 'index']);
    Route::get('addpayment', [PaymentController::class, 'addpay']);
    Route::get('editpayment/{id}', [PaymentController::class, 'addpay']);
    Route::post('addpay', [PaymentController::class, 'addpay_process'])->name('addpay');
    // Route::get('deletepayment/{id}',[PaymentController::class, 'deletepay']);

     //Payments CRUD
     Route::get('expenses', [ExpenseController::class, 'index']);
     Route::get('addexpense', [ExpenseController::class, 'addexp']);
     Route::get('editexpense/{id}', [ExpenseController::class, 'addexp']);
     Route::post('addexp', [ExpenseController::class, 'addexp_process'])->name('addexp');
    //  Route::get('deleteexpense/{id}',[ExpenseController::class, 'deleteexp']);

    //SUBCATEGORY CRUD
    Route::get('/subcategory', [SubcategoryController::class, 'subcat']);
    Route::get('/addsubcategory', [SubcategoryController::class, 'addsubcat']);
    Route::get('/addsubcategory/{id}', [SubcategoryController::class, 'addsubcat']);
    Route::get('/deletesubcategory/{id}', [SubcategoryController::class, 'delsubcat']);
    Route::post('/addsubcategory', [SubcategoryController::class, 'addsubcat_process'])->name('addsub');

    //PRODUCTS CRUD
    Route::get('products', [ProductController::class, 'index']);
    Route::get('addproduct', [ProductController::class, 'addproduct']);
    Route::get('editproduct/{id}', [ProductController::class, 'addproduct']);
    Route::post('addprod', [ProductController::class, 'addprod_process'])->name('addprod');
    Route::get('deleteprod/{id}', [ProductController::class, 'deleteprod']);
    Route::post('arrangeprod', [ProductController::class, 'arrangeprod'])->name('arrange.prod');


    //STAFF PAGES AND CRUD(Not allowed to staff)
    Route::get('/staff', [AdminController::class, 'staff']);
    Route::get('/addstaff', [AdminController::class, 'addstaff']);
    Route::get('/addstaff/{id}', [AdminController::class, 'addstaff']);
    Route::post('/addstaffprocess', [AdminController::class, 'addstaff_process'])->name('addstaffprocess');
    Route::get('/deletestaff', [AdminController::class, 'deletestaff']);


    //FRONT SETTINGS
    Route::get('frontsettings', [FrontController::class, 'index']);
    Route::post('frontimg', [FrontController::class, 'addimg'])->name('addimg');
    Route::get('delete/frontimg/{id}/{id2}', [FrontController::class, 'deleteimg']);


    //ADMIN SETTINGS
    Route::get('/admin/changemode', [AdminController::class, 'changemode']);


    //FOR SERVER SIDE BULK UPDATE
    Route::get('update', [FixController::class, 'update']);


    //FOR AJAX UPDATES AND GETS

        //update
    Route::post('updatecln', [ChalanController::class, 'updatechalan']);
    Route::post('updatedeliver', [OrderAdminController::class, 'updatedeliver']);

        //get
    Route::get('findcustomer', [CustomerController::class, 'getcustomer']);
    Route::get('finditem', [ProductController::class, 'getproduct']);
    Route::get('/getref', [AnalyticsController::class, 'getref']);
    Route::get('/getsubcat/{id}', [SubcategoryController::class, 'getsubcat']);
});

Route::group(['middleware'=>'CustomerAuth'], function() {
    Route::get('/home', [LoginController::class, 'home']);
});

Route::group(['middleware'=>'MarketerAuth'], function() {
    Route::get('marketer/home', [LoginController::class, 'marketerhome']);
});
