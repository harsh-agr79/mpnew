<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Image;

class PartsController extends Controller
{
    public function part(Request $request){
        $result['data'] = DB::table('parts')->orderBy('id', 'DESC')->get();
        return view('admin/parts',$result);
    }

    public function addpart(Request $request, $id = ''){
        if($id !== ""){
            $bat = DB::table('parts')->where('id', $id)->first();
            $result['name'] = $bat->name;
            $result['prod'] = $bat->product;
            $result['image'] = $bat->image;
            $result['id'] = $bat->id;
            $result['product'] = DB::table('products')->whereNotIn('name', explode('|', $bat->product))->get();
        }
        else{
            $result['name'] = '';
            $result['prod'] = '';
            $result['image'] = '';
            $result['id'] = '';
        $result['product'] = DB::table('products')->get();
        }
        return view('admin/addparts', $result);
    }
    public function addpart_process(Request $request){
        // dd($request->post());
       $name = $request->post("name");
       $product = $request->post("product", []);
       $image = $request->post("image");
       $id = $request->post('id');

    if($file = $request->file('image')){
        // $file = $request->file('img');
        $ext = $file->getClientOriginalExtension();
        $image_name = time().'partimg'.'.'.$ext;
        $image_resize = Image::make($file->getRealPath());
        $image_resize->resize(700, 700, function ($constraint) {
            $constraint->aspectRatio();
        });
        $image_resize->save('partsimage/'.$image_name);

        $image = 'partsimage/'.$image_name;
    }
    else{
       
        if($request->post('oldimage') == NULL){
            $image = '';
        }
        else{
            $image = $request->post('oldimage');
        }
    }
       if($id > 0){
        if($file = $request->file('image')) {
            File::delete( $request->post('oldimage'));
        }
        DB::table('parts')->where('id',$id)->update([
            'name'=>$name,
            'product'=>implode('|',$product),
            'image'=>$image
        ]);
       }
       else{
        DB::table('parts')->insert([
            'name'=>$name,
            'product'=>implode('|',$product),
            'image'=>$image
           ]);
       }
       return redirect('/part');
    }
    public function delpart($id){
        $part = DB::table('parts')->where('id', $id)->first();
        DB::table('parts')->where('id', $id)->delete();
        if(File::exists( $part->image)) {
            File::delete( $part->image);
        }
        return redirect('/part');
    }
}
