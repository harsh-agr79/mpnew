<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;

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
            $ciad = DB::table('chat')->where('sid', $id)->where('channel', $id2)->whereIn('sendtype', ['admin', 'staff', 'marketer'])->orderBy('created_at', 'DESC')->first();
            if($ciad == NULL){
                $result['chatidad'] = '';
            }
            else{
                $result['chatidad'] = $ciad->id;
            }
            $cius = DB::table('chat')->where('sid', $id)->where('channel', $id2)->where('seen', 'seen')->where('sendtype', 'user')->orderBy('created_at', 'DESC')->first();
            if($cius == NULL){
                $result['chatidus'] = '';
            }
            else{
                $result['chatidus'] = $cius->id;
            }

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
        ];
        return response()->json($res);
    }

    public function getchatlist(){
        if(session()->get('ADMIN_TYPE') == 'staff'){
            $channels = DB::table('channel')->get();
            $perms = DB::table('permission')->where('userid', session()->get('ADMIN_ID'))->pluck('perm')->toArray();
            $chn = array();
            foreach($channels as $item){
                if(in_array($item->shortname, $perms)){
                    array_push($chn, $item->shortname);
                }
            }
        $res =DB::table('chat')->whereIn('channel', $chn)->join('customers', 'customers.id', '=', 'chat.sid')->orderBy('chat.created_at', 'DESC')->get()->unique('sid');
        }
        else{
            $res =DB::table('chat')->join('customers', 'customers.id', '=', 'chat.sid')->orderBy('chat.created_at', 'DESC')->get()->unique('sid');
        }
      
        $chr = array();
        foreach($res as $item){
            $unseen = count(DB::table('chat')->where('sid', $item->sid)->where('sendtype', 'user')->where('seen', NULL)->get());
            $chr[] = [
                'profileimg'=>$item->profileimg,
                'sid'=>$item->sid,
                'seen'=>$item->seen,
                'name'=>$item->name,
                'sendtype'=>$item->sendtype,
                'channel'=>$item->channel,
                'message'=>$item->message,
                'unseen'=>$unseen
            ];
        }
        return response()->json($chr);
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
    public function seenupdate($id,$channel){
        DB::table('chat')->where('sid', $id)->where('channel', $channel)->where('sendtype','user')->update([
            'seen'=>'seen'
        ]);
        $ciad = DB::table('chat')->where('sid', $id)->where('channel', $channel)->whereIn('sendtype', ['admin', 'staff', 'marketer'])->where('seen', 'seen')->orderBy('created_at', 'DESC')->first();
            if($ciad == NULL){
                $chatidad = 0;
            }
            else{
                $chatidad  = $ciad->id;
            }
            $cius = DB::table('chat')->where('sid', $id)->where('channel', $channel)->where('sendtype', 'user')->orderBy('created_at', 'DESC')->first();
            if($cius == NULL){
                $chatidus = 0;
            }
            else{
                $chatidus = $cius->id;
            }

        $res = [
            'id'=>$id,
            'channel'=>$channel,
            'chatidad'=>$chatidad,
            'chatidus'=>$chatidus,
        ];
        return response()->json($res);
    }
    public function getuserchannel(Request $request, $id){
        $channel = DB::table('channels')->get();
        $res = array();
        foreach($channel as $item){
           $uns = DB::table('chat')->where('sid', $id)->where('sendtype', 'user')->where('channel', $item->shortname)->where('seen', NULL)->get();
            $unseen = count($uns);
            $res[] = [
                'channel'=>$item->name,
                'color'=>$item->color,
                'shortname'=>$item->shortname,
                'unseen'=>$unseen,
            ];
        }
        return response()->json($res);
    }

    public function mchatlist(Request $request){
        $result['allchats'] = DB::table('chat')->orderBy('created_at', 'DESC')->get()->unique('sid');
        return view('adminchat/chatlist', $result);
    }
    public function madminchat(Request $request,$id,$id2){
        $result['chat'] = DB::table('chat')->where('sid', $id)->where('channel', $id2)->orderBy('created_at', 'ASC')->get();
        $result['user'] = DB::table('customers')->where('id',$id)->first();
        $result['channels'] = DB::table('channels')->get();
        $result['channel'] = $id2;
        $ciad = DB::table('chat')->where('sid', $id)->where('channel', $id2)->whereIn('sendtype', ['admin', 'staff', 'marketer'])->orderBy('created_at', 'DESC')->first();
        if($ciad == NULL){
            $result['chatidad'] = '';
        }
        else{
            $result['chatidad'] = $ciad->id;
        }
        $cius = DB::table('chat')->where('sid', $id)->where('channel', $id2)->where('seen', 'seen')->where('sendtype', 'user')->orderBy('created_at', 'DESC')->first();
        if($cius == NULL){
            $result['chatidus'] = '';
        }
        else{
            $result['chatidus'] = $cius->id;
        }

        return view('adminchat/chatbox', $result);
    }

    public function getmsgcnt(){
        $cnt = count(DB::table('chat')->where('sendtype', 'user')->where('seen', NULL)->get());
        return response()->json($cnt);
    }
}
