<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserChatController extends Controller
{
    public function chatlist(Request $request){
        $result['chatList'] = DB::table('chat')->where('sid', session()->get('USER_ID'))->orderBy('created_at', 'DESC')->get()->unique('channel');
        $result['noChatList'] = DB::table('channels')->whereNotIn('shortname', DB::table('chat')->where('sid', session()->get('USER_ID'))->groupBy('channel')->pluck('channel')->toArray())->get();

        return view('customer/chatlist', $result);
    }
    public function chatbox(Request $request, $id) {
        $result['chat'] = DB::table('chat')->where('sid', session()->get('USER_ID'))->where('channel', $id)->orderBy('created_at', 'ASC')->get();
        $result['chan'] = DB::table('channels')->where('shortname', $id)->first();
        $result['channel'] = $id;
        return view('customer/chatbox', $result);
    }
    public function addmsguser(Request $request){
        $sendtype = 'user';
        $sentBy = session()->get('USER_ID');
        $sentname = DB::table('customers')->where('id', session()->get('USER_ID'))->first()->user_id;
        $sid = session()->get('USER_ID');
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
}
