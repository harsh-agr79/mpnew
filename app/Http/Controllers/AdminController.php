<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function changemode(Request $request){
        $admin = DB::table('admins')->where('id',session()->get('ADMIN_ID'))->first();
        if($admin->mode == 'light'){
            $mode = 'dark';
        }
        else{
            $mode = 'light';
        }
        DB::table('admins')->where('id',session()->get('ADMIN_ID'))->update([
            'mode'=>$mode
        ]);
        return redirect(url()->previous());
    }

    public function staff(){
        $result['data'] = DB::table('admins')->where('type', '!=', 'admin')->get();

        return view('admin/staff', $result);
    }

    public function addstaff(Request $request,$id=''){

        if($id > 0){
            $data = DB::table('admins')->where('id', $id)->first();
           $result['id'] = $data->id;
           $result['name'] = $data->name;
           $result['userid'] = $data->email;
           $result['contact'] = $data->contact;
           $result['password'] = $data->password;
           $result['type'] = $data->type;

           $result['permission'] = DB::table('permission')->where('userid', $data->id)->pluck('perm')->toArray();
        }
        else{
            $result['id'] = '';
           $result['name'] = '';
           $result['userid'] = '';
           $result['contact'] = '';
           $result['password'] = '';
           $result['type'] = '';

           $result['permission'] = [];
        }
        return view('admin/addstaff', $result);
    }

    public function addstaff_process(Request $request){

        // dd($request->post());
        $id = $request->get('id');
        $name = $request->get('name');
        $userid = $request->get('userid');
        $contact = $request->get('contact');
        $type = $request->get('type');

        if($request->get('passwordnew')){
            $password = $request->get('passwordnew');
        }
        else{
            $password = $request->get('passwordold');
        }

        if($id>0){
            DB::table('admins')->where('id', $id)->update([
                'name'=>$name,
                'email'=>$userid,
                'contact'=>$contact,
                'password'=>Hash::make($password),
                'type'=>$type
            ]);
            $initial = $request->post('name2');
            $initialid = $request->post('userid2');
            DB::table('orders')->where('refname', $initial)->update([
                'refname'=>$name
            ]);
            DB::table('customers')->where('refname', $initial)->update([
                'refname'=>$name
            ]);
            DB::table('orders')->where('seenby', $initialid)->update([
                'seenby'=>$userid
            ]);
            DB::table('permission')->where('userid', $id)->delete();
            $perms = $request->post('perm', []);
            for ($i=0; $i < count($perms); $i++) { 
                $perm = explode('|', $perms[$i]);
                foreach($perm as $item){
                    DB::table('permission')->insert([
                        'userid'=>$id,
                        'perm'=>$item,
                        'value'=>"1",
                    ]);
                }
            }
        }
        else{
            DB::table('admins')->insert([
                'name'=>$name,
                'email'=>$userid,
                'contact'=>$contact,
                'password'=>$password,
                'type'=>$type
            ]);
        }

        return redirect(url()->previous());
    }
}
