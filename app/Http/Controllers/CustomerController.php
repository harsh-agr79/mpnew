<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class CustomerController extends Controller
{
    public function changemode(Request $request){
        $user = DB::table('customers')->where('id',session()->get('USER_ID'))->first();
        if($user->mode == 'light'){
            $mode = 'dark';
        }
        else{
            $mode = 'light';
        }
        DB::table('customers')->where('id',session()->get('USER_ID'))->update([
            'mode'=>$mode
        ]);
        return redirect(url()->previous());
    }
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
        $result['data'] = DB::table('customers')->where('deleted','!=','on')->orWhereNull('deleted')->get();

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
        $refname = $request->post('refname');
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
        if($id > 0){
            if($request->get('passwordnew')){
                $password = Hash::make($request->get('passwordnew'));
            }
            else{
                $password = $request->get('passwordold');
            }
            
            DB::table('customers')->where('id', $id)->update([
                'name'=>$request->post('name'),
                'shopname'=>$request->post('shopname'),
                'user_id'=>$request->post('userid'),
                'contact'=>$request->post('contact'),
                'address'=>$request->post('address'),
                'cusuni_id'=>$request->post('uniqueid'),
                'refname'=>$request->post('refname'),
                'refid'=>$refid,
                'reftype'=>$reftype,
                'password'=>$password,
                'openbalance'=>$request->post('openbalance'),
                'obtype'=>$request->post('obtype'),
                'type'=>$request->post('type'),
                'cus_from'=>$request->post('from'),
            ]);

           

            $initial = $request->post('name1');
            $changed = $request->post('name');
            $idinitial = $request->post('uniold');
            $idchanged = $request->post('uniqueid');
            if($request->post('id')>0){
                DB::table('orders')->where('name',$initial)->update([
                    'name'=>$changed,
                    'cusuni_id'=>$idchanged,
                ]);
                DB::table('payments')->where('name',$initial)->update([
                    'name'=>$changed,
                    'cusuni_id'=>$idchanged,
                ]);
                DB::table('salesreturns')->where('name',$initial)->update([
                    'name'=>$changed,
                    'cusuni_id'=>$idchanged,
                ]);
                DB::table('expenses')->where('name',$initial)->update([
                    'name'=>$changed
                ]);
                DB::table('orders')->where('cusuni_id',$idinitial)->update([
                    'cusuni_id'=>$idchanged
                ]);
                DB::table('payments')->where('cusuni_id',$idinitial)->update([
                    'cusuni_id'=>$idchanged
                ]);
                DB::table('salesreturns')->where('cusuni_id',$idinitial)->update([
                    'cusuni_id'=>$idchanged
                ]);
                DB::table('orders')->where('cusuni_id',$request->post('uniqueid'))->update([
                    'refname'=>$request->post('refname'),
                    'refid'=>$refid,
                    'reftype'=>$reftype
                ]);
                $msg="Customer updated";
            }
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
                'refid'=>$refid,
                'reftype'=>$reftype,
                'password'=>Hash::make($request->post('passwordnew')),
                'openbalance'=>$request->post('openbalance'),
                'obtype'=>$request->post('obtype'),
                'type'=>$request->post('type'),
                'cus_from'=>$request->post('from'),
            ]);
        }
      return redirect('/customers');
    }

    public function deletecustomer(Request $request, $id){
        DB::table('customers')->where('id', $id)->update([
            'deleted'=>'on',
            'deleted_at'=>date('Y-m-d H:i:s'),
        ]);
        return redirect('/customers');
    }
    public function addupdate(Request $request){
        $ids = $request->post('id', []);
        $address = $request->post('address', []);
        $area = $request->post('area', []);
        $state = $request->post('state', []);
        $district = $request->post('district', []);

        for ($i=0; $i < count($ids); $i++) { 
           DB::table('customers')->where('id', $ids[$i])->update([
            'address'=>$address[$i],
            'area'=>$area[$i],
            'state'=>$state[$i],
            'district'=>$district[$i],
           ]);
        }
        return redirect('customers');
    }
}
