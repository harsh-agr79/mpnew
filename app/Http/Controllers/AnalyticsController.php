<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function mainanalytics(Request $request){
        if($request->get('date') && $request->get('date2'))
        {
            $date = $request->get('date');
            $date2 = $request->get('date2');
        }
        elseif($request->get('date')){
            $date = $request->get('date');
            $date3 = date('Y-10-18');
            $date2 = date('Y-m-d', strtotime($date3. ' + 1 year -1 day'));
        }
        elseif($request->get('date2')){
            $date2 = $request->get('date2');
            $date = date('Y-10-18');
        }
        elseif($request->get('clear')){
             if(date('Y-m-d') < date('Y-10-18') ){
             $date2 = date('Y-10-17');  
             $date = date('Y-m-d', strtotime($date2. ' -1 year +1 day'));
            }
            else{
                $date = date('Y-10-18');
                $date2 = date('Y-m-d', strtotime($date. ' + 1 year -1 day'));
            }
        }
        else{
            if(date('Y-m-d') < date('Y-10-18') ){
             $date2 = date('Y-10-17');  
             $date = date('Y-m-d', strtotime($date2. ' -1 year +1 day'));
            }
            else{
                $date = date('Y-10-18');
                $date2 = date('Y-m-d', strtotime($date. ' + 1 year -1 day'));
            }
            
        }
        $result['date'] = $date;
        $result['date2'] = date('Y-m-d', strtotime($date2. ' +1 day'));
        $date2 = date('Y-m-d', strtotime($date2. ' +1 day'));

        $result['totalsales'] = DB::table('orders')
        ->where(['deleted'=>NULL, 'save'=>NULL])
        ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
        ->where('orders.created_at', '>=', $date)
        ->where('orders.created_at', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->selectRaw('*,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
        ->groupBy('deleted')
        ->get();
        $result['catsales'] = DB::table('orders')
        ->where(['deleted'=>NULL, 'save'=>NULL])
        ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
        ->where('orders.created_at', '>=', $date)
        ->where('orders.created_at', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
        ->groupBy('category')
        ->orderBy('samt','DESC')
        ->get();

        $result['pb'] = DB::table('products')
        ->where(['orders.category'=>'powerbank','status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
        ->where('orders.created_at', '>=', $date)
        ->where('orders.created_at', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
        ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
        ->get();

        $result['ch'] = DB::table('products')
        ->where(['orders.category'=>'charger','status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
        ->where('orders.created_at', '>=', $date)
        ->where('orders.created_at', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
        ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
        ->get();
        $result['ca'] = DB::table('products')
        ->where(['orders.category'=>'cable','status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
        ->where('orders.created_at', '>=', $date)
        ->where('orders.created_at', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
        ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
        ->get();
        $result['bt'] = DB::table('products')
        ->where(['orders.category'=>'btitem','status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
        ->where('orders.created_at', '>=', $date)
        ->where('orders.created_at', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
        ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
        ->get();
        $result['ep'] = DB::table('products')
        ->where(['orders.category'=>'earphone','status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
        ->where('orders.created_at', '>=', $date)
        ->where('orders.created_at', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
        ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
        ->get();
        $result['oth'] = DB::table('products')
        ->where(['orders.category'=>'others','status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
        ->where('orders.created_at', '>=', $date)
        ->where('orders.created_at', '<=', $date2)
        ->where(function ($query) use ($request){
            if($request->get('name')){
                $query->where('orders.name', $request->get('name'));
            }
        })
        ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
        ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
        ->get();

        if($request->get('name')){
            $result['name'] = $request->get('name');
        }
        else{
            $result['name'] = "";
        }



        return view('admin/mainanalytics', $result);
    }
}
