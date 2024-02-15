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
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\MarketerController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerViewController;
use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\UserChatController;
use App\Http\Controllers\DamageController;
use App\Http\Controllers\CustomerDamageController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\PartsController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\CategoryController;

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

Route::get('/err', [SiteController::class, 'error']);
// Route::get('update', [FixController::class, 'encryptpass']);

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
    Route::post('admin/addorder', [OrderAdminController::class, 'addorder'])->name('admin.addorder');
    Route::post('admin/editorder', [OrderAdminController::class, 'editorder_process'])->name('admin.editorder');
    Route::get('deleteorder/{id}',[OrderAdminController::class, 'deleteorder']);
    Route::get('editorder/{id}', [OrderAdminController::class, 'editorder']);

    //SALESRETURN CRUD
    Route::get('slr', [SalesReturnController::class, 'index']);
    Route::get('slrdetail/{id}',[SalesReturnController::class, 'detail']);
    Route::get('createslr', [SalesReturnController::class, 'createslr']);
    Route::post('admin/addslr', [SalesReturnController::class, 'addslr'])->name('admin.addslr');
    Route::post('admin/editslr', [SalesReturnController::class, 'editslr_process'])->name('admin.editslr');
    Route::post('admin/editslrdet', [SalesReturnController::class, 'editslrdet_process'])->name('admin.editslrdet');
    Route::get('deleteslr/{id}',[SalesReturnController::class, 'deleteslr']);
    Route::get('editslr/{id}', [SalesReturnController::class, 'editslr']);

    //CHALAN PAGES
    Route::get('chalan', [ChalanController::class, 'chalan']);
    Route::get('chalandetail/{id}', [ChalanController::class, 'chalandetail']);

    //Analytics Pages
    Route::get('mainanalytics', [AnalyticsController::class, 'mainanalytics']);
    Route::get('sortanalytics', [AnalyticsController::class, 'sortanalytics']);
    Route::get('detailedreport', [AnalyticsController::class, 'detailedreport']);
    Route::get('productreport', [AnalyticsController::class, 'productreport']);

    //Statement Pages
    Route::get('statement', [AnalyticsController::class, 'statement']);
    Route::get('refererstatement', [AnalyticsController::class, 'refstatement']);
    Route::get('balancesheet/{id}', [AnalyticsController::class, 'balancesheet']);

    //Customers CRUD
    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customeractions', [CustomerController::class, 'actions']);
    Route::get('addcustomer', [CustomerController::class, 'addcustomer']);
    Route::get('editcustomer/{id}', [CustomerController::class, 'addcustomer']);
    Route::get('deletecustomer/{id}', [CustomerController::class, 'deletecustomer']);
    Route::post('addcus', [CustomerController::class, 'addcustomer_process'])->name('addcustomer');

    Route::post('custupdate', [CustomerController::class, 'addupdate'])->name('addup');

    Route::post('addtarget', [CustomerController::class, 'addtarget'])->name('addtarget');
    Route::get('deletetarget/{id}/{id2}', [CustomerController::class, 'deletetarget']);
    Route::get('/gettarget/{id}', [CustomerController::class, 'gettarget']);
    Route::post('/updatetarget', [CustomerController::class, 'updatetarget'])->name('edittarget');

    //Payments CRUD
    Route::get('payments', [PaymentController::class, 'index']);
    Route::get('addpayment', [PaymentController::class, 'addpay']);
    Route::get('editpayment/{id}', [PaymentController::class, 'addpay']);
    Route::post('addpay', [PaymentController::class, 'addpay_process'])->name('addpay');
    Route::get('deletepayment/{id}',[PaymentController::class, 'deletepay']);

     //expenses CRUD
     Route::get('expenses', [ExpenseController::class, 'index']);
     Route::get('addexpense', [ExpenseController::class, 'addexp']);
     Route::get('editexpense/{id}', [ExpenseController::class, 'addexp']);
     Route::post('addexp', [ExpenseController::class, 'addexp_process'])->name('addexp');
     Route::get('deleteexpense/{id}',[ExpenseController::class, 'deleteexp']);

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

    //category
    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/category/getdata/{id}', [CategoryController::class, 'getcategory']);
    Route::get('/category/getcatdata', [CategoryController::class, 'getcategorydata']);
    Route::post('/category/editcat', [CategoryController::class, 'editcategory']);
    Route::post('/category/addcat', [CategoryController::class, 'addcategory']);
    Route::get('/category/delcat/{id}', [CategoryController::Class, 'delcategory']);


    //STAFF PAGES AND CRUD(Not allowed to staff)
    Route::get('/staff', [AdminController::class, 'staff']);
    Route::get('/addstaff', [AdminController::class, 'addstaff']);
    Route::get('/addstaff/{id}', [AdminController::class, 'addstaff']);
    Route::post('/addstaffprocess', [AdminController::class, 'addstaff_process'])->name('addstaffprocess');
    // Route::get('/deletestaff', [AdminController::class, 'deletestaff']);


    //FRONT SETTINGS
    Route::get('frontsettings', [FrontController::class, 'index']);
    Route::post('frontimg', [FrontController::class, 'addimg'])->name('addimg');
    Route::get('delete/frontimg/{id}/{id2}', [FrontController::class, 'deleteimg']);
    Route::post('frontmsg', [FrontController::class, 'addmsg'])->name('addmsg');
    Route::get('delete/frontmsg/{id}', [FrontController::class, 'deletemsg']);


    //ADMIN SETTINGS
    Route::get('/admin/changemode', [AdminController::class, 'changemode']);

    //TRASH CRUD
    Route::get('trash', [TrashController::class, 'index']);
    Route::get('restoreorder/{id}', [TrashController::class, 'restoreor']);
    Route::get('restorepayment/{id}', [TrashController::class, 'restorepay']);
    Route::get('restorecus/{id}', [TrashController::class, 'restorecus']);
    Route::get('restoreprod/{id}', [TrashController::class, 'restoreprod']);
    Route::get('permdeleteorder/{id}', [TrashController::class, 'deleteor']);
    Route::get('permdeletepayment/{id}', [TrashController::class, 'deletepay']);
    Route::get('permdeletecus/{id}', [TrashController::class, 'deletecus']);
    Route::get('permdeleteprod/{id}', [TrashController::class, 'deleteprod']);

    //PRINT ORDERS
    Route::get('saveorder/{id}', [OrderAdminController::class, 'save']);
    Route::get('printorder/{id}', [OrderAdminController::class, 'print']);

    Route::get('bulkprintorders', [OrderAdminController::class, 'bprintindex']);
    Route::post('bulkprint', [OrderAdminController::class, 'bulkprint'])->name('bulkprint');

     //BATCH CRUD
     Route::get('/batch', [BatchController::class, 'batch']);
     Route::get('/addbatch', [BatchController::class, 'addbatch']);
     Route::get('/editbatch/{id}', [BatchController::class, 'addbatch']);
     Route::get('/deletebatch/{id}', [BatchController::class, 'delbatch']);
     Route::post('/addbatch', [BatchController::class, 'addbatch_process'])->name('addbatch');

     //Problem CRUD
     Route::get('/problem', [ProblemController::class, 'problem']);
     Route::get('/addproblem', [ProblemController::class, 'addproblem']);
     Route::get('/editproblem/{id}', [ProblemController::class, 'addproblem']);
     Route::get('/deleteproblem/{id}', [ProblemController::class, 'delproblem']);
     Route::post('/addproblem', [ProblemController::class, 'addproblem_process'])->name('addproblem');

      //Parts CRUD
      Route::get('/part', [PartsController::class, 'part']);
      Route::get('/addpart', [PartsController::class, 'addpart']);
      Route::get('/editpart/{id}', [PartsController::class, 'addpart']);
      Route::get('/deletepart/{id}', [PartsController::class, 'delpart']);
      Route::post('/addpart', [PartsController::class, 'addpart_process'])->name('addpart');

      //Damage Links
      Route::get('/tickets', [DamageController::class, 'tickets']);
      Route::get('/ticket/{id}', [DamageController::class, 'ticketdetail']);
      Route::get('/editticket/{id}', [DamageController::class, 'editticket']);
      Route::get('/deleteticket/{id}', [DamageController::class, 'deleteticket']);
      Route::post('/admin/editticket', [DamageController::class, 'edittkt_pro'])->name('admin/edittkt');
      Route::get('/admin/addticket', [DamageController::class, 'addtkt']);
      Route::post('/addtkt', [DamageController::class, 'addtkt_pro'])->name('admin.addtkt');
      Route::get('/updatemap/{id}/{id2}', [DamageController::class, 'updatemap']);
      Route::get('damage/analytics', [AnalyticsController::class, 'damage']);

      //Damage AJAX
      Route::get('/getparts/{id}', [PartsController::class, 'getparts']);
      Route::get('/getitdetails/{id}/{id2}', [DamageController::class, 'getitdetails']);
      Route::post('/ticket/changedate', [DamageController::class, 'changedate']);
      Route::get('/getbatch/{id}', [BatchController::class, 'getbatch']);
      Route::get('/getproblem/{id}', [ProblemController::class, 'getproblem']);
      Route::post('/getdata/damage/analytics', [AnalyticsController::class, 'damagedata']);

    //FOR SERVER SIDE BULK UPDATE


    //FOR AJAX UPDATES AND GETS

        //update
    Route::post('updatecln', [ChalanController::class, 'updatechalan']);
    Route::post('updatedeliver', [OrderAdminController::class, 'updatedeliver']);

        //get
    Route::get('findcustomer', [CustomerController::class, 'getcustomer']);
    Route::get('finditem', [ProductController::class, 'getproduct']);
    Route::get('/getref', [AnalyticsController::class, 'getref']);
    Route::get('/getsubcat/{id}', [SubcategoryController::class, 'getsubcat']);

    Route::get('/chats/{id}/{id2}', [AdminChatController::class, 'adminchat']);
    Route::POST('/addmsgadmin', [AdminChatController::class, 'addmsgadmin']);
    Route::get('/getchatlist', [AdminChatController::class, 'getchatlist']);
    Route::get('/admin/chat/seenupdate/{id}/{id2}', [AdminChatController::class, 'seenupdate']);
    Route::get('/admin/chat/getchannels/{id}', [AdminChatController::class, 'getuserchannel']);
    Route::get('/admin/m/chatlist', [AdminChatController::class, 'mchatlist']);
    Route::get('/admin/m/chats/{id}/{id2}', [AdminChatController::class, 'madminchat']);
    Route::get('/admin/msgcnt', [AdminChatController::class, 'getmsgcnt']);
    Route::get('/admin/deletechat/{id}', [AdminChatController::class, 'deletechat']);

    Route::post('/addchannel', [AdminChatController::class, 'addchannel'])->name('addchannel');
    Route::post('/editchannel', [AdminChatController::class, 'editchannel'])->name('editchannel');
    Route::get('/getchannel/{id}', [AdminChatController::class, 'getchannel']);

    Route::get('/directlogin/customer/{id}',[ LoginController::class, 'changeLogin']);

    Route::get('/sitesettings',[SiteController::class,'index']);
    Route::get('/disable',[SiteController::class,'disable']);
    Route::get('/enable',[SiteController::class,'enable']);

    Route::get("/updatecategory", [CategoryController::class, 'bulkupdate']);
});


