<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SubcategoryController extends Controller
{
    public function subcat(Request $request){
        $result['data'] = DB::table('subcategory')->get();


        return view('admin/subcat',  $result);
    }
    public function addsubcat(Request $request, $id=''){
        if($id>0){
            $arr=DB::table('subcategory')->where(['id'=>$id])->get();

            $result['subcategory']=$arr['0']->subcategory;
            $result['parent']=$arr['0']->parent;
            $result['category_id']=$arr['0']->category_id;
            $result['id']=$id;
        }
        else{
            $result['subcategory']='';
            $result['parent']='';
            $result['category_id']='';
            $result['id']='';
        }
        $result['categories'] = DB::table("categories")->get();
        return view('admin/addsubcat', $result);
    }
    public function addsubcat_process(Request $request){
        $subcategory = $request->post('subcategory');
        $subcategory_old = $request->post('subcategory_old');
        $parent = $request->post('category');
         $id = $request->post('id');
        if($id > 0 ){
         DB::table('subcategory')->where('id', $id)->update([
             'subcategory'=>$subcategory,
             'parent'=>DB::table('categories')->where('id', $parent)->first()->category,
             'category_id'=>$parent,
            ]);
             $products = DB::table('products')->get();
             foreach($products as $prod){
                 if($prod->subcat !== NULL){
                     $sbc = explode('|', $prod->subcat);
                     if(in_array($subcategory_old, $sbc)){
                         $arr = array_diff($sbc, array($subcategory_old));
                         array_push($arr, $subcategory);
                         DB::table('products')->where('id', $prod->id)->update([
                             'subcat'=>implode('|', $arr),
                         ]);
                     }
                 }
             }
        }
        else{
         DB::table('subcategory')->insert([
             'subcategory'=>$subcategory,
             'parent'=>DB::table('categories')->where('id', $parent)->first()->category,
             'category_id'=>$parent,
            ]);
        }
        return redirect('subcategory');  
     }
     public function delsubcat(Request $request, $id){
        $model=DB::table('subcategory')->where(['id'=>$id]);
        $model->delete();
        return redirect('subcategory');
    }
    public function getsubcat(Request $request, $parent){
        $subcat = DB::table('subcategory')->where('parent', $parent)->get();
        return response()->json($subcat);
    }
}
