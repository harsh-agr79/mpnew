<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request){
        $query = DB::table('payments')->where('deleted', NULL)->orderBy('date', 'DESC');
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
        if(session()->get('ADMIN_TYPE') == 'staff'){
            $name = DB::table('admins')->where('id', session()->get('ADMIN_ID'))->first()->email;
            $query = $query->where('entry_by', $name);
        }
        $query = $query->paginate(100);
        $result['data'] = $query;
        return view('admin/payment', $result);
    }
    public function addpay(Request $request, $id = ''){
        if($id !== ""){
            $pay = DB::table('payments')->where('paymentid', $id)->first();
            $result['date'] = $pay->date;
            $result['name'] = $pay->name;
            $result['amount'] = $pay->amount;
            $result['voucher'] = $pay->voucher;
            $result['remarks'] = $pay->remarks;
            $result['payid'] = $pay->paymentid;
        }
        else{
            $result['date'] = date('Y-m-d H:i:s');
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

            return redirect('addpayment');
        }
        else{
            DB::table('payments')->where('paymentid', $payid)->update([
                'date'=>$request->post('date'),
                'name'=>$request->post('name'),
                'amount'=>$request->post('amount'),
                'voucher'=>$request->post('voucher'),
                'remarks'=>$request->post('remarks'),
            ]);
            return redirect('payments');
        }
    } 

    public function deletepay(Request $request, $id){
        DB::table('payments')->where('paymentid', $id)->update([
            'deleted'=>'on',
            'deleted_at'=>date('Y-m-d H:i:s')
        ]);
        return redirect('/payments');
    }
}
