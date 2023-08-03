<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrashController extends Controller
{
    public function index(Request $request){
        $result['order'] = DB::table('orders')->where('deleted', 'on')->groupBy('orderid')->orderBy('created_at', 'DESC')->get();
        $result['payment'] = DB::table('payments')->where('deleted', 'on')->orderBy('date', 'DESC')->get();
        $result['customer'] = DB::table('customers')->where('deleted', 'on')->get();
        $result['product'] = DB::table('products')->where('deleted', 'on')->get();
    
        return view('admin/trash', $result);
    }
    public function restoreor($id){
        DB::table('orders')->where('orderid', $id)->update([
            'deleted'=>NULL
        ]);
        return redirect('/trash');
    }
    public function restorepay($id){
        DB::table('payments')->where('paymentid', $id)->update([
            'deleted'=>NULL
        ]);
        return redirect('/trash');
    }
    public function restorecus($id){
        DB::table('customers')->where('id', $id)->update([
            'deleted'=>NULL
        ]);
        return redirect('/trash');
    }
    public function restoreprod($id){
        DB::table('products')->where('id', $id)->update([
            'deleted'=>NULL
        ]);
        return redirect('/trash');
    }

    public function deleteor($id){
        DB::table('orders')->where('orderid', $id)->delete();
        return redirect('/trash');
    }
    public function deletepay($id){
        DB::table('payments')->where('paymentid', $id)->delete();
        return redirect('/trash');
    }
    public function deletecus($id){
        DB::table('customers')->where('id', $id)->delete();
        return redirect('/trash');
    }
    public function deleteprod($id){
        DB::table('products')->where('id', $id)->delete();
        return redirect('/trash');
    }
}
