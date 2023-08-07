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
}
