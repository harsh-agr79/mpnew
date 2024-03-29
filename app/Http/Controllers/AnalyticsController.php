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
        $result['date2'] = $date2;
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
            $result['data'][$item->category] = DB::table('products')
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
            $result['data2'][$item->category] = DB::table('products')
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
        $result['date2'] = $date2;
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
            ->where(['orders.deleted'=>NULL, 'save'=>NULL])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->where('orders.item', $request->get('product'))
            ->selectRaw('orders.*, customers.type, customers.contact, customers.actcolor, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')
            ->orderBy('sum','desc')
            ->join('customers',  'orders.cusuni_id', '=', 'customers.cusuni_id')
            // ->select('orders.*', 'customers.type')
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

    public function detailedreport(Request $request){
        $year = getNepaliYear(today());

        if($request->get('startyear')){
            $result['syear'] = $request->get('startyear');
        }
        else
        {
            $result['syear'] = $year;
        }
        if($request->get('endyear')){
            $result['eyear'] = $request->get('endyear');
        }
        else
        {
            $result['eyear'] = $year;
        }
        if($request->get('startmonth')){
            $result['smonth'] = $request->get('startmonth');
        }
        else
        {
            $result['smonth'] = "1";
        }
        if($request->get('endmonth')){
            $result['emonth'] = $request->get('endmonth');
        }
        else
        {
            $result['emonth'] = getNepaliMonth(today());
        }
        $date = getEnglishDate($result['syear'] ,  $result['smonth'], 1);
        $date2 = getEnglishDate($result['eyear'] , $result['emonth'],getLastDate($result['emonth'] , date('y', strtotime($result['eyear'] ))));
        if($request->get('name')){
            $result['data'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$request->get('name')])
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
            ->groupBy(['nepmonth', 'nepyear'])
            ->get();

            $result['fquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$request->get('name')])
            ->whereIn('nepmonth', [1,2,3])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();
        
            $result['squat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$request->get('name')])
            ->whereIn('nepmonth', [4,5,6])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['tquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$request->get('name')])
            ->whereIn('nepmonth', [7,8,9])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['frquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$request->get('name')])
            ->whereIn('nepmonth', [10,11,12])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['fifdays'] = DB::table('orders')
            ->where(['deleted'=>NULL])
            ->where('name', $request->get('name'))
            ->whereBetween('created_at', [now()->subDays(15), now()])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')->groupBy('name')
            ->get();

            $result['thirdays'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->where('name', $request->get('name'))
            ->where('status','approved') 
            ->whereBetween('created_at', [now()->subDays(30), now()->addDays(1)])
            ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')->groupBy('name')
            ->get();

            $result['fourdays'] = DB::table('orders')
            ->where(['deleted'=>NULL])
            ->where('name',$request->get('name'))
            ->whereBetween('created_at', [now()->subDays(45), now()->addDays(1)])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')->groupBy('name')
            ->get();

            $result['sixdays'] = DB::table('orders')
            ->where(['deleted'=>NULL])
            ->where('name', $request->get('name'))
            ->whereBetween('created_at', [now()->subDays(60), now()->addDays(1)])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')->groupBy('name')
            ->get();
            $result['nindays'] = DB::table('orders')
            ->where(['deleted'=>NULL])
            ->where('name', $request->get('name'))
            ->whereBetween('created_at', [now()->subDays(90), now()->addDays(1)])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')->groupBy('name')
            ->get();
            
            $result['custs'] = 'no data';
            $result['name'] = $request->get('name');
        }
        else{
            $result['data'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
            ->orderBy('created_at', 'ASC')
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy(['nepmonth', 'nepyear'])
            ->get();

            $result['fquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
            ->whereIn('nepmonth', [1,2,3])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['squat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
            ->whereIn('nepmonth', [4,5,6])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['tquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
            ->whereIn('nepmonth', [7,8,9])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['frquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
            ->whereIn('nepmonth', [10,11,12])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $date = getEnglishDate($result['syear'] ,  $result['smonth'], 1);
            $date2 = getEnglishDate($result['eyear'] , $result['emonth'],getLastDate($result['emonth'] , date('y', strtotime($result['eyear'] ))));

            $result['tss'] = DB::table('orders')
            ->where('deleted', NULL)->where('save', NULL)->where('status', 'approved') 
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
            ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')
            ->groupBy('deleted')
            ->get();

            $result['custs'] = DB::table('orders')->where('orders.deleted', NULL)->where('save', NULL)->where('status', 'approved') 
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
            ->selectRaw('orders.*, customers.type, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')
            ->groupBy('orders.name')
            ->join('customers', 'orders.cusuni_id', '=', 'customers.cusuni_id')
            ->orderBy('sum', 'DESC')
            ->get();

            $result['cusnts'] = DB::table('customers')->whereNotIn('name', DB::table('orders')->where('deleted', NULL)->where('save', NULL)->where('status', 'approved') 
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
            ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')->groupBy('name')->pluck('name')->toArray())
            ->get();


            $result['name'] = "";
        }      
        return view ('admin/detailedreport', $result);
    }


    public function statement(Request $request){
        $result['data'] = DB::table('customers')->where('deleted', NULL)->orderBy('name', 'ASC')->get();

        return view('admin/statement', $result);
    }
    public function balancesheet(Request $request, $name){

        $cust = DB::table('customers')->where('name', $name)->first();
        $result['cus'] = $cust;
        $today = date('Y-m-d');
         $target = DB::table('target')->where('userid',$cust->user_id)
         ->where('startdate', '<=', $today)
         ->where('enddate', '>=', $today)
         ->get();

         if(count($target) > 0){
            $rdate = $target['0']->startdate;
            $rdate2 = $target['0']->enddate;

            if($request->get('date') && $request->get('date2'))
                {
                    $date = $request->get('date');
                    $date2 = $request->get('date2');
                }
                elseif($request->get('date')){
                    $date = $request->get('date');
                    $date2 = $rdate2;
                }
                elseif($request->get('date2')){
                    $date2 = $request->get('date2');
                    $date = $rdate2;
                }
                elseif($request->get('clear')){
                    $date = $rdate;
                    $date2 = $rdate2;
                 }
                else{
                    $date = $rdate;
                    $date2 = $rdate2;
                }

         }
         else{
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
         }
        $result['date'] = $date;
        $result['date2'] = $date2;
        $date2 = date('Y-m-d', strtotime($date2. ' +1 day'));

        $result['oldorders'] = DB::table('orders')
        ->where(['deleted'=>NULL])
        ->where('created_at', '<', $date)
        ->where('name',$name)
        ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount * 0.01 * approvedquantity * price) as dis')->groupBy('name')->where('status','approved') 
        ->get();

        $result['oldpayments'] = DB::table('payments')
        ->where(['deleted'=>NULL])
        ->where('date', '<', $date)
        ->where('name',$name)
        ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
        ->get();

        $result['oldslr'] = DB::table('salesreturns')
           ->where('name', $name)
           ->where('date', '<', $date)
           ->selectRaw('*, SUM(quantity * price) as sum, SUM(discount*0.01 * quantity * price) as dis')->groupBy('name') 
           ->get();
           
       $result['oldexp'] = DB::table('expenses')
           ->where('name', $name)
           ->where('date', '<', $date)
           ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
           ->get();

           $result['cuorsum'] = DB::table('orders')
           ->where(['deleted'=>NULL, 'save'=>NULL])
           ->where('name', $name)
           ->where('created_at', '>=', $date)
           ->where('created_at', '<=', $date2)
           ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')->groupBy('name')->where('status','approved') 
           ->get();

           $result['cupysum'] = DB::table('payments')
           ->where('deleted',NULL)
           ->where('name', $name)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
           ->get();

           $result['cuslrsum'] = DB::table('salesreturns')
           ->where('name', $name)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(quantity * price) as sum, SUM(discount*0.01 * quantity * price) as dis')->groupBy('name') 
           ->get();
           
            $result['cuexsum'] = DB::table('expenses')
           ->where('name', $name)
           ->where('date', '>=', $date)
           ->where('date', '<=', $date2)
           ->selectRaw('*, SUM(amount) as sum')->groupBy('name') 
           ->get();

        $orders = DB::table('orders')
        ->where(['deleted'=>NULL, 'save'=>null])
        ->where('created_at', '>=', $date)
        ->where('created_at', '<=', $date2)
        ->where('status','approved')
        ->where('name',$name)
        ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount * 0.01 * approvedquantity * price) as dis')->groupBy('orderid') 
        ->orderBy('orders.created_at','desc')
        ->get();

        $payments = DB::table('payments')->where('name', $name)
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->where('deleted',NULL)->get();

        $slrs = DB::table('salesreturns')
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->selectRaw('*, SUM(quantity * price) as sum, SUM(discount * 0.01 * quantity * price) as dis')->groupBy('returnid')->where('name',$name) 
        ->orderBy('date','desc')
        ->get();
        
        $exp = DB::table('expenses')
        ->where('date', '>=', $date)
        ->where('date', '<=', $date2)
        ->selectRaw('*, SUM(amount) as sum')->where('name', $name)
        ->orderBy('date', 'desc')
        ->get();

        $data = array();
        foreach($orders as $item){
            if($item->name == NULL){

            }
            else{
            $data[] = [
                'name'=>$item->name,
                'created'=>$item->created_at,
                'ent_id'=>$item->orderid,
                'debit'=>$item->sum - $item->dis,
                'nar'=>$item->remarks,
                'vou'=>'',
                'credit'=>'0',
                'type'=>'sale',
            ];}
        }
        foreach($payments as $item){
            if($item->name == NULL){

            }
            else{
            $data[] = [
                'name'=>$item->name,
                'created'=>$item->date,
                'ent_id'=>$item->paymentid,
                'id'=>$item->id,
                'debit'=>'0',
                'nar'=>'',
                'vou'=>$item->voucher,
                'credit'=>$item->amount,
                'type'=>'payment',
            ];}
        }
        foreach($slrs as $item){
            if($item->name == NULL){

            }
            else{
            $data[] = [
                'name'=>$item->name,
                'created'=>$item->date,
                'ent_id'=>$item->returnid,
                'debit'=>'0',
                'nar'=>'',
                'vou'=>'',
                'credit'=>$item->sum - $item->dis,
                'type'=>'Sales Return',
            ];}
        }
        foreach($exp as $item){
            if($item->name == NULL){

            }
            else{
                $data[] = [
                    'name'=>$item->name,
                    'created'=>$item->date,
                    'ent_id'=>$item->expenseid,
                    'id'=>$item->id,
                    'debit'=>$item->amount,
                    'nar'=>'',
                    'vou'=>$item->particular,
                    'credit'=>'0',
                    'type'=>'expense',
                ];
            }   
        }
            usort($data, function($a, $b) {
                return strtotime($a['created']) - strtotime($b['created']);
            });

        $result['data'] = collect($data);
        return view('admin/balancesheet', $result);
    }

    public function getref(){
        $c = DB::table('customers')->get();
        $m = DB::table('admins')->where('type', 'marketer')->get();
        
        $result = $c->merge($m);
        return response()->json($result);
    }

    public function refstatement(Request $request){
        if($request->get('name')){
            $result['data'] = DB::table('customers')->where('refname', $request->get('name'))->get();
            $result['name2'] = $request->get('name');
            $result['name'] = '';

            $year = getNepaliYear(today());
    
            if($request->get('startyear')){
                $result['syear'] = $request->get('startyear');
            }
            else
            {
                $result['syear'] = $year;
            }
            if($request->get('endyear')){
                $result['eyear'] = $request->get('endyear');
            }
            else
            {
                $result['eyear'] = $year;
            }
            if($request->get('startmonth')){
                $result['smonth'] = $request->get('startmonth');
            }
            else
            {
                $result['smonth'] = "1";
            }
            if($request->get('endmonth')){
                $result['emonth'] = $request->get('endmonth');
            }
            else
            {
                $result['emonth'] = getNepaliMonth(today());
            }
            $ref = DB::table('admins')->where('name', $request->get('name'))->first();
            $cuslist = marketercuslist($ref->id);

            $date = getEnglishDate($result['syear'] ,  $result['smonth'], 1);
            $date2 = getEnglishDate($result['eyear'] , $result['emonth'],getLastDate($result['emonth'] , date('y', strtotime($result['eyear'] ))));
            
                $result['data2'] = DB::table('orders')
                ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
                ->orderBy('created_at', 'ASC')
                ->whereIn('orders.name', $cuslist)
                ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
                ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
                ->groupBy(['nepmonth', 'nepyear'])
                ->get();
    
                $result['fquat'] = DB::table('orders')
                ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
                ->whereIn('nepmonth', [1,2,3])
                ->orderBy('created_at', 'ASC')
                ->whereIn('orders.name', $cuslist)
                ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
                ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
                ->groupBy('nepyear')
                ->get();
    
                $result['squat'] = DB::table('orders')
                ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
                ->whereIn('nepmonth', [4,5,6])
                ->orderBy('created_at', 'ASC')
                ->whereIn('orders.name', $cuslist)
                ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
                ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
                ->groupBy('nepyear')
                ->get();
    
                $result['tquat'] = DB::table('orders')
                ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
                ->whereIn('nepmonth', [7,8,9])
                ->orderBy('created_at', 'ASC')
                ->whereIn('orders.name', $cuslist)
                ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
                ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
                ->groupBy('nepyear')
                ->get();
    
                $result['frquat'] = DB::table('orders')
                ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
                ->whereIn('nepmonth', [10,11,12])
                ->orderBy('created_at', 'ASC')
                ->whereIn('orders.name', $cuslist)
                ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)  
                ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
                ->groupBy('nepyear')
                ->get();

                $result['tss'] = DB::table('orders')
                ->where('deleted', NULL)->where('save', NULL)->where('status', 'approved') 
                ->where('orders.created_at', '>=', $date)
                ->where('orders.created_at', '<=', $date2)  
                ->whereIn('orders.name', $cuslist)
                ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')
                ->groupBy('deleted')
                ->get();
    
                $result['custs'] = DB::table('orders')->where('orders.deleted', NULL)->where('save', NULL)->where('status', 'approved') 
                ->where('orders.created_at', '>=', $date)
                ->where('orders.created_at', '<=', $date2)  
                ->whereIn('orders.name', $cuslist)
                ->selectRaw('orders.*, customers.type, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')
                ->groupBy('orders.name')
                ->join('customers', 'orders.cusuni_id', '=', 'customers.cusuni_id')
                ->orderBy('sum', 'DESC')
                ->get();
    
                $result['cusnts'] = DB::table('customers')->whereNotIn('name', DB::table('orders')->where('deleted', NULL)->where('save', NULL)->where('status', 'approved') 
                ->where('orders.created_at', '>=', $date)
                ->where('orders.created_at', '<=', $date2)  
                ->whereIn('orders.name', $cuslist)
                ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')->groupBy('name')->pluck('name')->toArray())
                ->whereIn('customers.name', $cuslist)
                ->get();

            $date = getEnglishDate($result['syear'] ,  $result['smonth'],1);
            $date2 = getEnglishDate($result['eyear'] , $result['emonth'],getLastDate($result['emonth'] , date('y', strtotime($result['eyear'] ))));

            $result['date'] = $date;
            $result['date2'] = $date2;

            $result['catsales'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->whereIn('orders.name', $cuslist)
            ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
            ->groupBy('category')
            ->orderBy('samt','DESC')
            ->get();

            foreach($result['catsales'] as $item){
                $result['pdata'.$item->category] = DB::table('products')
                ->where(['orders.category'=>$item->category,'status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
                ->where('orders.created_at', '>=', $date)
                ->where('orders.created_at', '<=', $date2)
                ->whereIn('orders.name', $cuslist)
                ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
                ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
                ->get();
                $result['pdata2'.$item->category] = DB::table('products')
                ->where(['category'=>$item->category])
                ->whereNotIn('produni_id', DB::table('orders')
                ->where(['category'=>$item->category,'status'=>'approved','deleted'=>NULL, 'save'=>NULL])
                ->where('created_at', '>=', $date)
                ->where('created_at', '<=', $date2)
                ->whereIn('orders.name', $cuslist)
                ->pluck('produni_id')
                ->toArray())
                ->get();
            }
        }
        else{
            $result['name'] = '';
            $result['name2'] = '';
            $result['data'] = 'no data';
        }

    

        return view('admin/refererstatement', $result);
    }

    public function productreport(Request $request){
        $year = getNepaliYear(today());
    
        if($request->get('startyear')){
            $result['syear'] = $request->get('startyear');
        }
        else
        {
            $result['syear'] = $year;
        }
        if($request->get('endyear')){
            $result['eyear'] = $request->get('endyear');
        }
        else
        {
            $result['eyear'] = $year;
        }
        if($request->get('startmonth')){
            $result['smonth'] = $request->get('startmonth');
        }
        else
        {
            $result['smonth'] = "1";
        }
        if($request->get('endmonth')){
            $result['emonth'] = $request->get('endmonth');
        }
        else
        {
            $result['emonth'] = getNepaliMonth(today());
        }
        $date = getEnglishDate($result['syear'] ,  $result['smonth'], 1);
        $date2 = getEnglishDate($result['eyear'] , $result['emonth'],getLastDate($result['emonth'] , date('y', strtotime($result['eyear'] ))));

        if($request->get('category')){
            $category = $request->get('category'); 
            $data = DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL, 'status'=>'approved'])
            ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->where('orders.category', $category)
            ->where(function ($query) use ($request){
                if($request->get('name')){
                    $query->where('orders.name', $request->get('name'));
                }
            })
            ->selectRaw('*, SUM(approvedquantity) as sum')->groupBy(['nepmonth', 'nepyear'])->orderBy('created_at','ASC')
            ->orderBy('category','desc')
            ->get();
            $items = DB::table('products')->where('category', $category)->get();

            $res = array();
            foreach($data as $item){
            $res2 = array();
            $data2 = DB::table('orders')
                    ->where(['deleted'=>NULL, 'save'=>NULL, 'status'=>'approved'])
                    ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
                    ->where('orders.nepmonth', $item->nepmonth)
                    ->where('orders.nepyear', $item->nepyear)
                    ->where('orders.category', $category)
                    ->where(function ($query) use ($request){
                        if($request->get('name')){
                            $query->where('orders.name', $request->get('name'));
                        }
                    })
                    ->selectRaw('*, SUM(approvedquantity) as sum')->groupBy('item')->orderBy('name','ASC');
                foreach($items as $item2){
                    $data3 = $data2->where('item', $item2->name)->get();
                    if(count($data3)>0){
                        $res2[] = [
                            'name'=>$item2->name,
                            'quant'=>$data3[0]->sum,
                            'hide'=>$item2->hide,
                            'uniid'=>$item2->produni_id,
                        ];
                    }
                    else{
                        $res2[] = [
                            'name'=>$item2->name,
                            'quant'=>'0',
                            'hide'=>$item2->hide,
                            'uniid'=>$item2->produni_id
                        ];
                    }
                    $data2 = DB::table('orders')
                    ->where(['deleted'=>NULL, 'save'=>NULL, 'status'=>'approved'])
                    ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
                    ->where('orders.nepmonth', $item->nepmonth)
                    ->where('orders.nepyear', $item->nepyear)
                    ->where('orders.category', $category)
                    ->where(function ($query) use ($request){
                        if($request->get('name')){
                            $query->where('orders.name', $request->get('name'));
                        }
                    })
                    ->selectRaw('*, SUM(approvedquantity) as sum')->groupBy('item')->orderBy('name','ASC');
                }
                $res[] = [
                    'month'=>$item->nepmonth,
                    'year'=>$item->nepyear,
                    'prod'=>$res2
                ];
            }
            $result['testdata'] = json_encode($res);
            $result['data'] = $items;
            $result['sort'] = 'category';
            $result['category'] = $category;
        }
        else{
            $data = DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL, 'status'=>'approved'])
            ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->where(function ($query) use ($request){
                if($request->get('name')){
                    $query->where('orders.name', $request->get('name'));
                }
            })
            ->selectRaw('*, SUM(approvedquantity) as sum')->groupBy(['nepmonth', 'nepyear'])->orderBy('created_at','ASC')
            ->orderBy('category','desc')
            ->get();
    
            $res = array();
    
            foreach($data as $item){
                $category = DB::table("categories")->get();
                $data2 = DB::table('orders')
                ->where(['deleted'=>NULL, 'save'=>NULL, 'status'=>'approved'])
                ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
                ->where('nepmonth', $item->nepmonth)
                ->where('nepyear',$item->nepyear)
                ->where(function ($query) use ($request){
                    if($request->get('name')){
                        $query->where('orders.name', $request->get('name'));
                    }
                })
                ->selectRaw('*, SUM(approvedquantity) as sum')
                ->groupBy(['category','nepmonth', 'nepyear'])
                ->orderBy('created_at','desc')
                ->orderBy('category','desc')
                ->get();
            //    $oth =  DB::table('orders')
            //    ->where(['deleted'=>NULL, 'save'=>NULL, 'status'=>'approved', 'category'=>'others'])
            //    ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
            //    ->where('nepmonth', $item->nepmonth)
            //     ->where('nepyear',$item->nepyear)
            //     ->where(function ($query) use ($request){
            //         if($request->get('name')){
            //             $query->where('orders.name', $request->get('name'));
            //         }
            //     })
            //    ->selectRaw('*, SUM(approvedquantity) as sum')
            //    ->get();
    
            //    if($oth[0]->sum != NULL){
            //     $othnum = $oth[0]->sum;
            //    }
            //    else{
            //     $othnum = "0";
            //    }
            //    if($data2->where('category','powerbank')->first() == NULL){
            //     $pb = 0;
            //    }
            //    else{
            //     $pb = $data2->where('category','powerbank')->first()->sum;
            //    }
            //    if($data2->where('category','charger')->first() == NULL){
            //     $ch = 0;
            //    }
            //    else{
            //     $ch = $data2->where('category','charger')->first()->sum;
            //    }
            //    if($data2->where('category','cable')->first() == NULL){
            //     $ca = 0;
            //    }
            //    else{
            //     $ca = $data2->where('category','cable')->first()->sum;
            //    }
            //    if($data2->where('category','earphone')->first() == NULL){
            //     $ep = 0;
            //    }
            //    else{
            //     $ep = $data2->where('category','earphone')->first()->sum;
            //    }
            //    if($data2->where('category','btitem')->first() == NULL){
            //     $bt = 0;
            //    }
            //    else{
            //     $bt = $data2->where('category','btitem')->first()->sum;
            //    }
                // $res[] = [
                //     'month'=>$item->nepmonth,
                //     'year'=>$item->nepyear,
                //     'powerbank'=>$pb,
                //     'charger'=>$ch,
                //     'cable'=>$ca,
                //     'earphone'=>$ep,
                //     'btitem'=>$bt,
                //     'others'=>$othnum,
                // ];
                $dat = [];
                foreach($category as $cat){
                    if($data2->where('category',$cat->category)->first() == NULL){
                        $su = 0;
                    }
                    else{
                     $su = $data2->where('category',$cat->category)->first()->sum;
                    }
                    $dat[$cat->category] = $su;
                }
                $dat['month'] = $item->nepmonth;
                $dat['year'] = $item->nepyear;
                $res[] = $dat;
            }
        $result['data'] = collect($res);
        $result['sort'] = 'normal';
        $result['category'] = '';
        }
      
        if ($request->get('name')) {
            $result['name'] = $request->get('name');
        }
        else{
            $result['name'] = '';
        }
        $result['categories'] = DB::table("categories")->get();
        return view('admin/productreport', $result);
    }
    public function damage(Request $request){
        return view('damage/analytics');
    }
    
    public function damagedata(Request $request){
        $product = $request->post("product");
        $batch = $request->post("batch");
        $category = $request->post("category");
        $name = $request->post('name');
        $problem = $request->post('problem');
        $solution = $request->post('solution');
        if($product == NULL && $batch == NULL && $category == NULL && $name == NULL && $problem == NULL && $solution == NULL){
            $res = "";
        }
        else{
            $query = DB::table('damage');
            if($product != NULL){
                $query = $query->where('item', $product);
            }
            if($batch != NULL){
                $query = $query->where('batch', $batch);
            }
            if($category != NULL){
                $query = $query->where('category', $category);
            }
            if($name != NULL){
                $query = $query->where('name', $name);
            }
            if($problem != NULL){
                $query = $query->where('problem', $problem);
            }
            if($solution != NULL){
                $query = $query->where('solution', $solution);
            }
            $query = $query->orderBy('date', 'DESC')->get();
            $res = $query;
        }
       
        return response()->json($res);
    }
}
