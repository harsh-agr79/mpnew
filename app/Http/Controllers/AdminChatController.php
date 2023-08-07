<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminChatController extends Controller
{
    public function adminchat(Request $request, $id, $id2){
        $chan = DB::table('channels')->where('shortname', $id2)->first();
        if($chan != NULL || $id2 != NULL){
            $result['chat'] = DB::table('chat')->where('sid', $id)->where('channel', $id2)->orderBy('created_at', 'ASC')->get();
            $result['allchats'] = DB::table('chat')->orderBy('created_at', 'DESC')->get()->unique('sid');
            $result['user'] = DB::table('customers')->where('id',$id)->first();
            $result['channels'] = DB::table('channels')->get();
            $result['channel'] = $id2;

            return view('adminchat/chathome', $result);
        }
        else{
            return redirect('chats/'.$id.'/general');
        }
      
    }
    public function addmsgadmin(Request $request){
        $sendtype = session()->get('ADMIN_TYPE');
        $sentBy = session()->get('ADMIN_ID');
        $sentname = DB::table('admins')->where('id', session()->get('ADMIN_ID'))->first()->email;
        $sid = $request->post('sid');
        $channel = $request->post('channel');
        $message = $request->post('message');
        $time = time();

        DB::table('chat')->insert([
            'sid'=>$sid,
            'sendtype'=>$sendtype,
            'sentBy'=>$sentBy,
            'sentname'=>$sentname,
            'channel'=>$channel,
            'message'=>$message,
            'created_at'=>$time,
        ]);
        $res = array();
        $res[] =  [
            'sid'=>$sid,
            'sendtype'=>$sendtype,
            'sentBy'=>$sentBy,
            'channel'=>$channel,
            'sentname'=>$sentname,
            'message'=>$message,
            'created_at'=>$time,
        ];
        return response()->json($res);
    }

    public function getchatlist(){
        $res =DB::table('chat')->join('customers', 'customers.id', '=', 'chat.sid')->orderBy('chat.created_at', 'DESC')->get()->unique('sid');
        return response()->json($res);
    }

    public function addchannel(Request $request){
        DB::table('channels')->insert([
            'name'=>$request->post('name'),
            'shortname'=>$request->post('shortname'),
            'color'=>$request->post('color'),
            'adminonly'=>$request->post('adminonly'),
        ]);
        // $url = $request->post('preurl');
        return redirect(url()->previous());
    }
    public function getchannel(Request $request, $id){
        $channel = DB::table('channels')->where('id', $id)->first();
        return response()->json($channel);
    }
    public function editchannel(Request $request){
        DB::table('channels')->where('id', $request->post('id'))->update([
            'name'=>$request->post('name'),
            'shortname'=>$request->post('shortname'),
            'color'=>$request->post('color'),
            'adminonly'=>$request->post('adminonly'),
        ]);
        // $url = $request->post('preurl');
        return redirect(url()->previous());
    }
}
