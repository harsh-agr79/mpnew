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
        $date = date('Y-m-d H:i:s');
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
                    'category_id'=>$category[$i],
                    'category'=>DB::table("categories")->where('id', $category[$i])->first()->category,
                    'instatus'=>'pending',
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

        ticketstat($invoiceid);
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
        $stat = $request->post('stat', []);
        $check = $request->post('check', []);
        $cnt = array_count_values($check);

        // print_r($cnt);
        DB::table('damage')->where('invoiceid', $invoice)->delete();

        for ($i=0; $i < count($dqty); $i++) { 
            if ($check[$i] != 'dup') {
                if($cnt[$prodid[$i]] == 1 || $dqty[$i] > 0){
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
                        'category_id'=>$category[$i],
                        'category'=>DB::table("categories")->where('id', $category[$i])->first()->category,
                        'pop'=>implode("|", $pop[$i]),
                        'warrantyproof'=>$wproof[$i],
                        'batch'=>$batch[$i],
                        'instatus'=>$stat[$i],
                        'sendbycus'=>$request->post('sendbycus'),
                        'recbycomp'=>$request->post('recbycomp'),
                        'sendbackbycomp'=>$request->post('sendbackbycomp'),
                        'recbycus'=>$request->post('recbycus'),
                    ]);
                } 
            }
        }

        ticketstat($invoice);
        return redirect('/tickets');
    }
    public function updatemap($invoiceid, $stat){
        $date = date('Y-m-d H:i:s');
        $inv = DB::table('damage')->where('invoiceid', $invoiceid)->first();
        $s = NULL;
        if($inv->$stat == NULL){
            $d = $date;
            DB::table('damage')->where('invoiceid', $invoiceid)->update([
                $stat=>$date
            ]);
           
            if($stat == 'recbycomp'){
                $s = 'in progress';
                DB::table('damage')->where('invoiceid', $invoiceid)->update([
                    // 'instatus'=>'in progress',
                    'mainstatus'=>'in progress'
                ]); 
            }
        }
        else{
            $d = NULL;
            DB::table('damage')->where('invoiceid', $invoiceid)->update([
                $stat=>NULL
            ]);
           
            if($stat == 'recbycomp'){
                $s = 'pending';
                DB::table('damage')->where('invoiceid', $invoiceid)->update([
                    // 'instatus'=>'pending',
                    'mainstatus'=>'pending'
                ]); 
            }
        }
        $res = [
            'date'=>$d,
            'invoiceid'=>$invoiceid,
            'stat'=>$stat,
            'mainstat'=>$s
        ];
        
        return response()->json($res);
    }

    public function deleteticket($invoice){
        DB::table('damage')->where('invoiceid', $invoice)->delete();
        return redirect('/tickets');
    }
    public function getitdetails($item, $inv){
        $chk = DB::table('damage')->where('item', $item)->where('invoiceid',$inv)->get();
        if(count($chk)>0){
            $res = 'Already Exists';
        }
        else{
            $prod = DB::table('products')->where('name', $item)->first();
            $batch = DB::table('batch')->where('product', $item)->pluck('batch')->toArray();
            $problem = DB::table('problem')->where('category', $prod->category)->pluck('problem')->toArray();
            $res = [
                'prod'=>$prod,
                'batch'=>$batch,
                'problem'=>$problem
            ];
        }
        return response()->json($res);
    }
    public function ticketdetail($invoice){
        $result['data'] = DB::table('damage')->where('invoiceid', $invoice)->groupBy('item')->get();
        return view('damage/ticketdetail', $result);
    }
    public function changedate(Request $request){
        $inv = $request->post('inv');
        $stat = $request->post('stat');
        $date = $request->post('date');

        DB::table('damage')->where('invoiceid', $inv)->update([
            $stat=>$date
        ]);
        $res = [
            'inv'=>$inv,
            'stat'=>$stat,
            'date'=>date('Y-m-d H:i:s', strtotime($date))
        ];
        
        return response()->json($res);
    }
}
