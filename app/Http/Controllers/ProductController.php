<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;


class ProductController extends Controller
{
    public function getproduct(){
        $c = DB::table('products')->get();
        return response()->json($c);
    }
    public function getproductdetail(Request $request, $id){
        $c = DB::table('products')->where('id', $id)->first();
        return response()->json($c);
    }

    public function index(Request $request){
        $result['data'] = DB::table('products')->where('deleted', NULL)->orderBy('category', 'ASC')->orderBy('ordernum', 'ASC')->get();
        $cats = DB::table("categories")->get();
        foreach($cats as $item){
            $result['data2'][$item->category] = DB::table('products')->orderBy('ordernum','ASC')->where('category',$item->category)->where('deleted', NULL)->get();
        }
        $result['cat'] = DB::table("categories")->get();
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
        if($id>0){
            $arr=DB::table('products')->where(['id'=>$id])->get();

            $result['name']=$arr['0']->name;
            $result['category']=$arr['0']->category;
            $result['category_id']=$arr['0']->category_id;
            $result['stock']=$arr['0']->stock;
            if($arr['0']->stock=="on"){
                $result['stock_selected']="checked";
            }
            else{
                $result['stock_selected']="";
            }
            if($arr['0']->hide=="on"){
                $result['hide_selected']="checked";
            }
            else{
                $result['hide_selected']="";
            }
            $result['price']=$arr['0']->price;
            $result['ordernum']=$arr['0']->ordernum;
            $result['details']=$arr['0']->details;
            $result['subcat']=$arr['0']->subcat;
            $result['unique_id']=$arr['0']->produni_id;
            $result['img']=$arr['0']->img;
            $result['img2']=$arr['0']->img2;
            $result['id']=$arr['0']->id;
        }
        else{
            $result['name']='';
            $result['category']='';
            $result['category_id']='';
            $result['stock']='';
            $result['stock_selected']='';
            $result['hide_selected']='';
            $result['ordernum']='';
            $result['price']='';
            $result['details']='';
            $result['subcat']='';
            $result['unique_id']='';
            $result['img']='';
            $result['img2']='';
            $result['id']=0;
        }
        $result['categories'] = DB::table("categories")->get();
        return view('admin/addproduct', $result);
    }
    public function addprod_process(Request $request){
        $request->validate([
            'name'=>'required|unique:products,name,'.$request->post('id'),
            'uniqueid'=>'required|unique:products,produni_id,'.$request->post('id')
        ]);
        if($request->post('id')>0){
            $img_val='mimes:jpeg,jpg,png';
        }
        else{
            $img_val='required|mimes:jpeg,jpg,png';
        }
        if($request->hasfile('img')){
            if($request->post('id')>0){
                $arrimage=DB::table('products')->where(['id'=>$request->post('id')])->get();
                if(Storage::exists('/public/media/'.$arrimage[0]->img)){
                    Storage::delete('/public/media/'.$arrimage[0]->img);
                }
            }
            $img=$request->file('img');
            $ext=$img->extension();
            $image_name=time().'.'.$ext;
            $img->storeAs('/public/media',$image_name);
            // $model->img=$image_name;
        }
        else{
            $arrimage=DB::table('products')->where(['id'=>$request->post('id')])->get();
            $image_name = $arrimage[0]->img;
        }
        if($request->hasfile('img2')){
            if($request->post('id')>0){
                $arrimage=DB::table('products')->where(['id'=>$request->post('id')])->get();
                if(Storage::exists('/public/media/'.$arrimage[0]->img2)){
                    Storage::delete('/public/media/'.$arrimage[0]->img2);
                }
            }
            $img2=$request->file('img2');
            $ext=$img2->extension();
            $image_name2=time().'1'.'.'.$ext;
            $img2->storeAs('/public/media',$image_name2);
            // $model->img2=$image_name2;
        }
        else{
            $arrimage=DB::table('products')->where(['id'=>$request->post('id')])->get();
            $image_name2 = $arrimage[0]->img2;
        }
        if($request->post('id') > 0){
            $initial = $request->post('name1');
            $changed = $request->post('name');
            $idinitial = $request->post('uid_old');
            $idchanged = $request->post('uniqueid');
            if($request->post('id')>0){
                DB::table('orders')->where('item',$initial)->update([
                    'item'=>$changed,
                    'produni_id'=>$idchanged
                ]);
                DB::table('salesreturns')->where('item',$initial)->update([
                    'item'=>$changed,
                    'produni_id'=>$idchanged
                ]);
                DB::table('batch')->where('product',$initial)->update([
                    'product'=>$changed,
                    'produni_id'=>$idchanged
                ]);
                DB::table('damage')->where('item',$initial)->update([
                    'item'=>$changed,
                    'produni_id'=>$idchanged
                ]);
                DB::table('orders')->where('produni_id',$idinitial)->update([
                    'produni_id'=>$idchanged
                ]);
                DB::table('salesreturns')->where('produni_id',$idinitial)->update([
                    'produni_id'=>$idchanged
                ]);
                DB::table('batch')->where('produni_id',$idinitial)->update([
                    'produni_id'=>$idchanged
                ]);
                DB::table('damage')->where('produni_id',$idinitial)->update([
                    'produni_id'=>$idchanged
                ]);
                $parts = DB::table('parts')->get();
                foreach($parts as $item){
                    $ar = explode('|',$item->product);
                    if(in_array($initial, $ar)){
                        $key = array_search($initial, $ar);
                        $ar[$key] = $changed;
                        DB::table('parts')->where('id', $item->id)->update([
                            'product'=>implode('|', $ar)
                        ]);
                    }
                }
            }
            DB::table('products')->where('id', $request->post('id'))->update([
                'name'=>$request->post('name'),
                'price'=>$request->post('price'),
                'category'=>DB::table('categories')->where('id', $request->post('category'))->first()->category,
                'category_id'=>$request->post('category'),
                'subcat'=>implode('|',$request->post('subcat', [])),
                'produni_id'=>$request->post('uniqueid'),
                'details'=>$request->post('details'),
                'stock'=>$request->post('stock'),
                'hide'=>$request->post('hide'),
                'img'=>$image_name,
                'img2'=>$image_name2,
            ]);
        }
        else{
            DB::table('products')->insert([
                'name'=>$request->post('name'),
                'price'=>$request->post('price'),
                'category'=>DB::table('categories')->where('id', $request->post('category'))->first()->category,
                'category_id'=>$request->post('category'),
                'subcat'=>implode('|',$request->post('subcat', [])),
                'produni_id'=>$request->post('uniqueid'),
                'details'=>$request->post('details'),
                'stock'=>$request->post('stock'),
                'hide'=>$request->post('hide'),
                'img'=> $image_name,
                'img2'=>$image_name2,
            ]);
        }
        DB::table('orders')->where('produni_id', $request->post('uniqueid'))->update([
            'category'=>DB::table('categories')->where('id', $request->post('category'))->first()->category,
            'category_id'=>$request->post('category'),
        ]);
        DB::table('salesreturns')->where('produni_id', $request->post('uniqueid'))->update([
            'category'=>DB::table('categories')->where('id', $request->post('category'))->first()->category,
            'category_id'=>$request->post('category'),
        ]);
        DB::table('damage')->where('produni_id', $request->post('uniqueid'))->update([
            'category'=>DB::table('categories')->where('id', $request->post('category'))->first()->category,
            'category_id'=>$request->post('category'),
        ]);
        $request->session()->flash('category', $request->post('category'));
        return redirect('products');
    }
    public function deleteprod(Request $request, $id){
        DB::table('products')->where('id',$id)->update([
            'deleted'=>'on',
            'deleted_at'=>date('Y-m-d H:i:s')
        ]);
        return redirect('products');
    }
}
