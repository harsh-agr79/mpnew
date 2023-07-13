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