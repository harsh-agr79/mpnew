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
}
