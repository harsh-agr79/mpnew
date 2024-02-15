<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SalesReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('salesreturns')->groupBy('returnid')->orderBy('date', 'DESC');
        $result['date'] = '';
        $result['date2'] =  '';
        $result['name'] =  '';
        if($request->get('date')){
            $query = $query->where('date', '>=', $request->get('date'));
            $result['date'] =  $request->get('date');
        }
        if($request->get('date2')){
            $query = $query->where('date', '<=', $request->get('date2'));
            $result['date2'] =  $request->get('date2');
        }
        if($request->get('name')){
            $query = $query->where('name', $request->get('name'));
            $result['name'] =  $request->get('name');
        }
        $query = $query->get();
        $result['data'] = $query;
        return view('admin/slr',$result);
    }

    public function createslr(Request $request){
        $result['data'] = DB::table('products')->orderBy('category', 'ASC')->orderBy('ordernum', 'ASC')->get();
        return view('admin/createslr', $result);
    }

    public function addslr(Request $request){
        $name = $request->post('name');
        $date = $request->post('date');
        $customer = DB::table('customers')->where('name', $name)->first();
        $returnid='slr'.$customer->id.time();

        $item=$request->post('item',[]);
        $price=$request->post('price',[]);
        $category=$request->post('category',[]);
        $quantity=$request->post('quantity',[]);
        $prodid=$request->post('prodid',[]);

        for ($i=0; $i < count($item); $i++) { 
            if($quantity[$i] !== NULL && $quantity[$i] !== '0'){
                DB::table('salesreturns')->insert([
                    'date'=>$date,
                    'name'=>$name,
                    'userid'=>$customer->id,
                    'returnid'=>$returnid,
                    'item'=>$item[$i],
                    'cusuni_id'=>$customer->cusuni_id,
                    'produni_id'=>$prodid[$i],
                    'category'=>DB::table("categories")->where('id', $category[$i])->first()->category,
                    'category_id'=>$category[$i],
                    'price'=>$price[$i],
                    'quantity'=>$quantity[$i],
                ]);
            }
        }

        return redirect('slr');
    }

    public function detail(Request $request, $returnid){
        $result['data'] = DB::table('salesreturns')->where('returnid', $returnid) 
        ->join('products', 'salesreturns.produni_id', '=', 'products.produni_id')
        ->selectRaw('salesreturns.*, products.stock')
        ->get();

        return view('admin/slrdetail', $result);
    }

    public function editslr(Request $request, $returnid){
        $result['slr'] = DB::table('salesreturns')->where('returnid', $returnid)
        ->join('products', 'products.produni_id', '=', 'salesreturns.produni_id')
        ->selectRaw('salesreturns.*, products.img, products.hide, products.stock, products.subcat')
        ->get();
        $result['data'] = DB::table('products')
        ->whereNotIn('name', DB::table('salesreturns')->where('returnid', $returnid)->pluck('item')->toArray())
        ->orderBy('category', 'ASC')
        ->orderBy('ordernum', 'ASC')->get();

        return view('admin/editslr', $result);
    }

    public function editslr_process(Request $request)
    {
        $name = $request->post('name');
        $date = $request->post('date');
        $customer = DB::table('customers')->where('name', $name)->first();
        $returnid = $request->post('returnid');
    
        // $slr = DB::table('salesreturns')->where('returnid', $returnid)->first();

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
                    DB::table('salesreturns')->where('id', $id[$i])->update([
                        'date'=>$date,
                        'name'=>$name,
                        'userid'=>$customer->id,
                        'returnid'=>$returnid,
                        'item'=>$item[$i],
                        'cusuni_id'=>$customer->cusuni_id,
                        'produni_id'=>DB::table('products')->where('name', $item[$i])->first()->produni_id,
                        'category'=>DB::table('products')->where('name', $item[$i])->first()->category,
                        'category_id'=>DB::table('products')->where('name', $item[$i])->first()->category_id,
                        'price'=>$price[$i],
                        'quantity'=>$quantity[$i],
                    ]);
                }
                else{
                    DB::table('orders')->insert([
                        'date'=>$date,
                        'name'=>$name,
                        'userid'=>$customer->id,
                        'returnid'=>$returnid,
                        'item'=>$item[$i],
                        'cusuni_id'=>$customer->cusuni_id,
                        'produni_id'=>DB::table('products')->where('name', $item[$i])->first()->produni_id,
                        'category'=>DB::table('products')->where('name', $item[$i])->first()->category,
                        'category_id'=>DB::table('products')->where('name', $item[$i])->first()->category_id,
                        'price'=>$price[$i],
                        'quantity'=>$quantity[$i],
                    ]);
                }
            }
            elseif ($quantity[$i] == NULL || $quantity[$i] == '0' && $id[$i] !== NULL) {
                DB::table('salesreturns')->where('id', $id[$i])->delete();
            }
        }
        return redirect('slrdetail/'.$returnid);
    }
    public function editslrdet_process(Request $request){
        $returnid = $request->post('returnid');
        $id = $request->post('id', []);
        $price = $request->post('price', []);
        for ($i=0; $i < count($id); $i++) { 
            DB::table('salesreturns')->where('returnid', $returnid)->where('id', $id[$i])->update([
                'price'=>$price[$i],
                'discount'=>$request->post('discount'),
                'remarks'=>$request->post('remarks'),
            ]);
        }
        return redirect('slrdetail/'.$returnid);
    }
    public function deleteslr(Request $request, $id){
        DB::table('salesreturns')->where('returnid', $id)->delete();
        return redirect('/slr');
    }

}
