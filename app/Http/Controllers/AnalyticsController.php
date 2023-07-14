<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function mainanalytics(Request $request){
        $result['totalsales'] = DB::table('orders')
        ->where(['deleted'=>NULL, 'save'=>NULL])
        ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
        // ->where('orders.created_at', '>=', $date)
        // ->where('orders.created_at', '<=', $date2)
        // ->where('orders.name', $cusname)
        ->selectRaw('*,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
        ->groupBy('deleted')
        ->get();
        $result['catsales'] = DB::table('orders')
        ->where(['deleted'=>NULL, 'save'=>NULL])
        ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
        // ->where('orders.created_at', '>=', $date)
        // ->where('orders.created_at', '<=', $date2)
        // ->where('orders.name', $cusname)
        ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
        ->groupBy('category')
        ->orderBy('samt','DESC')
        ->get();

        $result['pb'] = DB::table('products')
        ->where(['orders.category'=>'powerbank','status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
        // ->where('orders.created_at', '>=', $date)
        // ->where('orders.created_at', '<=', $date2)
        // ->where('orders.name', $cusname)
        ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
        ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
        ->get();

        $result['ch'] = DB::table('products')
        ->where(['orders.category'=>'charger','status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
        // ->where('orders.created_at', '>=', $date)
        // ->where('orders.created_at', '<=', $date2)
        // ->where('orders.name', $cusname)
        ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
        ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
        ->get();
        $result['ca'] = DB::table('products')
        ->where(['orders.category'=>'cable','status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
        // ->where('orders.created_at', '>=', $date)
        // ->where('orders.created_at', '<=', $date2)
        // ->where('orders.name', $cusname)
        ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
        ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
        ->get();
        $result['bt'] = DB::table('products')
        ->where(['orders.category'=>'btitem','status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
        // ->where('orders.created_at', '>=', $date)
        // ->where('orders.created_at', '<=', $date2)
        // ->where('orders.name', $cusname)
        ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
        ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
        ->get();
        $result['ep'] = DB::table('products')
        ->where(['orders.category'=>'earphone','status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
        // ->where('orders.created_at', '>=', $date)
        // ->where('orders.created_at', '<=', $date2)
        // ->where('orders.name', $cusname)
        ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
        ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
        ->get();
        $result['oth'] = DB::table('products')
        ->where(['orders.category'=>'others','status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
        // ->where('orders.created_at', '>=', $date)
        // ->where('orders.created_at', '<=', $date2)
        // ->where('orders.name', $cusname)
        ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
        ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
        ->get();



        return view('admin/mainanalytics', $result);
    }
}
