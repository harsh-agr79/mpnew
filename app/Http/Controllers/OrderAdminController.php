<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class OrderAdminController extends Controller
{

    //INVOICE VIEW FUNCTION
    public function details(Request $request, $orderid){
        $result['data'] = DB::table('orders')->where('orderid', $orderid) 
        ->join('products', 'orders.produni_id', '=', 'products.produni_id')
        ->selectRaw('orders.*, products.stock')
        ->get();

        return view('admin/detail', $result);
    }
    public function appdetails(Request $request, $orderid){
        $result['data'] = DB::table('orders')->where('orderid', $orderid)
        -where('status', 'approved')   
        ->join('products', 'orders.produni_id', '=', 'products.produni_id')
        ->selectRaw('orders.*, products.stock')
        ->get();

        return view('admin/detail', $result);
    }


    //ORDER UPDATE FUNCTIONS
    public function detailupdate(Request $request){
        $id = $request->post('id',[]);
        $apquantity = $request->post('apquantity', []);
        $quantity = $request->post('quantity', []);
        $price = $request->post('price', []);
        $status = $request->post('status', []);
        $discount = $request->post('discount');
        for ($i=0; $i < count($id); $i++) { 
            if($status[$i] == 'approved'){
                if($apquantity[$i] > 0){
                    $qty = $apquantity[$i];
                }
                else{
                    $qty = $quantity[$i];
                }
            }
            else{
                $qty = 0;
            }
            DB::table('orders')->where('id', $id[$i])->update([
                'approvedquantity'=>$qty,
                'price'=>$price[$i],
                'status'=>$status[$i],
                'remarks'=>$request->post('remarks'),
                'cartoons'=>$request->post('cartoons'),
                'transport'=>$request->post('transport'),
                'discount'=>$discount,
            ]);
        }
        updateMainStatus($request->post('orderid'));

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
    public function updatedeliver(Request $request){
        $orderid = $request->post('orderid');
        $delivered = $request->post('delivered');
        if($delivered == 'on'){
            $packorder = 'delivered';
        }
        else{
            $packorder = 'packorder';
        }
        DB::table('orders')->where('orderid',$orderid)->update([
            'delivered'=>$delivered,
            'clnstatus'=>$packorder
        ]);
        updateMainStatus($orderid);
        return response()->json($request->all());
    }

    //ALL ORDERS VIEWS

    public function orders(Request $request){
        $query = DB::table('orders');
        $query = $query->where(['deleted'=>NULL, 'save'=>NULL])->orderBy('created_at', 'DESC')->groupBy('orderid');
        if($request->get('name')){
           $query = $query->where('name', $request->get('name'));
           $result['name']= $request->get('name');
        }
        else{
            $result['name'] ='';
        }
        if($request->get('date')){
           $query = $query->whereDate('created_at', $request->get('date'));
           $result['date']= $request->get('date');
        }
        else{
            $result['date']= '';
        }
        $query = $query->paginate(50);
        $result['data']=$query;
    

        return view('admin/orders', $result);
    }

    public function approvedorders(Request $request){
        $result['data'] = DB::table('orders')
        ->where(['deleted'=>NULL, 'save'=>NULL])
        ->whereIn('mainstatus', ['amber darken-1', 'deep-purple'])
        ->groupBy('orderid')
        ->orderBy('created_at', 'DESC')
        ->get();

        return view('admin/approvedorders', $result);
    }
    public function pendingorders(Request $request){
        $result['data'] = DB::table('orders')
        ->where(['deleted'=>NULL, 'save'=>NULL])
        ->where('mainstatus', 'blue')
        ->groupBy('orderid')
        ->orderBy('created_at', 'DESC')
        ->get();

        return view('admin/pendingorders', $result);
    }
    public function rejectedorders(Request $request){
        $result['data'] = DB::table('orders')
        ->where(['deleted'=>NULL, 'save'=>NULL])
        ->where('mainstatus', 'red')
        ->groupBy('orderid')
        ->orderBy('created_at', 'DESC')
        ->get();

        return view('admin/rejectedorders', $result);
    }
    public function deliveredorders(Request $request){
        $result['data'] = DB::table('orders')
        ->where(['deleted'=>NULL, 'save'=>NULL])
        ->where('mainstatus', 'green')
        ->groupBy('orderid')
        ->orderBy('created_at', 'DESC')
        ->paginate(50);

        return view('admin/deliveredorders', $result);
    }
}
