<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerDamageController extends Controller
{
    public function userticket(Request $request){
        $result['data'] = DB::table('products')->where('hide', '!=', 'on')->orWhereNull('hide')->orderBy('category', 'DESC')->orderBy('ordernum', 'ASC')->get();
        return view('damage/userticket', $result);
    }

    public function ticketsubmit(Request $request){
       $name = $request->post('name');
       $user = DB::table('customers')->where('name', $name)->first();
       $date = $request->post('date');
       $item = $request->post('item', []);
       $price = $request->post('price', []);
       $prodid = $request->post('prodid', []);
       $category = $request->post('category', []);
       $quantity = $request->post('quantity', []);
       $detail = $request->post('detail', []);

       for ($i=0; $i < count($item); $i++) { 
            if($quantity[$i] > 0){
                DB::table('damage')->insert([
                    'date'=>$date,
                    'invoiceid'=>time()."dmg",
                    'name'=>$name,
                    'cusuni_id'=>$user->cusuni_id,
                    'item'=>$item[$i],
                    'produni_id'=>$prodid[$i],
                    'quantity'=>$quantity[$i],
                    'cusremarks'=>$detail[$i],
                    'category'=>$category[$i]
                ]);
            }
       }

       return redirect('/user/damageticket');
    }
}
