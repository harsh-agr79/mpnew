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

    public function addorder(Request $request){
        $name = $request->post('name');
        $date = $request->post('date');
        $customer = DB::table('customers')->where('name', $name)->first();
        $orderid=$customer->id.time();

        $created_at = $date;
        $nepmonth = getNepaliMonth($created_at);
        $nepyear = getNepaliYear($created_at);

    
            $refid = $customer->refid;
            $refname = $customer->refname;
            $reftype = $customer->reftype;

        $item=$request->post('item',[]);
        $price=$request->post('price',[]);
        $category=$request->post('category',[]);
        $quantity=$request->post('quantity',[]);
        $prodid=$request->post('prodid',[]);

        if($request->post('submit') == 'save'){
            $save = 'save';
        }
        else{
            $save = '';
        }

        for ($i=0; $i < count($item); $i++) { 
            if($quantity[$i] !== NULL && $quantity[$i] !== '0'){
                DB::table('orders')->insert([
                    'name'=>$name,
                    'userid'=>$customer->id,
                    'orderid'=>$orderid,
                    'item'=>$item[$i],
                    'cusuni_id'=>$customer->cusuni_id,
                    'produni_id'=>$prodid[$i],
                    'category'=>$category[$i],
                    'price'=>$price[$i],
                    'quantity'=>$quantity[$i],
                    'approvedquantity'=>'0',
                    'mainstatus'=>'blue',
                    'status'=>'pending',
                    'created_at'=>$created_at,
                    'refname'=>$refname,
                    'refid'=>$refid,
                    'reftype'=>$reftype,
                    'nepmonth'=>$nepmonth,
                    'nepyear'=>$nepyear,
                    'save'=>$save,
                ]);
            }
        }

        return redirect('/user/detail/'.$orderid);
    }

    public function detail(Request $request, $orderid){
        $result['data'] = DB::table('orders')->where('orderid', $orderid) 
        ->join('products', 'orders.produni_id', '=', 'products.produni_id')
        ->selectRaw('orders.*, products.stock')
        ->get();

        return view('customer/detail', $result);
    }
    public function oldorders(Request $request){
        $cust = DB::table('customers')->where('id', session()->get('USER_ID'))->first();
        $name = $cust->name;
        $query = DB::table('orders');
        $query = $query->where(['deleted'=>NULL, 'save'=>NULL, 'name'=>$name])->orderBy('created_at', 'DESC')->groupBy('orderid');
        if($request->get('date')){
           $query = $query->whereDate('created_at', $request->get('date'));
           $result['date']= $request->get('date');
        }
        else{
            $result['date']= '';
        }
        $query = $query->paginate(50);
        $result['data']=$query;
        $result['page'] = 'old';
    

        return view('customer/orders', $result);
    }
    public function savedorders(Request $request){
        $cust = DB::table('customers')->where('id', session()->get('USER_ID'))->first();
        $name = $cust->name;
        $query = DB::table('orders');
        $query = $query->where(['deleted'=>NULL, 'save'=>'save', 'name'=>$name])->orderBy('created_at', 'DESC')->groupBy('orderid');
        if($request->get('date')){
           $query = $query->whereDate('created_at', $request->get('date'));
           $result['date']= $request->get('date');
        }
        else{
            $result['date']= '';
        }
        $query = $query->paginate(50);
        $result['data']=$query;
        $result['page'] = 'saved';
    

        return view('customer/orders', $result);
    }
}
