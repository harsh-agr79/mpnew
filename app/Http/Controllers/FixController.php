<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FixController extends Controller
{
    public function update(){
        // $orders = DB::table('orders')->groupBy('orderid')->orderBy('id', 'DESC')->where('mainstatus', NULL)->get();
        // foreach($orders as $item){
        //     DB::table('orders')->where('orderid', $item->orderid)->update([
        //         'mainstatus'=>getpstat($item->orderid)
        //     ]);
        // }
        // $customers = DB::table('customers')->get();
        // foreach($customers as $item){
        //     $bal = getbalance($item->name);
        //     DB::table('customers')->where('id', $item->id)->update([
        //         'balance'=>implode("|",$bal)
        //     ]);
        // }

        foreach( DB::table('customers')->orderBy('id', 'ASC')->lazy() as $item){
            $balance = explode("|",$item->balance);
            // $balance = explode("|", DB::table('customers')->where('name', $item->name)->first()->balance);
            $thirdays = DB::table('orders')
                                    ->where(['deleted' => null, 'save' => null])
                                    ->where('name', $item->name)
                                    ->where('status', 'approved')
                                    ->whereBetween('created_at', [now()->subDays(30), now()->addDays(1)])
                                    ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')
                                    ->groupBy('name')
                                    ->first();
        
            if($thirdays != NULL){
                $tdy = $thirdays->sum-$thirdays->dis;
            }
            else{
                $tdy = 0;
            }
            if($balance[1] > $tdy){
                $result1 =$balance[1] - $tdy;
                $r1clr = 'red lighten-5';
            }
            else{
                $result1 = 0;
                $r1clr = 'green lighten-5';
            }
            $fourdays = DB::table('orders')
                                    ->where(['deleted' => null, 'save' => null])
                                    ->where('name', $item->name)
                                    ->where('status', 'approved')
                                    ->whereBetween('created_at', [now()->subDays(45), now()->addDays(1)])
                                    ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')
                                    ->groupBy('name')
                                    ->first();
        
            if($fourdays != NULL){
                $fdy = $fourdays->sum-$fourdays->dis;
            }
            else{
                $fdy = 0;
            }
            if($balance[1] > $fdy){
                $result2 = $balance[1] - $fdy;
                $r2clr = 'red lighten-5';
            }
            else{
                $result2 = 0;
                $r2clr = 'green lighten-5';
            }
             $sixdays = DB::table('orders')
                        ->where(['deleted' => null, 'save' => null])
                        ->where('name', $item->name)
                        ->where('status', 'approved')
                        ->whereBetween('created_at', [now()->subDays(60), now()->addDays(1)])
                        ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')
                        ->groupBy('name')
                        ->first();
        
            if($sixdays != NULL){
                $sdy = $sixdays->sum-$sixdays->dis;
            }
            else{
                $sdy = 0;
            }
            if($balance[1] > $sdy){
                $result3 = $balance[1] - $sdy;
                $r3clr = 'red lighten-5';
            }
            else{
                $result3 = 0;
                $r3clr = 'green lighten-5';
            }
             $nindays = DB::table('orders')
                        ->where(['deleted' => null, 'save' => null])
                        ->where('name', $item->name)
                        ->where('status', 'approved')
                        ->whereBetween('created_at', [now()->subDays(90), now()->addDays(1)])
                        ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')
                        ->groupBy('name')
                        ->first();
        
            if($nindays != NULL){
                $ndy = $nindays->sum-$nindays->dis;
            }
            else{
                $ndy = 0;
            }
            if($balance[1] > $ndy){
                $result4 = $balance[1] - $ndy;
                $r4clr = 'red lighten-5';
            }
            else{
                $result4 = 0;
                $r4clr = 'green lighten-5';
            }
            DB::table('customers')->where('id', $item->id)->update([
                'thirdays'=>$result1,
                'fourdays'=>$result2,
                'sixdays'=>$result3,
                'nindays'=>$result4,
            ]);
        }
        // echo 'done';
    }
    public function encryptpass(){
        $admins = DB::table('admins')->get();

        foreach($admins as $item){
            DB::table('admins')->where('id', $item->id)->update([
                'password'=>Hash::make($item->password)
                ]);
        }

        $customers = DB::table('customers')->get();

        foreach($customers as $item){
            DB::table('customers')->where('id', $item->id)->update([
                'password'=>Hash::make($item->password)
                ]);
        }
    }
}
