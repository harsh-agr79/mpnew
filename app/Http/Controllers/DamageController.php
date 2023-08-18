<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DamageController extends Controller
{
    public function userticket(Request $request){
        $result['data'] = DB::table('products')->where('hide', '!=', 'on')->orWhereNull('hide')->orderBy('category', 'DESC')->orderBy('ordernum', 'ASC')->get();
        return view('damage/userticket', $result);
    }
}