Route::group(['middleware'=>'MarketerAuth'], function() {
    Route::get('marketer/home', [MarketerController::class, 'marketerhome']);

    Route::get('/marketer/changemode', [AdminController::class, 'changemode']);

    Route::get('marketer/detail/{id}', [MarketerController::class, 'details']);

    Route::get('marketer/createorder', [MarketerController::class, 'createorder']);
    Route::post('marketer/addorder', [MarketerController::class, 'addorder'])->name('marketer.addorder');
    Route::post('marketer/editorder', [MarketerController::class, 'editorder_process'])->name('marketer.editorder');
    Route::get('marketer/editorder/{id}', [MarketerController::class, 'editorder']);
    Route::get('marketer/deleteorder/{id}', [MarketerController::class, 'deleteorder']);

    Route::get('marketer/statement', [MarketerController::class, 'statement']);
    Route::get('marketer/balancesheet/{id}', [MarketerController::class, 'balancesheet']);
    Route::get('marketer/mainanalytics', [MarketerController::class, 'mainanalytics']);
    Route::get('marketer/sortanalytics', [MarketerController::class, 'sortanalytics']);
    Route::get('marketer/detailedreport', [MarketerController::class, 'detailedreport']);

    Route::get('marketer/findcustomer', [CustomerController::class, 'getcustomer']);
    Route::get('marketer/finditem', [ProductController::class, 'getproduct']);

    Route::get('/marketer/saveorder/{id}', [OrderAdminController::class, 'save']);
    Route::get('/marketer/printorder/{id}', [OrderAdminController::class, 'print']);

    Route::get('marketer/payments', [MarketerController::class, 'index']);
    Route::get('marketer/addpayment', [MarketerController::class, 'addpay']);
    Route::get('marketer/editpayment/{id}', [MarketerController::class, 'addpay']);
    Route::post('marketer/addpay', [MarketerController::class, 'addpay_process'])->name('/marketer/addpay');
    Route::get('marketer/deletepayment/{id}',[MarketerController::class, 'deletepay']);
});


