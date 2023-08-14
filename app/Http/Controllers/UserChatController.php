<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;

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
        $ciad = DB::table('chat')->where('sid', session()->get('USER_ID'))->where('channel', $id)->whereIn('sendtype', ['admin', 'staff', 'marketer'])->orderBy('created_at', 'DESC')->first();
        if($ciad == NULL){
            $result['chatidad'] = '';
        }
        else{
            $result['chatidad'] = $ciad->id;
        }
        $cius = DB::table('chat')->where('sid', session()->get('USER_ID'))->where('channel', $id)->where('seen', 'seen')->where('sendtype', 'user')->orderBy('created_at', 'DESC')->first();
        if($cius == NULL){
            $result['chatidus'] = '';
        }
        else{
            $result['chatidus'] = $cius->id;
        }
       
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
        $user = DB::table('customers')->where('id', session()->get('USER_ID'))->first();
        if($user->profileimg == NULL){
            $pimg = 'user.jpg';
        }
        else{
            $pimg = $user->profileimg;
        }

        if($file = $request->file('img')){
            // $file = $request->file('img');
            $ext = $file->getClientOriginalExtension();
            $image_name = time().'chatmsg'.'.'.$ext;
            $image_resize = Image::make($file->getRealPath());
            $image_resize->resize(1000, 1000, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_resize->save('chatimg/'.$image_name);

            $image = 'chatimg/'.$image_name;
            $msgtype = 'img';
        }
        else{
            $image = NULL;
            $msgtype = 'text';
        }

        DB::table('chat')->insert([
            'sid'=>$sid,
            'sendtype'=>$sendtype,
            'sentBy'=>$sentBy,
            'sentname'=>$sentname,
            'channel'=>$channel,
            'message'=>$message,
            'msgtype'=>$msgtype,
            'created_at'=>$time,
            'image'=>$image,
        ]);
        $res = array();
        $ch = DB::table('chat')->where([
            'sid'=>$sid,
            'sendtype'=>$sendtype,
            'sentBy'=>$sentBy,
            'sentname'=>$sentname,
            'channel'=>$channel,
            'message'=>$message,
            'msgtype'=>$msgtype,
            'created_at'=>$time,
            'image'=>$image,
        ])->first();
        $res[] =  [
            'id'=>$ch->id,
            'sid'=>$sid,
            'sendtype'=>$sendtype,
            'sentBy'=>$sentBy,
            'channel'=>$channel,
            'sentname'=>$sentname,
            'message'=>$message,
            'msgtype'=>$msgtype,
            'created_at'=>$time,
            'image'=>$image,
            'profileimg'=> $pimg,
        ];
        return response()->json($res);
    }

    public function seenupdate($id,$channel){
        DB::table('chat')->where('sid', $id)->where('channel', $channel)->whereIn('sendtype', ['admin', 'staff', 'marketer'])->update([
            'seen'=>'seen'
        ]);
        $ciad = DB::table('chat')->where('sid', session()->get('USER_ID'))->where('channel', $channel)->where('seen', 'seen')->whereIn('sendtype', ['admin', 'staff', 'marketer'])->orderBy('created_at', 'DESC')->first();
        if($ciad == NULL){
            $cad = 0;
        }
        else{
            $cad = $ciad->id;
        }
        $cius = DB::table('chat')->where('sid', session()->get('USER_ID'))->where('channel', $channel)->where('sendtype', 'user')->orderBy('created_at', 'DESC')->first();
        if($cius == NULL){
            $cus = 0;
        }
        else{
            $cus = $cius->id;
        }

        $res = [
            'id'=>$id,
            'channel'=>$channel,
            'chatidad'=>$cad,
            'chatidus'=>$cus,
        ];
        return response()->json($res);
    }
    public function getchatlist(){
        $cl = DB::table('chat')->where('sid', session()->get('USER_ID'))->orderBy('created_at', 'DESC')->get()->unique('channel');
        $ncl = DB::table('channels')->whereNotIn('shortname', DB::table('chat')->where('sid', session()->get('USER_ID'))->groupBy('channel')->pluck('channel')->toArray())->get();
        $chr = array();
        foreach($cl as $item){
            $chl = DB::table('channels')->where('shortname', $item->channel)->first();
            $unseen = DB::table('chat')->where('channel', $item->channel)->where('sid', session()->get('USER_ID'))->whereIn('sendtype', ['admin', 'staff', 'marketer'])->where('seen', NULL)->get();
            $chr[] = [
                'shortname'=>$chl->shortname,
                'color'=>$chl->color,
                'name'=>$chl->name,
                'message'=>$item->message,
                'sendtype'=>$item->sendtype,
                // 'seen'=>$item->seen,
                'unseen'=>count($unseen),
            ];
        }
        foreach($ncl as $item){
            $chr[] = [
                'shortname'=>$item->shortname,
                'color'=>$item->color,
                'name'=>$item->name,
                'message'=>'',
                'sendtype'=>'admin',
                // 'seen'=>'seen',
                'unseen'=>0,
            ];
        }
       return response()->json($chr);
    }
    public function getmsgcnt(){
        $cnt = count(DB::table('chat')->where('sid', session()->get('USER_ID'))->whereIn('sendtype', ['admin', 'staff', 'marketer'])->where('seen', NULL)->get());
        return response()->json($cnt);
    }
}
