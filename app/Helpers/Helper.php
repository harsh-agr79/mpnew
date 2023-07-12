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