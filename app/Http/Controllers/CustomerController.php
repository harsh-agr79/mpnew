<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CustomerController extends Controller
{
    public function getcustomer(){
        if(session()->get('ADMIN_TYPE') == 'marketer')
        {
             $c = DB::table('customers')->where('refid', session()->get('ADMIN_ID'))->get();
        }else
        {
            $c = DB::table('customers')->get();
        }
        return response()->json($c);
    }

    public function index(Request $request){
        $result['data'] = DB::table('customers')->where('deleted', NULL)->get();

        return view('admin/customers', $result);
    }

    public function addcustomer(Request $request, $id = ''){
        if($id > 0){
            $cus = DB::table('customers')->where('id', $id)->first();
            $result['name'] = $cus->name;
            $result['shopname'] = $cus->shopname;
            $result['userid'] = $cus->user_id;
            $result['contact'] = $cus->contact;
            $result['address'] = $cus->address;
            $result['uniqueid'] = $cus->cusuni_id;
            $result['refname'] = $cus->refname;
            $result['password'] = $cus->password;
            $result['openbalance'] = $cus->openbalance;
            $result['obtype'] = $cus->obtype;
            $result['type'] = $cus->type;
            $result['from'] = $cus->cus_from;
            $result['id'] = $cus->id;
        }
        else{
            $result['name'] = '';
            $result['shopname'] = '';
            $result['userid'] = '';
            $result['contact'] = '';
            $result['address'] = '';
            $result['uniqueid'] = '';
            $result['refname'] = '';
            $result['password'] = '';
            $result['openbalance'] = '';
            $result['obtype'] = '';
            $result['type'] = '';
            $result['from'] = '';
            $result['id'] = '';
        }
        return view('admin/addcustomer',$result);
    }

    public function addcustomer_process(Request $request){
        $id = $request->post('id');
        $request->validate([
            'contact'=>'required|unique:customers,contact,'.$request->post('id'),
            'userid'=>'required|unique:customers,user_id,'.$request->post('id'),
            'uniqueid'=>'required|unique:customers,cusuni_id,'.$request->post('id')
        ]);
        if($id > 0){
            DB::table('customers')->where('id', $id)->update([
                'name'=>$request->post('name'),
                'shopname'=>$request->post('shopname'),
                'user_id'=>$request->post('userid'),
                'contact'=>$request->post('contact'),
                'address'=>$request->post('address'),
                'cusuni_id'=>$request->post('uniqueid'),
                'refname'=>$request->post('refname'),
                'password'=>$request->post('password'),
                'openbalance'=>$request->post('openbalance'),
                'obtype'=>$request->post('obtype'),
                'type'=>$request->post('type'),
                'cus_from'=>$request->post('from'),
            ]);
        }
        else{
            DB::table('customers')->insert([
                'name'=>$request->post('name'),
                'shopname'=>$request->post('shopname'),
                'user_id'=>$request->post('userid'),
                'contact'=>$request->post('contact'),
                'address'=>$request->post('address'),
                'cusuni_id'=>$request->post('uniqueid'),
                'refname'=>$request->post('refname'),
                'password'=>$request->post('password'),
                'openbalance'=>$request->post('openbalance'),
                'obtype'=>$request->post('obtype'),
                'type'=>$request->post('type'),
                'cus_from'=>$request->post('from'),
            ]);
        }
      return redirect('/customers');
    }

    public function deletecustomer(Request $request, $id){
        DB::table('customers')->where('id', $id)->delete();
        return redirect('/customers');
    }
}
