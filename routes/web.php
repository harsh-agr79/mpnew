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

    //CHALAN PAGES
    Route::get('chalan', [ChalanController::class, 'chalan']);
    Route::get('chalandetail/{id}', [ChalanController::class, 'chalandetail']);

    //Analytics Pages
    Route::get('mainanalytics', [AnalyticsController::class, 'mainanalytics']);
    Route::get('sortanalytics', [AnalyticsController::class, 'sortanalytics']);
    Route::get('detailedreport', [AnalyticsController::class, 'detailedreport']);
    Route::get('statement', [AnalyticsController::class, 'statement']);

    //STAFF PAGES AND CRUD(Not allowed to staff)
    Route::get('/staff', [AdminController::class, 'staff']);
    Route::get('/addstaff', [AdminController::class, 'addstaff']);
    Route::get('/addstaff/{id}', [AdminController::class, 'addstaff']);
    Route::post('/addstaffprocess', [AdminController::class, 'addstaff_process'])->name('addstaffprocess');
    Route::get('/deletestaff', [AdminController::class, 'deletestaff']);




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
});

Route::group(['middleware'=>'CustomerAuth'], function() {
    Route::get('/home', [LoginController::class, 'home']);
});

Route::group(['middleware'=>'MarketerAuth'], function() {
    Route::get('marketer/home', [LoginController::class, 'marketerhome']);
});
