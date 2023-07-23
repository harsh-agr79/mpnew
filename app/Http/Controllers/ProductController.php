<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function getproduct(){
        $c = DB::table('products')->get();
        return response()->json($c);
    }

    public function index(Request $request){
        $result['data'] = DB::table('products')->where('deleted', NULL)->orderBy('category', 'ASC')->orderBy('ordernum', 'ASC')->get();
        $result['cat'] = DB::table('products')->where('deleted', NULL)->groupBy('category')->orderBy('category', 'ASC')->get();
        $result['pb'] = DB::table('products')->orderBy('ordernum','ASC')->where('category','powerbank')->where('deleted', NULL)->get();
        $result['ch'] = DB::table('products')->orderBy('ordernum','ASC')->where('category', 'charger')->where('deleted', NULL)->get();
        $result['ca'] = DB::table('products')->orderBy('ordernum','ASC')->where('category', 'cable')->where('deleted', NULL)->get();
        $result['ep'] = DB::table('products')->orderBy('ordernum','ASC')->where('category', 'earphone')->where('deleted', NULL)->get();
        $result['bt'] = DB::table('products')->orderBy('ordernum','ASC')->where('category', 'btitem')->where('deleted', NULL)->get();
        $result['oth'] = DB::table('products')->orderBy('ordernum','ASC')->where('category', 'others')->where('deleted', NULL)->get();

        return view('admin/product', $result);
    }

    public function arrangeprod(Request $request){
        $ids = $request->post('id', []);
        $category = $request->post('category');

        for ($i=0; $i < count($ids); $i++) { 
            DB::table('products')->where('id', $ids[$i])->update([
                'ordernum'=>$i+1,
            ]);
        }
        $request->session()->flash('category', $category);
        return redirect('products');
    }
    public function addproduct(Request $request, $id=''){
        return view('admin/addproduct');
    }
}
