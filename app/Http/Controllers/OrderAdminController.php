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
    public function appdetails(Request $request, $orderid){
        $result['data'] = DB::table('orders')->where('orderid', $orderid)
        -where('status', 'approved')   
        ->get();

        return view('admin/detail', $result);
    }
    public function detailupdate(Request $request){
        $id = $request->post('id',[]);
        $apquantity = $request->post('apquantity', []);
        $price = $request->post('price', []);
        $status = $request->post('status', []);
        for ($i=0; $i < count($id); $i++) { 
            DB::table('orders')->where('id', $id[$i])->update([
                'approvedquantity'=>$apquantity[$i],
                'price'=>$price[$i],
                'status'=>$status[$i],
                'remarks'=>$request->post('remarks'),
                'cartoons'=>$request->post('cartoons'),
                'transport'=>$request->post('transport'),
            ]);
        }
        return redirect($request->post('previous'));
    }

    public function seenupdate(Request $request){
        $orderid = $request->post('orderid');
        $admin = $request->post('admin');

        DB::table('orders')->where('orderid',$orderid)->update([
            'seen'=>'seen',
            'seenby'=>$admin,
        ]);

        return response()->json('200');
    }
}
