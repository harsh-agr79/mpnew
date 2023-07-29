<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketerController extends Controller
{
    public function marketerhome(Request $request){
        $cuslist = marketercuslist(session()->get('ADMIN_ID'));
        $query = DB::table('orders');
        $query = $query->where(['deleted'=>NULL, 'save'=>NULL])->whereIn('name', $cuslist)->orderBy('created_at', 'DESC')->groupBy('orderid');
        if($request->get('name')){
           $query = $query->where('name', $request->get('name'));
           $result['name']= $request->get('name');
        }
        else{
            $result['name'] ='';
        }
        if($request->get('date')){
           $query = $query->whereDate('created_at', $request->get('date'));
           $result['date']= $request->get('date');
        }
        else{
            $result['date']= '';
        }
        $query = $query->paginate(50);
        $result['data']=$query;
    

        return view('marketer/home', $result);
    }

    public function statement(Request $request){
        $cuslist = marketercuslist(session()->get('ADMIN_ID'));
        $result['data'] = DB::table('customers')->where('deleted', NULL)->whereIn('name', $cuslist)->orderBy('name', 'ASC')->get();

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

    public function mainanalytics(Request $request){
        $cuslist = marketercuslist(session()->get('ADMIN_ID'));
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
        ->whereIn('orders.name', $cuslist)
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
        ->whereIn('orders.name', $cuslist)
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
            ->whereIn('orders.name', $cuslist)
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
            ->whereIn('orders.name', $cuslist)
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
        $cuslist = marketercuslist(session()->get('ADMIN_ID'));
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
            ->whereIn('orders.name', $cuslist)
            ->where('orders.name', $request->get('name'))
            ->where('orders.item', $request->get('product'))
            ->orderBy('created_at', 'DESC')
            ->get();

            $result['nptotal'] =  DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->where('status', 'approved')
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->whereIn('orders.name', $cuslist)
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
            ->whereIn('orders.name', $cuslist)
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
            ->whereIn('orders.name', $cuslist)
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
                ->whereIn('orders.name', $cuslist)
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
                ->whereIn('orders.name', $cuslist)
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
            ->whereIn('orders.name', $cuslist)
            ->where('orders.item', $request->get('product'))
            ->selectRaw('orders.*, customers.type, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')
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
            ->whereIn('orders.name', $cuslist)
            ->where('orders.item', $request->get('product'))
            ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
            ->groupBy('item')
            ->get();
            $result['pnodata'] = DB::table('customers')
            ->where('deleted', NULL)
            ->whereIn('name', $cuslist)
            ->whereNotIn('name', DB::table('orders')
                            ->where('status', 'approved')
                            ->where(['deleted'=>NULL, 'save'=>NULL])
                            ->where('orders.created_at', '>=', $date)
                            ->where('orders.created_at', '<=', $date2)
                            ->whereIn('orders.name', $cuslist)
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
        $cuslist = marketercuslist(session()->get('ADMIN_ID'));

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
        if($request->get('name')){
            $result['data'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$request->get('name')])
            ->whereIn('orders.name', $cuslist)
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy(['nepmonth', 'nepyear'])
            ->get();

            $result['fquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$request->get('name')])
            ->whereIn('orders.name', $cuslist)
            ->whereIn('nepmonth', [1,2,3])
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();
        
            $result['squat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$request->get('name')])
            ->whereIn('orders.name', $cuslist)
            ->whereIn('nepmonth', [4,5,6])
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['tquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$request->get('name')])
            ->whereIn('orders.name', $cuslist)
            ->whereIn('nepmonth', [7,8,9])
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['frquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$request->get('name')])
            ->whereIn('orders.name', $cuslist)
            ->whereIn('nepmonth', [10,11,12])
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['fifdays'] = DB::table('orders')
            ->where(['deleted'=>NULL])
            ->where('name', $request->get('name'))
            ->whereIn('orders.name', $cuslist)
            ->whereBetween('created_at', [now()->subDays(15), now()])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')->groupBy('name')
            ->get();

            $result['thirdays'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->where('name', $request->get('name'))
            ->whereIn('orders.name', $cuslist)
            ->where('status','approved') 
            ->whereBetween('created_at', [now()->subDays(30), now()->addDays(1)])
            ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')->groupBy('name')
            ->get();

            $result['fourdays'] = DB::table('orders')
            ->where(['deleted'=>NULL])
            ->where('name',$request->get('name'))
            ->whereIn('orders.name', $cuslist)
            ->whereBetween('created_at', [now()->subDays(45), now()->addDays(1)])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')->groupBy('name')
            ->get();

            $result['sixdays'] = DB::table('orders')
            ->where(['deleted'=>NULL])
            ->where('name', $request->get('name'))
            ->whereIn('orders.name', $cuslist)
            ->whereBetween('created_at', [now()->subDays(60), now()->addDays(1)])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')->groupBy('name')
            ->get();
            $result['nindays'] = DB::table('orders')
            ->where(['deleted'=>NULL])
            ->where('name', $request->get('name'))
            ->whereIn('orders.name', $cuslist)
            ->whereBetween('created_at', [now()->subDays(90), now()->addDays(1)])
            ->where('status','approved') 
            ->selectRaw('*, SUM(approvedquantity * price) as sum, SUM(discount*0.01 * approvedquantity * price) as dis')->groupBy('name')
            ->get();

            $result['name'] = $request->get('name');
        }
        else{
            $result['data'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
            ->orderBy('created_at', 'ASC')
            ->whereIn('orders.name', $cuslist)
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy(['nepmonth', 'nepyear'])
            ->get();

            $result['fquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
            ->whereIn('nepmonth', [1,2,3])
            ->orderBy('created_at', 'ASC')
            ->whereIn('orders.name', $cuslist)
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['squat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
            ->whereIn('nepmonth', [4,5,6])
            ->orderBy('created_at', 'ASC')
            ->whereIn('orders.name', $cuslist)
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['tquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
            ->whereIn('nepmonth', [7,8,9])
            ->orderBy('created_at', 'ASC')
            ->whereIn('orders.name', $cuslist)
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['frquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL])
            ->whereIn('nepmonth', [10,11,12])
            ->orderBy('created_at', 'ASC')
            ->whereIn('orders.name', $cuslist)
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['name'] = "";
        }      
        return view ('admin/detailedreport', $result);
    }

    public function index(Request $request){
        $cuslist = marketercuslist(session()->get('ADMIN_ID'));
        $query = DB::table('payments')->whereIn('name', $cuslist)->orderBy('date', 'DESC');
        $result['date'] = '';
        $result['date2'] =  '';
        $result['name'] =  '';
        if($request->get('date')){
            $query = $query->where('date', '>=', $request->get('date'));
            $result['date'] =  $request->get('date');
        }
        if($request->get('date2')){
            $query = $query->where('date', '<=', $request->get('date2'));
            $result['date2'] =  $request->get('date2');
        }
        if($request->get('name')){
            $query = $query->where('name', $request->get('name'));
            $result['name'] =  $request->get('name');
        }
        $query = $query->paginate(100);
        $result['data'] = $query;
        return view('admin/payment', $result);
    }
    public function addpay(Request $request, $id = ''){
        if($id !== ""){
        $cuslist = marketercuslist(session()->get('ADMIN_ID'));
            $pay = DB::table('payments')->whereIn('name', $cuslist)->where('paymentid', $id)->first();
            $result['date'] = $pay->date;
            $result['name'] = $pay->name;
            $result['amount'] = $pay->amount;
            $result['voucher'] = $pay->voucher;
            $result['remarks'] = $pay->remarks;
            $result['payid'] = $pay->paymentid;
        }
        else{
            $result['date'] = date('Y-m-d');
            $result['name'] = '';
            $result['amount'] = '';
            $result['voucher'] = '';
            $result['remarks'] = '';
            $result['payid'] = '';
        }
        return view('admin/addpayment', $result);
    }
    public function addpay_process(Request $request){
        $payid = $request->post('payid');
        $admin = DB::table('admins')->find($request->session()->get('ADMIN_ID'));
        if($payid === NULL){
            DB::table('payments')->insert([
                'date'=>$request->post('date'),
                'name'=>$request->post('name'),
                'cusuni_id'=>DB::table('customers')->where('name', $request->post('name'))->first()->cusuni_id,
                'paymentid'=>date('ymdhis'),
                'amount'=>$request->post('amount'),
                'voucher'=>$request->post('voucher'),
                'remarks'=>$request->post('remarks'),
                'entry_by'=>$admin->email,
            ]);

            return redirect('marketer/addpayment');
        }
        else{
            DB::table('payments')->where('paymentid', $payid)->update([
                'date'=>$request->post('date'),
                'name'=>$request->post('name'),
                'amount'=>$request->post('amount'),
                'voucher'=>$request->post('voucher'),
                'remarks'=>$request->post('remarks'),
            ]);
            return redirect('marketer/payments');
        }
    } 

    public function deletepay(Request $request, $id){
        $cuslist = marketercuslist(session()->get('ADMIN_ID'));
        DB::table('payments')->whereIn('name', $cuslist)->where('paymentid', $id)->delete();
        return redirect('/payments');
    }

    public function details(Request $request, $orderid){
        $result['data'] = DB::table('orders')->where('orderid', $orderid) 
        ->join('products', 'orders.produni_id', '=', 'products.produni_id')
        ->selectRaw('orders.*, products.stock')
        ->get();

        return view('marketer/detail', $result);
    }


    public function createorder(Request $request){
        $result['data'] = DB::table('products')->where('hide', '!=', 'on')->orWhereNull('hide')->orderBy('category', 'DESC')->orderBy('ordernum', 'ASC')->get();
        return view('admin/createorder', $result);
    }
    public function addorder(Request $request){
        $name = $request->post('name');
        $date = $request->post('date');
        $customer = DB::table('customers')->where('name', $name)->first();
        $orderid=$customer->id.time();

        $time = date('H:i:s');
        $created_at = $date." ".$time;
        $nepmonth = getNepaliMonth($created_at);
        $nepyear = getNepaliYear($created_at);

        if($request->session()->get('ADMIN_TYPE') == 'marketer'){
            $refid = $request->session()->get('ADMIN_ID');
            $refname = DB::table('admins')->where('id', $refid)->first()->name;
            $reftype = 'marketer';
        }
        elseif($request->session()->get('ADMIN_TYPE') == 'admin'){
            $refname = $customer->refname;
            if($refname == NULL)
            {
                $refid = NULL;
                $reftype = NULL;
            }
            else{
                $ref = DB::table('customers')->where('name', $refname)->first();
                if($ref == NULL)
                {
                    $refid = DB::table('admins')->where('name', $refname)->first()->id;
                    $reftype = DB::table('admins')->where('name', $refname)->first()->type;
                }
                else
                {
                    $refid = DB::table('customers')->where('name', $refname)->first()->id;
                    $reftype = DB::table('customers')->where('name', $refname)->first()->type;
                }
            }
        }
        else
        {
            $refid = NULL;
            $refname = NULL;
            $reftype = NULL;
        }

        $item=$request->post('item',[]);
        $price=$request->post('price',[]);
        $category=$request->post('category',[]);
        $quantity=$request->post('quantity',[]);
        $prodid=$request->post('prodid',[]);

        for ($i=0; $i < count($item); $i++) { 
            if($quantity[$i] !== NULL && $quantity[$i] !== '0'){
                DB::table('orders')->insert([
                    'name'=>$name,
                    'userid'=>$customer->id,
                    'orderid'=>$orderid,
                    'item'=>$item[$i],
                    'cusuni_id'=>$customer->cusuni_id,
                    'produni_id'=>$prodid[$i],
                    'category'=>$category[$i],
                    'price'=>$price[$i],
                    'quantity'=>$quantity[$i],
                    'approvedquantity'=>'0',
                    'mainstatus'=>'blue',
                    'status'=>'pending',
                    'created_at'=>$created_at,
                    'refname'=>$refname,
                    'refid'=>$refid,
                    'reftype'=>$reftype,
                    'nepmonth'=>$nepmonth,
                    'nepyear'=>$nepyear,
                ]);
            }
        }

        return redirect('marketer/detail/'.$orderid);
    }

    public function editorder(Request $request, $orderid){
        $result['order'] = DB::table('orders')->where('orderid', $orderid)
        ->join('products', 'products.produni_id', '=', 'orders.produni_id')
        ->selectRaw('orders.*, products.img, products.hide, products.stock, products.subcat')
        ->get();
        $result['data'] = DB::table('products')
        ->whereNotIn('name', DB::table('orders')->where('orderid', $orderid)->pluck('item')->toArray())
        ->orderBy('category', 'ASC')
        ->orderBy('ordernum', 'ASC')->get();

        return view('admin/editorder', $result);
    }

    public function editorder_process(Request $request)
    {
        $name = $request->post('name');
        $date = $request->post('date');
        $customer = DB::table('customers')->where('name', $name)->first();
        $orderid = $request->post('orderid');
        $time = date('H:i:s');
        $created_at = $date." ".$time;
        $nepmonth = getNepaliMonth($created_at);
        $nepyear = getNepaliYear($created_at);
        $order = DB::table('orders')->where('orderid', $orderid)->first();

        if($request->session()->get('ADMIN_TYPE') == 'marketer'){
            $refid = $request->session()->get('ADMIN_ID');
            $refname = DB::table('admins')->where('id', $refid)->first()->name;
            $reftype = 'marketer';
        }
        elseif($request->session()->get('ADMIN_TYPE') == 'admin'){
            $refname = $customer->refname;
            if($refname == NULL)
            {
                $refid = NULL;
                $reftype = NULL;
            }
            else{
                $ref = DB::table('customers')->where('name', $refname)->first();
                if($ref == NULL)
                {
                    $refid = DB::table('admins')->where('name', $refname)->first()->id;
                    $reftype = DB::table('admins')->where('name', $refname)->first()->type;
                }
                else
                {
                    $refid = DB::table('customers')->where('name', $refname)->first()->id;
                    $reftype = DB::table('customers')->where('name', $refname)->first()->type;
                }
            }
        }
        else
        {
            $refid = NULL;
            $refname = NULL;
            $reftype = NULL;
        }

        $item=$request->post('item',[]);
        $price=$request->post('price',[]);
        // $category=$request->post('category',[]);
        $quantity=$request->post('quantity',[]);
        // $prodid=$request->post('prodid',[]);
        $id = $request->post('id', []);
        $status = $request->post('status',[]);

        for ($i=0; $i < count($item); $i++) { 
            if($quantity[$i] !== NULL && $quantity[$i] !== '0'){
                if($id[$i]){
                    DB::table('orders')->where('id', $id[$i])->update([
                        'name'=>$name,
                        'userid'=>$customer->id,
                        'orderid'=>$orderid,
                        'item'=>$item[$i],
                        'cusuni_id'=>$customer->cusuni_id,
                        'produni_id'=>DB::table('products')->where('name', $item[$i])->first()->produni_id,
                        'category'=>DB::table('products')->where('name', $item[$i])->first()->category,
                        'price'=>$price[$i],
                        'quantity'=>$quantity[$i],
                        // 'approvedquantity'=>'0',
                        'mainstatus'=>'blue',
                        'status'=>$status[$i],
                        'created_at'=>$created_at,
                        'refname'=>$refname,
                        'refid'=>$refid,
                        'reftype'=>$reftype,
                        'nepmonth'=>$nepmonth,
                        'nepyear'=>$nepyear,
                    ]);
                }
                else{
                    DB::table('orders')->insert([
                        'name'=>$name,
                        'userid'=>$customer->id,
                        'orderid'=>$orderid,
                        'item'=>$item[$i],
                        'cusuni_id'=>$customer->cusuni_id,
                        'produni_id'=>DB::table('products')->where('name', $item[$i])->first()->produni_id,
                        'category'=>DB::table('products')->where('name', $item[$i])->first()->category,
                        'price'=>$price[$i],
                        'quantity'=>$quantity[$i],
                        'approvedquantity'=>'0',
                        'mainstatus'=>'blue',
                        'status'=>$status[$i],
                        'created_at'=>$created_at,
                        'refname'=>$refname,
                        'refid'=>$refid,
                        'reftype'=>$reftype,
                        'nepmonth'=>$nepmonth,
                        'nepyear'=>$nepyear,
                        'seen'=>$order->seen,
                        'seenby'=>$order->seenby,
                    ]);
                }
            }
            elseif ($quantity[$i] == NULL || $quantity[$i] == '0' && $id[$i] !== NULL) {
                DB::table('orders')->where('id', $id[$i])->delete();
            }
        }
        updateMainStatus($orderid);
        return redirect('marketer/detail/'.$orderid);
    }
}
