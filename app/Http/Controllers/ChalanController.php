<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ChalanController extends Controller
{
    public function updatechalan(Request $request){
        $orderid = $request->post('orderid');
        $packorder = $request->post('packorder');

        DB::table('orders')->where('orderid', $orderid)->update([
            'clnstatus'=>$packorder,
        ]);
        return response()->json($request->all());
    }
}
