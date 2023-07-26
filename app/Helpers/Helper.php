<?php

use Illuminate\Support\Facades\DB;

function getpstat($orderid){
    $order = DB::table('orders')->where('orderid', $orderid)->get();
    $tc = count($order);
    $cc = 0;
    $rc = 0;
    foreach($order as $item){
        if($item->status == 'approved'){
            $cc = $cc + 1;
        }
        elseif ($item->status == 'rejected') {
            $cc = $cc + 1;
            $rc = $rc + 1;
        }
    }
    if($order[0]->delivered == 'on'){
        $result = 'green';
    }
    elseif($order[0]->clnstatus == 'packorder'){
        $result = 'deep-purple';
    }
    elseif($tc == $cc && $tc == $rc) {
        $result = 'red';
    }
    elseif($tc == $cc){
        $result = 'amber darken-1';
    }
    else{
        $result = 'blue';
    }
    return $result;
}

function getTotalAmount($orderid){
    $orders = DB::table('orders')->where('orderid', $orderid)->get();
    $ts = 0;
    foreach($orders as $item){
        if($item->status == 'pending'){
            $ts = $ts + ($item->quantity * $item->price);
        }
        else{
            $ts = $ts + ($item->approvedquantity * $item->price);
        }
    }
    $tsd = $ts - ($ts * 0.01 * $orders[0]->discount);
    return $tsd;
}
function updateMainStatus($orderid){
    $order = DB::table('orders')->where('orderid', $orderid)->get();
    $tc = count($order);
    $cc = 0;
    $rc = 0;
    foreach($order as $item){
        if($item->status == 'approved'){
            $cc = $cc + 1;
        }
        elseif ($item->status == 'rejected') {
            $cc = $cc + 1;
            $rc = $rc + 1;
        }
    }
    if($order[0]->delivered == 'on'){
        $result = 'green';
    }
    elseif($order[0]->clnstatus == 'packorder'){
        $result = 'deep-purple';
    }
    elseif($tc == $cc && $tc == $rc) {
        $result = 'red';
    }
    elseif($tc == $cc){
        $result = 'amber darken-1';
    }
    else{
        $result = 'blue';
    }
    DB::table('orders')->where('orderid', $orderid)->update([
        'mainstatus'=>$result,
    ]);
}

function money($money){
    $decimal = (string)($money - floor($money));
    $money = floor($money);
    $length = strlen($money);
    $m = '';
    $money = strrev($money);
    for($i=0;$i<$length;$i++){
        if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$length){
            $m .=',';
        }
        $m .=$money[$i];
    }
    $result = strrev($m);
    $decimal = preg_replace("/0\./i", ".", $decimal);
    $decimal = substr($decimal, 0, 3);
    // if( $decimal != '0'){
    // $result = $result.$decimal;
    // }
    return $result;
}
function getbalance($name)
{
    $payment = DB::table('payments')
        ->where('deleted', null)
        ->where('name', $name)
        ->selectRaw('*, SUM(amount) as sum')
        ->groupBy('name')
        ->first();
    $order = DB::table('orders')
        ->where(['deleted' => null, 'status' => 'approved', 'save' => null, 'name' => $name])
        ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount * 0.01 * approvedquantity * price) as dis')
        ->where('status', 'approved')
        ->groupBy('name')
        ->first();
    $slr = DB::table('salesreturns')
        ->where('name', $name)
        ->selectRaw('*, SUM(quantity * price) as sum, SUM(discount * 0.01 * quantity * price) as dis')
        ->groupBy('name')
        ->first();
    $exp = DB::table('expenses')
        ->where('name', $name)
        ->selectRaw('*, SUM(amount) as sum')
        ->groupBy('name')
        ->first();
    $cus = DB::table('customers')->where('name', $name)->first();

    if($cus->obtype == 'debit'){
        $od = $cus->openbalance;
        $oc = 0;
    }
    elseif ($cus->obtype == 'credit') {
        $od = 0;
        $oc = $cus->openbalance;
    }
    else{
        $od = 0;
        $oc = 0;
    }
    if($order!=NULL){
        $or = $order->sum-$order->dis;
    }
    else{
        $or = 0;
    }
    if($exp != NULL){
        $ex = $exp->sum;
    }
    else{
        $ex = 0;
    }
    if($slr != NULL){
        $sr = $slr->sum-$slr->dis;
    }
    else{
        $sr = 0;
    }
    if($payment!=NULL){
        $py = $payment->sum;
    }
    else{
        $py = 0;
    }

    $tdb = $od+$or+$ex;
    $tcb = $oc+$py+$sr;

    if($tdb > $tcb){
        $result = array('red', $tdb-$tcb);
        return $result;
    }
    elseif($tdb < $tcb){
        $result = array('green', $tcb-$tdb);
        return $result;
    }
    else{
        $result = array('green', 0);
        return $result;
    }
}

function getOSamt($name){
    $balance = explode("|", DB::table('customers')->where('name', $name)->first()->balance);
    $thirdays = DB::table('orders')
                            ->where(['deleted' => null, 'save' => null])
                            ->where('name', $name)
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
        $result1 = array('red lighten-5', $balance[1] - $tdy);
    }
    else{
        $result1 = array('green lighten-5', 0);
    }
    $fourdays = DB::table('orders')
                            ->where(['deleted' => null, 'save' => null])
                            ->where('name', $name)
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
        $result2 = array('red lighten-5', $balance[1] - $fdy);
    }
    else{
        $result2 = array('green lighten-5', 0);
    }

    $data = array(); 
    $data[] = [
        'tdyt'=>$result1[0],
        'tdy'=>$result1[1],
        'fdyt'=>$result2[0],
        'fdy'=>$result2[1]
    ];
    return $data;
}
function marketercuslist($id){
    $data = DB::table('customers')->where('refid',$id)->pluck('name')->toArray();
    return $data;
}