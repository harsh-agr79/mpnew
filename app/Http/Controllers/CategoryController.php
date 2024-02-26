<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(){
            $result['data'] = DB::table('categories')->get();
            return view('admin.category',$result);
    }
    public function getcategory($id){
        $category = DB::table('categories')->where('id', $id)->first();
        return response()->json($category, 200);
    }
    public function editcategory(Request $request){
        $request->validate([
            'category'=>'required|unique:categories,category,'.$request->post('id'),       
        ]);

        $category = $request->post('category');
        $id = $request->post('id');

        DB::table('categories')->where('id', $id)->update([
            'category'=>$category,
        ]);
        DB::table('products')->where('category_id', $id)->update([
            'category'=>$category
        ]);
        DB::table('orders')->where('category_id', $id)->update([
            'category'=>$category
        ]);
        DB::table('salesreturns')->where('category_id', $id)->update([
            'category'=>$category
        ]);
        DB::table('damage')->where('category_id', $id)->update([
            'category'=>$category
        ]);
        DB::table('problem')->where('category_id', $id)->update([
            'category'=>$category
        ]);
        DB::table('subcategory')->where('category_id', $id)->update([
            'parent'=>$category
        ]);
        return response()->json($request->post(), 200);
    }
    public function getcategorydata(){
        $category = DB::table('categories')->get();
        return response()->json($category,200);
    }
    public function addcategory(Request $request){
        $request->validate([
            'category'=>'required|unique:categories,category,',           
        ]);
        $category = $request->post('category');
        DB::table('categories')->insert([
            'category'=>$category
        ]);
        return response()->json($request->post('id'), 200);
    }
    public function delcategory($id){
        DB::table('categories')->where('id', $id)->delete();
            return response()->json("Category Deleted!", 200);
    }
    public function bulkupdate(){
        // $cats = DB::table('categories')->get();
        // foreach ($cats as $item) {
        //     DB::table('orders')->where('category', $item->category)->update([
        //         'category_id'=>$item->id
        //     ]);
        //     DB::table('products')->where('category', $item->category)->update([
        //         'category_id'=>$item->id
        //     ]);
        //     DB::table('problem')->where('category', $item->category)->update([
        //         'category_id'=>$item->id
        //     ]);
        //     DB::table('salesreturns')->where('category', $item->category)->update([
        //         'category_id'=>$item->id
        //     ]);
        //     DB::table('damage')->where('category', $item->category)->update([
        //         'category_id'=>$item->id
        //     ]);
        //     DB::table('subcategory')->where('parent', $item->category)->update([
        //         'category_id'=>$item->id
        //     ]);
        //     }

        $products = DB::table("products")->get();
        foreach ($products as $item) {
            DB::table("orders")->where("produni_id", $item->produni_id)->update([
                'category'=>$item->category,
                'category_id'=>$item->category_id
            ]);
            DB::table("salesreturns")->where("produni_id", $item->produni_id)->update([
                'category'=>$item->category,
                'category_id'=>$item->category_id
            ]);
            DB::table("damage")->where("produni_id", $item->produni_id)->update([
                'category'=>$item->category,
                'category_id'=>$item->category_id
            ]);
        }
        }
}
