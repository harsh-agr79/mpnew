<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchController extends Controller
{
    public function batch(Request $request){
        $result['data'] = DB::table('batch')->orderBy('id', 'DESC')->get();
        return view('damage/batch',$result);
    }

    public function addbatch(Request $request, $id = ''){
        if($id !== ""){
            $bat = DB::table('batch')->where('id', $id)->first();
            $result['batch'] = $bat->batch;
            $result['prod'] = $bat->product;
            $result['id'] = $bat->id;
        }
        else{
            $result['batch'] = '';
            $result['prod'] = '';
            $result['id'] = '';
        }
        $result['product'] = DB::table('products')->get();
        return view('damage/addbatch', $result);
    }
    public function addbatch_process(Request $request){
       $name = $request->post("batch");
       $product = DB::table('products')->where('id',$request->post('product'))->first();
       $id = $request->post('id');
       if($id > 0){
        DB::table('batch')->where('id',$id)->update([
            'batch'=>$name,
            'product'=>$product->name,
            'produni_id'=>$product->produni_id
        ]);
       }
       else{
        DB::table('batch')->insert([
            'batch'=>$name,
            'product'=>$product->name,
            'produni_id'=>$product->produni_id
           ]);
       }
       return redirect('/batch');
    }
    public function delbatch($id){
        DB::table('batch')->where('id', $id)->delete();
        return redirect('/batch');
    }
    public function getbatch($prod){
        $res = DB::table('batch')->where('product', $prod)->get();
        return response()->json($res);
    }
}
