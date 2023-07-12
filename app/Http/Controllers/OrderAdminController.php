<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OrderAdminController extends Controller
{
    public function details(Request $request, $orderid){
        $result['data'] = DB::table('orders')->where('orderid', $orderid)   
        ->get();

        return view('admin/detail', $result);
    }
}
