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
                    'mainstatus'=>'pending',
                    'instatus'=>'pending',
                    'produni_id'=>$prodid[$i],
                    'quantity'=>$quantity[$i],
                    'cusremarks'=>$detail[$i],
                    'category'=>$category[$i]
                ]);
            }
       }

       return redirect('/user/tickets');
    }

    public function tickets(){
        $user = DB::table('customers')->where('id', session()->get('USER_ID'))->first();
        $result['data'] = DB::table('damage')->where('name', $user->name)->orderBy('date', 'DESC')->groupBy('invoiceid')->get();
        return view('customer/tickets', $result);
    }
    public function ticketdetail($invoice){
        $result['data'] = DB::table('damage')->where('invoiceid', $invoice)->groupBy('item')->get();
        return view('customer/ticketdetail', $result);
    }
    public function editticket(Request $request, $invoice){
        $result['inv'] = DB::table('damage')->where('invoiceid', $invoice)->get();
        $result['data'] = DB::table('products')->where('hide', NULL)->whereNotIn('name', DB::table('damage')->where('invoiceid', $invoice)->pluck('item')->toArray())->orderBy('category', 'DESC')->orderBy('ordernum', 'ASC')->get();
        return view('customer/editticket', $result);
    }

    public function editticketsubmit(Request $request){
        $invoice = $request->post('invoice');
        DB::table('damage')->where('invoiceid',$invoice)->delete();
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
                     'invoiceid'=>$invoice,
                     'name'=>$name,
                     'cusuni_id'=>$user->cusuni_id,
                     'item'=>$item[$i],
                     'mainstatus'=>'pending',
                    'instatus'=>'pending',
                     'produni_id'=>$prodid[$i],
                     'quantity'=>$quantity[$i],
                     'cusremarks'=>$detail[$i],
                     'category'=>$category[$i]
                 ]);
             }
        }
 
        return redirect('/user/tickets');
    }

    public function changestat($invoiceid,$stat){
        $date = date('Y-m-d H:i:s');
        $inv = DB::table('damage')->where('invoiceid', $invoiceid)->first();
        if($inv->$stat == NULL){
            $d = $date;
            DB::table('damage')->where('invoiceid', $invoiceid)->update([
                $stat=>$date
            ]);
        }
        else{
            $d = NULL;
            DB::table('damage')->where('invoiceid', $invoiceid)->update([
                $stat=>NULL
            ]);
        }
        $res = [
            'date'=>$d,
            'invoiceid'=>$invoiceid,
            'stat'=>$stat
        ];
        
        return response()->json($res); 
    }
    public function deleteticket($invoice){
        DB::table('damage')->where('invoiceid', $invoice)->delete();
        return redirect('/user/tickets');
    }
}
