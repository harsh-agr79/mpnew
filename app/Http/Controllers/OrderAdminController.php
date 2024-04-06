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

        if($request->post('previous') == url('editorder/'.DB::table('orders')->where('id', $id[0])->first()->orderid)){
            return redirect('dashboard');
        }
        else{
            return redirect($request->post('previous'));
        }
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
        $result['date']= '';
        $result['date2']= '';
        $result['status']= '';
        $result['product']= '';
        $result['name']= '';

        $query = DB::table('orders');
        $query = $query->where(['deleted'=>NULL, 'save'=>NULL])->orderBy('created_at', 'DESC');
        if($request->get('name')){
           $query = $query->where('name', $request->get('name'))->groupBy('orderid');
           $result['name']= $request->get('name');
        }
        else{
            $result['name'] ='';
        }
        if($request->get('date')){
           $query = $query->where('created_at', '>=', $request->get('date'))->groupBy('orderid');
           $result['date']= $request->get('date');
        }
        if($request->get('date2')){
            $query = $query->where('created_at', '<=', $request->get('date2'))->groupBy('orderid');
            $result['date2']= $request->get('date2');
         }
         if($request->get('status') && $request->get('product') == ''){
            $query = $query->where('status',$request->get('status'))->groupBy('orderid');
            $result['status']= $request->get('status');
         }
         if($request->get('status') == '' && $request->get('product') != ''){
            $query = $query->where('item',$request->get('product'));
            $result['product'] = $request->get('product');
         }
         if($request->get('status') && $request->get('product') != ''){
            $query = $query->where('status',$request->get('status'));
            $query = $query->where('item',$request->get('product'));
            $result['status']= $request->get('status');
            $result['product'] = $request->get('product');
         }
         else{
            $query = $query->groupBy('orderid');
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
    public function createorder(Request $request){
        $result['data'] = DB::table('products')->orderBy('category', 'DESC')->orderBy('ordernum', 'ASC')->get();
        return view('admin/createorder', $result);
    }
    public function addorder(Request $request){
        $name = $request->post('name');
        $date = $request->post('date');
        $customer = DB::table('customers')->where('name', $name)->first();
        $orderid=$customer->id.time();

        $time = date('H:i:s');
        $created_at = $date." ".$time;
        $nepmonth = getNepaliMonth($created_at);
        $nepyear = getNepaliYear($created_at);

        if($request->session()->get('ADMIN_TYPE') == 'marketer'){
            $refid = $request->session()->get('ADMIN_ID');
            $refname = DB::table('admins')->where('id', $refid)->first()->name;
            $reftype = 'marketer';
        }
        elseif($request->session()->get('ADMIN_TYPE') == 'admin'){
            $refname = $customer->refname;
            if($refname == NULL)
            {
                $refid = NULL;
                $reftype = NULL;
            }
            else{
                $ref = DB::table('customers')->where('name', $refname)->first();
                if($ref == NULL)
                {
                    $refid = DB::table('admins')->where('name', $refname)->first()->id;
                    $reftype = DB::table('admins')->where('name', $refname)->first()->type;
                }
                else
                {
                    $refid = DB::table('customers')->where('name', $refname)->first()->id;
                    $reftype = DB::table('customers')->where('name', $refname)->first()->type;
                }
            }
        }
        else
        {
            $refid = NULL;
            $refname = NULL;
            $reftype = NULL;
        }

        $item=$request->post('item',[]);
        $price=$request->post('price',[]);
        $category=$request->post('category',[]);
        $quantity=$request->post('quantity',[]);
        $prodid=$request->post('prodid',[]);

        for ($i=0; $i < count($item); $i++) { 
            if($quantity[$i] !== NULL && $quantity[$i] !== '0'){
                DB::table('orders')->insert([
                    'name'=>$name,
                    'userid'=>$customer->id,
                    'orderid'=>$orderid,
                    'item'=>$item[$i],
                    'cusuni_id'=>$customer->cusuni_id,
                    'produni_id'=>$prodid[$i],
                    'category'=>DB::table("categories")->where('id', $category[$i])->first()->category,
                    'category_id'=>$category[$i],
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
                ]);
            }
        }

        return redirect('pendingorders');
    }

    public function deleteorder(Request $request, $orderid)
    {
        DB::table('orders')->where('orderid', $orderid)->update([
            'deleted'=>'on',
            'deleted_at'=>date('Y-m-d H:i:s')
        ]);
        return redirect(url()->previous());
    }

    public function editorder(Request $request, $orderid){
        $result['order'] = DB::table('orders')->where('orderid', $orderid)
        ->join('products', 'products.produni_id', '=', 'orders.produni_id')
        ->selectRaw('orders.*, products.img, products.offer, products.hide, products.stock, products.subcat')
        ->get();
        $result['data'] = DB::table('products')
        ->whereNotIn('name', DB::table('orders')->where('orderid', $orderid)->pluck('item')->toArray())
        ->orderBy('category', 'ASC')
        ->orderBy('ordernum', 'ASC')->get();

        return view('admin/editorder', $result);
    }

    public function editorder_process(Request $request)
    {
        $name = $request->post('name');
        $date = $request->post('date');
        $customer = DB::table('customers')->where('name', $name)->first();
        $orderid = $request->post('orderid');
        $time = date('H:i:s');
        $created_at = $date." ".$time;
        $nepmonth = getNepaliMonth($created_at);
        $nepyear = getNepaliYear($created_at);
        $order = DB::table('orders')->where('orderid', $orderid)->first();

        if($request->session()->get('ADMIN_TYPE') == 'marketer'){
            $refid = $request->session()->get('ADMIN_ID');
            $refname = DB::table('admins')->where('id', $refid)->first()->name;
            $reftype = 'marketer';
        }
        elseif($request->session()->get('ADMIN_TYPE') == 'admin'){
            $refname = $customer->refname;
            if($refname == NULL)
            {
                $refid = NULL;
                $reftype = NULL;
            }
            else{
                $ref = DB::table('customers')->where('name', $refname)->first();
                if($ref == NULL)
                {
                    $refid = DB::table('admins')->where('name', $refname)->first()->id;
                    $reftype = DB::table('admins')->where('name', $refname)->first()->type;
                }
                else
                {
                    $refid = DB::table('customers')->where('name', $refname)->first()->id;
                    $reftype = DB::table('customers')->where('name', $refname)->first()->type;
                }
            }
        }
        else
        {
            $refid = NULL;
            $refname = NULL;
            $reftype = NULL;
        }

        $item=$request->post('item',[]);
        $price=$request->post('price',[]);
        // $category=$request->post('category',[]);
        $quantity=$request->post('quantity',[]);
        // $prodid=$request->post('prodid',[]);
        $id = $request->post('id', []);
        $status = $request->post('status',[]);

        for ($i=0; $i < count($item); $i++) { 
            if($quantity[$i] !== NULL && $quantity[$i] !== '0'){
                if($id[$i]){
                    DB::table('orders')->where('id', $id[$i])->update([
                        'name'=>$name,
                        'userid'=>$customer->id,
                        'orderid'=>$orderid,
                        'item'=>$item[$i],
                        'cusuni_id'=>$customer->cusuni_id,
                        'produni_id'=>DB::table('products')->where('name', $item[$i])->first()->produni_id,
                        'category'=>DB::table('products')->where('name', $item[$i])->first()->category,
                        'category_id'=>DB::table('products')->where('name', $item[$i])->first()->category_id,
                        'price'=>$price[$i],
                        'quantity'=>$quantity[$i],
                        // 'approvedquantity'=>'0',
                        'mainstatus'=>'blue',
                        'status'=>$status[$i],
                        'created_at'=>$created_at,
                        'refname'=>$refname,
                        'refid'=>$refid,
                        'reftype'=>$reftype,
                        'nepmonth'=>$nepmonth,
                        'nepyear'=>$nepyear,
                    ]);
                }
                else{
                    DB::table('orders')->insert([
                        'name'=>$name,
                        'userid'=>$customer->id,
                        'orderid'=>$orderid,
                        'item'=>$item[$i],
                        'cusuni_id'=>$customer->cusuni_id,
                        'produni_id'=>DB::table('products')->where('name', $item[$i])->first()->produni_id,
                        'category'=>DB::table('products')->where('name', $item[$i])->first()->category,
                        'category_id'=>DB::table('products')->where('name', $item[$i])->first()->category_id,
                        'price'=>$price[$i],
                        'quantity'=>$quantity[$i],
                        'approvedquantity'=>'0',
                        'mainstatus'=>'blue',
                        'status'=>$status[$i],
                        'created_at'=>$created_at,
                        'refname'=>$refname,
                        'refid'=>$refid,
                        'reftype'=>$reftype,
                        'nepmonth'=>$nepmonth,
                        'nepyear'=>$nepyear,
                        'seen'=>$order->seen,
                        'seenby'=>$order->seenby,
                        'save'=>$order->save,
                        'discount'=>$order->discount,
                        'remarks'=>$order->remarks,
                        'userremarks'=>$order->userremarks,
                    ]);
                }
            }
            elseif ($quantity[$i] == NULL || $quantity[$i] == '0' && $id[$i] !== NULL) {
                DB::table('orders')->where('id', $id[$i])->delete();
            }
        }
        updateMainStatus($orderid);
        return redirect('detail/'.$orderid);
    }
    
    public function save($orderid){
        $result['data'] = DB::table('orders')->where('orderid',$orderid)->where('status', 'approved')->get();
        return view('admin/saveorder', $result);
    }
    public function print($orderid){
        $result['data'] = DB::table('orders')->where('orderid',$orderid)->where('status', 'approved')->get();
        return view('admin/printorder', $result);
    }
    public function bprintindex(Request $request){
        $query = DB::table('orders')->where('deleted', NULL)->where('save', NULL)->orderBy('created_at', 'DESC')->groupBy('orderid');
        $result['date'] = '';
        $result['date2'] =  '';
        $result['name'] =  '';
        if($request->get('date')){
            $query = $query->where('created_at', '>=', $request->get('date'));
            $result['date'] =  $request->get('date');
        }
        if($request->get('date2')){
            $query = $query->where('created_at', '<=', $request->get('date2'));
            $result['date2'] =  $request->get('date2');
        }
        if($request->get('name')){
            $query = $query->where('name', $request->get('name'));
            $result['name'] =  $request->get('name');
        }
        $query = $query->paginate(45);
        $result['data'] = $query;

        return view('admin/bprintindex', $result);
    }

    public function bulkprint(Request $request){
        $result['orderids'] = $request->post('orderid',[]);

        return view('admin/bulkprint', $result);
    }
}
