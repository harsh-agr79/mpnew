<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CustomerController extends Controller
{
    public function getcustomer(){
        if(session()->get('ADMIN_TYPE') == 'marketer')
        {
             $c = DB::table('customers')->where('refid', session()->get('ADMIN_ID'))->get();
        }else
        {
            $c = DB::table('customers')->get();
        }
        return response()->json($c);
    }
}
