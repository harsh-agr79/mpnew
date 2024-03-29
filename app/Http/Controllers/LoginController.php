<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{
    //LOGIN page
    public function login(){
        if(session()->has('ADMIN_LOGIN')){
            if (session()->get('ADMIN_TYPE') == 'admin' || session()->get('ADMIN_TYPE') == 'staff') {
                return redirect('/dashboard');
            }
            elseif(session()->get('ADMIN_TYPE') == 'marketer'){
                return redirect('marketer/home');
            }
        }
        elseif(session()->has('USER_LOGIN')){
            return redirect('/home');
        }
        else{
            return view('login');
        }
    }

    //LOGIN FUNCTION
    public function auth(Request $request){
        $userid = $request->post('userid');
        $password = $request->post('password');

        $admin = DB::table('admins')->where(['email'=>$userid])->first();
        $customer = DB::table('customers')->where(['user_id'=>$userid])->first();
        if($admin!=NULL){
            if (Hash::check($request->post('password'), $admin->password)) {
                $request->session()->put('ADMIN_LOGIN', true);
                $request->session()->put('ADMIN_ID', $admin->id);
                $request->session()->put('ADMIN_TIME', time() );
                $request->session()->put('ADMIN_TYPE', $admin->type);
    
                return redirect('/');
            }
            else{
                $request->session()->flash('error','please enter valid login details');
                return redirect('/');
            }
        }
        elseif($customer!=NULL){
            if (Hash::check($request->post('password'), $customer->password)) {
            $request->session()->put('USER_LOGIN', true);
            $request->session()->put('USER_ID', $customer->id);
            $request->session()->put('USER_TIME', time() );

            return redirect('/');
            }
            else{
                $request->session()->flash('error','please enter valid login details');
                return redirect('/');
            }
        }
        else{
            $request->session()->flash('error','please enter valid login details');
            return redirect('/');
        }

    }

    //DASHBOARD PAGE
    public function dashboard(){
        // $result['dealer'] = DB::table('orders')
        // ->where(['orders.deleted'=>NULL, 'save'=>NULL])
        // ->havingBetween('orders.created_at', [today()->subDays(1), today()->addDays(1)])
        // ->orderBy('orders.created_at', 'DESC')
        // ->join('customers', 'customers.cusuni_id', '=', 'orders.cusuni_id')
        // ->selectRaw('orders.name,orders.created_at,orders.refname, orderid, mainstatus, seen, seenby, delivered, clnstatus, SUM(approvedquantity * price) as sla, SUM(discount * 0.01 * approvedquantity * price) as disa, SUM(quantity * price) as sl, SUM(discount * 0.01 * quantity * price) as dis')
        // ->groupBy('orders.orderid')
        // ->get();

        $result['mpe'] = DB::table('orders')
        ->where(['orders.deleted'=>NULL, 'save'=>NULL])
        ->havingBetween('orders.created_at', [today()->subDays(1), today()->addDays(1)])
        ->orderBy('orders.created_at', 'DESC')
        ->join('customers', 'customers.cusuni_id', '=', 'orders.cusuni_id')
        ->selectRaw('orders.name,orders.created_at,orders.refname, orderid, mainstatus, seen, seenby, delivered, clnstatus, SUM(approvedquantity * price) as sla, SUM(discount * 0.01 * approvedquantity * price) as disa, SUM(quantity * price) as sl, SUM(discount * 0.01 * quantity * price) as dis')
        ->groupBy('orders.orderid')
        ->get();

        $result['pending'] = DB::table('orders')
        ->where(['orders.deleted'=>NULL, 'save'=>NULL, 'status'=>'pending'])
        ->orderBy('orders.created_at', 'DESC')
        ->join('customers', 'customers.cusuni_id', '=', 'orders.cusuni_id')
        ->selectRaw('orders.name,orders.created_at,orders.refname, orderid, mainstatus, seen, seenby, delivered, clnstatus,SUM(quantity * orders.price) as samt, SUM(discount * 0.01 * approvedquantity * orders.price) as damt')
        ->groupBy('orders.orderid')
        ->get();
        return view('admin/dashboard', $result);
    }

    //CUSTOMER HOME PAGE
    public function home(){
        $result['data']=DB::table('front')->where('type', 'image')->get();
        $result['data2']=DB::table('front')->where('type', 'message')->get();
        return view('customer/home', $result);
    }

    public function set_time(Request $request){
        $request->session()->forget('USER_TIME');
        $request->session()->put('USER_TIME', time());
        
        return response()->json('success', 200);
    }
    public function changeLogin(Request $request, $id){
        if(session()->get('ADMIN_TYPE') == 'admin'){
            $admin = Crypt::encryptString(session()->get('ADMIN_ID'));
            $customer = DB::Table('customers')->where('id', $id)->first();
    
            session()->flush();
            $request->session()->put('USER_LOGIN', true);
            $request->session()->put('USER_ID', $customer->id);
            $request->session()->put('USER_TIME', time());
            $request->Session()->put('ADMIN_DIRECT', true);
            $request->session()->put('ADMIN_ID', $admin);
        }
        return redirect('/');
    }
    public function changeLoginBack(Request $request, $id){
        if(session()->has('ADMIN_DIRECT')){
            $adminid = Crypt::decryptString($id);
            $admin = DB::table('admins')->where('id',$adminid)->first();
            session()->flush();
                    $request->session()->put('ADMIN_LOGIN', true);
                    $request->session()->put('ADMIN_ID', $admin->id);
                    $request->session()->put('ADMIN_TIME', time() );
                    $request->session()->put('ADMIN_TYPE', $admin->type);
        }
        return redirect('/');
    }
}
