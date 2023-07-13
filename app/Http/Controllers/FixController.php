<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FixController extends Controller
{
    public function update(){
        // $orders = DB::table('orders')->groupBy('orderid')->orderBy('id', 'DESC')->where('mainstatus', NULL)->get();
        // foreach($orders as $item){
        //     DB::table('orders')->where('orderid', $item->orderid)->update([
        //         'mainstatus'=>getpstat($item->orderid)
        //     ]);
        // }
    }
}
