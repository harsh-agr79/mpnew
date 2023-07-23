<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class FrontController extends Controller
{
    public function index(Request $request){
        $result['data'] = DB::table('front')->get();
        return view('admin/frontsettings', $result);
    }

    public function addimg(Request $request){
        if($files = $request->file('img')){
            $a = 0;
            foreach($files as $file) {
                $a = $a + 1;
                $image_name = time().$a;
                $ext = $file->extension();
                $image_fullname = $image_name.'.'.$ext;
                $upload_path = 'docs/';
                $image_url = $upload_path.$image_fullname;
                $file->move($upload_path, $image_fullname);
                $image[] = $image_url;
               DB::table('front')->insert([
                    'image'=>$image_url,
                ]);
            }

        }
        return redirect('frontsettings');
    }
    public function deleteimg(Request $request, $path, $name){
        $image_path = $path.'/'.$name;
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
        DB::table('front')->where(['image'=>$image_path])->delete();

        return redirect('frontsettings');
    }
}
