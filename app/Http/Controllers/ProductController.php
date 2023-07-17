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
}
