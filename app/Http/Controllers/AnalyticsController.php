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

        foreach($result['catsales'] as $item){
            $result['data'.$item->category] = DB::table('products')
            ->where(['orders.category'=>$item->category,'status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
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
            $result['data2'.$item->category] = DB::table('products')
            ->where(['category'=>$item->category])
            ->whereNotIn('produni_id', DB::table('orders')
            ->where(['category'=>$item->category,'status'=>'approved','deleted'=>NULL, 'save'=>NULL])
            ->where('created_at', '>=', $date)
            ->where('created_at', '<=', $date2)
            ->where(function ($query) use ($request){
                if($request->get('name')){
                    $query->where('orders.name', $request->get('name'));
                }
            })
            ->pluck('produni_id')
            ->toArray())
            ->get();
        }

        if($request->get('name')){
            $result['name'] = $request->get('name');
        }
        else{
            $result['name'] = "";
        }



        return view('admin/mainanalytics', $result);
    }

    public function sortanalytics(Request $request){
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

        if ($request->get('name') && $request->get('product')) {
            $result['npdata'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->where('status', 'approved')
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->where('orders.name', $request->get('name'))
            ->where('orders.item', $request->get('product'))
            ->orderBy('created_at', 'DESC')
            ->get();

            $result['nptotal'] =  DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->where('status', 'approved')
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->where('orders.name', $request->get('name'))
            ->where('orders.item', $request->get('product'))
            ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
            ->groupBy('name')
            ->get();

            $result['datatype'] = 'np';
            $result['name'] = $request->get('name');
            $result['product'] = $request->get('product');
        }
        elseif ($request->get('name')) {
            $result['totalsales'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->where('orders.name', $request->get('name'))
            ->selectRaw('*,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
            ->groupBy('deleted')
            ->get();
            
            $result['catsales'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->where('orders.name', $request->get('name'))
            ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
            ->groupBy('category')
            ->orderBy('samt','DESC')
            ->get();

            foreach($result['catsales'] as $item){
                $result['data'.$item->category] = DB::table('products')
                ->where(['orders.category'=>$item->category,'status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
                ->where('orders.created_at', '>=', $date)
                ->where('orders.created_at', '<=', $date2)
                ->where('orders.name', $request->get('name'))
                ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
                ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
                ->get();
                $result['data2'.$item->category] = DB::table('products')
                ->where(['category'=>$item->category])
                ->whereNotIn('produni_id', DB::table('orders')
                ->where(['category'=>$item->category,'status'=>'approved','deleted'=>NULL, 'save'=>NULL])
                ->where('created_at', '>=', $date)
                ->where('created_at', '<=', $date2)
                ->where('orders.name', $request->get('name'))
                ->pluck('produni_id')
                ->toArray())
                ->get();
            }
            $result['datatype'] = 'n';
            $result['name'] = $request->get('name');
            $result['product'] = '';
        }
        elseif ($request->get('product')){
            $result['pdata'] = DB::table('orders')
            ->where('status', 'approved')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->where('orders.item', $request->get('product'))
            ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->orderBy('sum','desc')
            ->groupBy('orders.name')
            ->get();
            $result['ptotal'] =  DB::table('orders')
            ->where('status', 'approved')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->where('orders.item', $request->get('product'))
            ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
            ->groupBy('item')
            ->get();
            $result['pnodata'] = DB::table('customers')
            ->where('deleted', NULL)
            ->whereNotIn('name', DB::table('orders')
                            ->where('status', 'approved')
                            ->where(['deleted'=>NULL, 'save'=>NULL])
                            ->where('orders.created_at', '>=', $date)
                            ->where('orders.created_at', '<=', $date2)
                            ->where('orders.item', $request->get('product'))
                            ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->orderBy('sum','desc')
                            ->groupBy('orders.name')->pluck('name')->toArray())
            ->get();

            $result['datatype'] = 'p';
            $result['name'] = '';
            $result['product'] = $request->get('product');
        }
        else{
            $result['datatype'] = 'nodata';
            $result['name'] = '';
            $result['product'] = '';
        }

        return view('admin/sortanalytics', $result);
    }
}
