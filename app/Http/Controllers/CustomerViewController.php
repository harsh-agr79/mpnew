<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Image;

class CustomerViewController extends Controller
{
    public function statement(Request $request){

        $cust = DB::table('customers')->where('id', session()->get('USER_ID'))->first();
        $name = $cust->name;
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
        return view('customer/statement', $result);
    }

    
    public function analytics(Request $request){
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
        $cust = DB::table('customers')->where('id', session()->get('USER_ID'))->first();
        $name = $cust->name;

        if ($request->get('product')) {
            $result['npdata'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->where('status', 'approved')
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->where('orders.name', $name)
            ->where('orders.item', $request->get('product'))
            ->orderBy('created_at', 'DESC')
            ->get();

            $result['nptotal'] =  DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->where('status', 'approved')
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->where('orders.name', $name)
            ->where('orders.item', $request->get('product'))
            ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
            ->groupBy('name')
            ->get();

            $result['datatype'] = 'np';
            $result['name'] = $name;
            $result['product'] = $request->get('product');
        }
        else{
            $result['totalsales'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->where('orders.name', $name)
            ->selectRaw('*,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
            ->groupBy('deleted')
            ->get();
            
            $result['catsales'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->where('orders.name', $name)
            ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
            ->groupBy('category')
            ->orderBy('samt','DESC')
            ->get();

            foreach($result['catsales'] as $item){
                $result['data'][$item->category] = DB::table('products')
                ->where(['orders.category'=>$item->category,'status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
                ->where('orders.created_at', '>=', $date)
                ->where('orders.created_at', '<=', $date2)
                ->where('orders.name', $name)
                ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
                ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
                ->get();
                $result['data2'][$item->category] = DB::table('products')
                ->where(['category'=>$item->category, 'hide'=>NULL])
                ->whereNotIn('produni_id', DB::table('orders')
                ->where(['category'=>$item->category,'status'=>'approved','deleted'=>NULL, 'save'=>NULL])
                ->where('created_at', '>=', $date)
                ->where('created_at', '<=', $date2)
                ->where('orders.name', $name)
                ->pluck('produni_id')
                ->toArray())
                ->get();
            }
            $result['datatype'] = 'n';
            $result['product'] = '';
        }

        return view('customer/analytics', $result);
    }


    public function summary(Request $request){
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
        $cust = DB::table('customers')->where('id', session()->get('USER_ID'))->first();
        $name = $cust->name;

            $result['data'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$name])
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy(['nepmonth', 'nepyear'])
            ->get();

            $result['fquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$name])
            ->whereIn('nepmonth', [1,2,3])
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();
        
            $result['squat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$name])
            ->whereIn('nepmonth', [4,5,6])
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['tquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$name])
            ->whereIn('nepmonth', [7,8,9])
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $result['frquat'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'status'=>'approved', 'save'=>NULL, 'name'=>$name])
            ->whereIn('nepmonth', [10,11,12])
            ->orderBy('created_at', 'ASC')
            ->selectRaw('*, SUM(approvedquantity * price) as sl, SUM(discount * 0.01 * approvedquantity * price) as dis')
            ->groupBy('nepyear')
            ->get();

            $date = getEnglishDate($result['syear'] ,  $result['smonth'],1);
            $date2 = getEnglishDate($result['eyear'] , $result['emonth'],getLastDate($result['emonth'] , date('y', strtotime($result['eyear'] ))));
    
            $result['date']=$date;
            $result['date2']=$date2;

            $result['catsales'] = DB::table('orders')
            ->where(['deleted'=>NULL, 'save'=>NULL])
            ->whereIn('mainstatus', ['green', 'deep-purple', 'amber darken-2'])
            ->where('orders.created_at', '>=', $date)
            ->where('orders.created_at', '<=', $date2)
            ->where('orders.name', $name)
            ->selectRaw('*,SUM(approvedquantity) as sum,SUM(approvedquantity * price) as samt, SUM(discount * 0.01 * approvedquantity * price) as damt')
            ->groupBy('category')
            ->orderBy('samt','DESC')
            ->get();

            foreach($result['catsales'] as $item){
                $result['data'.$item->category] = DB::table('products')
                ->where(['orders.category'=>$item->category,'status'=>'approved','orders.deleted'=>NULL, 'save'=>NULL])
                ->where('orders.created_at', '>=', $date)
                ->where('orders.created_at', '<=', $date2)
                ->where('orders.name', $name)
                ->join('orders', 'products.produni_id', '=', 'orders.produni_id')
                ->selectRaw('*, SUM(approvedquantity) as sum, SUM(approvedquantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')->groupBy('orders.produni_id')->orderBy('sum','desc')
                ->get();
                $result['data2'.$item->category] = DB::table('products')
                ->where(['category'=>$item->category])
                ->whereNotIn('produni_id', DB::table('orders')
                ->where(['category'=>$item->category,'status'=>'approved','deleted'=>NULL, 'save'=>NULL])
                ->where('created_at', '>=', $date)
                ->where('created_at', '<=', $date2)
                ->where('orders.name', $name)
                ->pluck('produni_id')
                ->toArray())
                ->get();
            }

        $today = date('Y-m-d');
         $target = DB::table('target')->where('userid',$cust->user_id)
         ->where('startdate', '<=', $today)
         ->where('enddate', '>=', $today)
         ->get();
        return view ('customer/summary', $result);
    }

    public function editprofile(Request $request){
        return view('customer/editprofile');
    }
    public function edpr_process(Request $request){
        if($file = $request->file('dp')){

            if(File::exists($request->post('olddp'))) {
                File::delete($request->post('olddp'));
            }
            $file = $request->file('dp');
            $ext = $file->getClientOriginalExtension();
            $image_name = session()->get('USER_ID').time().'userdp'.'.'.$ext;
            $image_resize = Image::make($file->getRealPath());
            $image_resize->fit(300);
            $image_resize->save('customerdp/'.$image_name);
            $image = 'customerdp/'.$image_name;

                DB::table('customers')->where('id', session()->get('USER_ID'))->update([
                    'profileimg'=>$image
                ]);
            }

        $address = $request->post('address');
        $shopname = $request->post('shopname');
        $contact1 = $request->post('contact1');
        $contact2 = $request->post('contact2');
        $taxtype = $request->post('taxtype');
        $taxnum = $request->post('taxnum');
        $dob = $request->post('dob');

        DB::table('customers')->where('id', session()->get('USER_ID'))->update([
            'address'=>$address,
            'shopname'=>$shopname,
            'contact'=>$contact1,
            'contact2'=>$contact2,
            'taxtype'=>$taxtype,
            'taxnum'=>$taxnum,
            'DOB'=>$dob,
        ]);

        return redirect('/home');
    }
}