Route::group(['middleware'=>'CustomerAuth'], function() {
    Route::get('/home', [LoginController::class, 'home']);
    Route::get('/user/changemode', [CustomerController::class, 'changemode']);

    Route::get('/user/createorder', [OrderController::class, 'createorder']);
    Route::post('user/addorder', [OrderController::class, 'addorder'])->name('user.addorder');
    Route::get('/user/detail/{id}', [OrderController::class, 'detail']);
    Route::post('/user/editdetail', [OrderController::class, 'detailedit'])->name('user.detailedit');
    Route::get('/user/oldorders', [OrderController::class, 'oldorders']);
    Route::get('/user/savedorders', [OrderController::class, 'savedorders']);
    Route::get('/user/editorder/{id}', [OrderController::class, 'editorder']);
    Route::post('/user/editorder', [OrderController::class, 'editorder_process'])->name('user.editorder');
    Route::get('/user/deleteorder/{id}', [OrderController::class, 'deleteorder']);

    Route::get('/user/confirmorder/{id}', [OrderController::class, 'confirm']);

    Route::get('/user/analytics', [CustomerViewController::class, 'analytics']);
    Route::get('/user/summary', [CustomerViewController::class, 'summary']);
    Route::get('/user/statement', [CustomerViewController::class, 'statement']);

    Route::get('user/finditem', [ProductController::class, 'getproduct']);
    Route::get('user/finditem/{id}', [ProductController::class, 'getproductdetail']);

    Route::get('/user/saveorder/{id}', [OrderAdminController::class, 'save']);
    Route::get('/user/printorder/{id}', [OrderAdminController::class, 'print']);

    Route::get('/user/editprofile', [CustomerViewController::class, 'editprofile']);
    Route::post('/user/editprofile', [CustomerViewController::class, 'edpr_process'])->name('editpr');

    Route::get('/user/timeupdate', [LoginController::class, 'set_time']);
    Route::get('/admin/directlogin/goback/{id}', [LoginController::class, 'changeLoginBack']);

    Route::get('/user/recieve/{id}',[OrderController::class, 'recieveorder']);

    Route::get('/user/chatlist', [UserChatController::class, 'chatlist']);
    Route::get('/user/chatbox/{id}', [UserChatController::class, 'chatbox']);
    Route::POST('/addmsguser', [UserChatController::class, 'addmsguser']);
    Route::get('/user/getchatlist', [UserChatController::class, 'getchatlist']);
    Route::get('/user/msgcnt', [UserChatController::class, 'getmsgcnt']);

    Route::get('/user/chat/seenupdate/{id}/{id2}', [UserChatController::class, 'seenupdate']);
    

    Route::get('/user/damageticket', [CustomerDamageController::class, 'userticket']);
    Route::get('/user/editticket/{id}', [CustomerDamageController::class, 'editticket']);
    Route::get('/user/deleteticket/{id}', [CustomerDamageController::class, 'deleteticket']);
    Route::post('/user/ticketsubmit', [CustomerDamageController::class, 'ticketsubmit'])->name('tkt');
    Route::post('/user/editticketsubmit', [CustomerDamageController::class, 'editticketsubmit'])->name('edittkt');
    Route::get('/user/tickets', [CustomerDamageController::class, 'tickets']);
    Route::get('/user/ticket/{id}', [CustomerDamageController::class, 'ticketdetail']);
    Route::get('/user/updatestat/{id}/{id2}', [CustomerDamageController::class, 'changestat']);
    
});

