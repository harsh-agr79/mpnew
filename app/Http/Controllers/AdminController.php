<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function changemode(Request $request){
        $admin = DB::table('admins')->where('id',session()->get('ADMIN_ID'))->first();
        if($admin->mode == 'light'){
            $mode = 'dark';
        }
        else{
            $mode = 'light';
        }
        DB::table('admins')->where('id',session()->get('ADMIN_ID'))->update([
            'mode'=>$mode
        ]);
        return redirect(url()->previous());
    }
}
