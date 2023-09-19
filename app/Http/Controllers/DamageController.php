<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DamageController extends Controller
{
    public function tickets(Request $request){
        $result['data'] = DB::table('damage')->orderBy('date', 'DESC')->groupBy('invoiceid')->get();
        return view('damage/tickets', $result);
    }
    public function editticket(Request $request, $id){
        $result['data'] = DB::table('damage')->where('invoiceid', $id)->groupBy('item')->get();
        return view('damage/editticket', $result);
    }
    public function addtkt(Request $request){
        $result['data'] = DB::table('products')->orderBy('category', 'DESC')->orderBy('ordernum', 'ASC')->get();
        return view('damage/createticket', $result);
    }
    public function addtkt_pro(Request $request){
        $name = $request->post('name');
        $date = $request->post('date');
        $customer = DB::table('customers')->where('name', $name)->first();
        $invoiceid=$customer->id.time()."dmg";
        // $time = date('H:i:s');
        // $created_at = $date." ".$time;
        // $nepmonth = getNepaliMonth($created_at);
        // $nepyear = getNepaliYear($created_at);

        // if($request->session()->get('ADMIN_TYPE') == 'marketer'){
        //     $refid = $request->session()->get('ADMIN_ID');
        //     $refname = DB::table('admins')->where('id', $refid)->first()->name;
        //     $reftype = 'marketer';
        // }
        // elseif($request->session()->get('ADMIN_TYPE') == 'admin'){
        //     $refname = $customer->refname;
        //     if($refname == NULL)
        //     {
        //         $refid = NULL;
        //         $reftype = NULL;
        //     }
        //     else{
        //         $ref = DB::table('customers')->where('name', $refname)->first();
        //         if($ref == NULL)
        //         {
        //             $refid = DB::table('admins')->where('name', $refname)->first()->id;
        //             $reftype = DB::table('admins')->where('name', $refname)->first()->type;
        //         }
        //         else
        //         {
        //             $refid = DB::table('customers')->where('name', $refname)->first()->id;
        //             $reftype = DB::table('customers')->where('name', $refname)->first()->type;
        //         }
        //     }
        // }
        // else
        // {
        //     $refid = NULL;
        //     $refname = NULL;
        //     $reftype = NULL;
        // }

        $item=$request->post('item',[]);
        // $price=$request->post('price',[]);
        $category=$request->post('category',[]);
        $quantity=$request->post('quantity',[]);
        $prodid=$request->post('prodid',[]);

        for ($i=0; $i < count($item); $i++) { 
            if($quantity[$i] !== NULL && $quantity[$i] !== '0'){
                DB::table('damage')->insert([
                    'name'=>$name,
                    'cusuni_id'=>$customer->cusuni_id,
                    'invoiceid'=>$invoiceid,
                    'item'=>$item[$i],
                    'produni_id'=>$prodid[$i],
                    'category'=>$category[$i],
                    // 'price'=>$price[$i],
                    'date'=>$date,
                    'quantity'=>$quantity[$i],
                    // 'approvedquantity'=>'0',
                    // 'mainstatus'=>'blue',
                    // 'status'=>'pending',
                    // 'created_at'=>$created_at,
                    // 'refname'=>$refname,
                    // 'refid'=>$refid,
                    // 'reftype'=>$reftype,
                    // 'nepmonth'=>$nepmonth,
                    // 'nepyear'=>$nepyear,
                ]);
            }
        }

        return redirect('/tickets');
    }
    public function edittkt_pro(Request $request){
        // dd($request->post());
        $invoice = $request->post('invoice');
        $date = $request->post('date');
        $name = $request->post('name');
        $cusuni_id = $request->post('cusid');
        $item = $request->post('prod', []);
        $prodid = $request->post('prodid', []);
        $quantity = $request->post('quantity', []);
        $cusremarks = $request->post('cusremarks', []);
        $dqty = $request->post('dqty', []);
        $condition = $request->post('condition', []);
        $warranty = $request->post('warranty', []);
        $problem = $request->post('problem', []);
        $solution = $request->post('solution', []);
        $pop = $request->post('pop', []);
        $adremarks = $request->post('adremarks', []); 
        $category = $request->post('category', []); 
        $wproof = $request->post('warrantyproof', []); 
        $batch = $request->post('batch', []); 

        DB::table('damage')->where('invoiceid', $invoice)->delete();

        for ($i=0; $i < count($dqty); $i++) { 
            if ($dqty[$i] > 0) {
                DB::table('damage')->insert([
                    'date'=>$date,
                    'invoiceid'=>$invoice,
                    'name'=>$name,
                    'cusuni_id'=>$cusuni_id,
                    'item'=>$item[$i],
                    'produni_id'=>$prodid[$i],
                    'quantity'=>$quantity[$i],
                    'grpqty'=>$dqty[$i],
                    'cusremarks'=>$cusremarks[$i],
                    'adremarks'=>$adremarks[$i],
                    'problem'=>$problem[$i],
                    'solution'=>$solution[$i],
                    'condition'=>$condition[$i],
                    'warranty'=>$warranty[$i],
                    'category'=>$category[$i],
                    'pop'=>implode("|", $pop[$i]),
                    'warrantyproof'=>$wproof[$i],
                    'batch'=>$batch[$i],
                ]);
            }
        }

        return redirect('/tickets');
    }
    public function updatemap($invoiceid, $stat){
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
}
