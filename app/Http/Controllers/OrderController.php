<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function createorder(Request $request){
        $result['data'] = DB::table('products')->where('hide', '!=', 'on')->orWhereNull('hide')->orderBy('category', 'DESC')->orderBy('ordernum', 'ASC')->get();
        return view('customer/createorder', $result);
    }
}
