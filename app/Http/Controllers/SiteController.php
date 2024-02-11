<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    public function index(Request $request){
        $admin = DB::table("admins")->where('id',$request->session()->get('ADMIN_ID'))->first();
        return view('admin/sitesettings', compact('admin'));
    }
    public function disable(Request $request){
        DB::table('admins')->where('disabled',NULL)->update([
            'disabled'=>'on'
        ]);
        DB::table('customers')->where('disabled',NULL)->update([
            'disabled'=>'on'
        ]);
        return redirect('/');
    }
    public function enable(Request $request){
        DB::table('admins')->where('disabled','on')->update([
            'disabled'=>NULL
        ]);
        DB::table('customers')->where('disabled','on')->update([
            'disabled'=>NULL
        ]);
        return redirect('/');
    }
    public function error(Request $request){
        if(session()->has('ADMIN_LOGIN')){
            $chk = DB::table("admins")->where('id',$request->session()->get('ADMIN_ID'))->first()->disabled;
        }
        elseif(session()->has('USER_LOGIN')){
            $chk = DB::table("admins")->where('id',$request->session()->get('ADMIN_ID'))->first()->disabled;
        }
        if($chk == "on"){
            return view('/errorload');
        }
        else{
            return redirect("/");
        }   
    }
}
