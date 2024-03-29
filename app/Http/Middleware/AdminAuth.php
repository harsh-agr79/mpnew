<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->session()->has('ADMIN_LOGIN') && in_array(session()->get('ADMIN_TYPE'), ['admin', 'staff'])){
            $time = session()->get('ADMIN_TIME');
            $cutime = time();
            if(DB::table('admins')->where('id', session()->get('ADMIN_ID'))->first()->disabled == "on"){
                return redirect('/err');
            }
            else{
                if($cutime - $time <= 3600){
                    if(session()->get('ADMIN_TYPE') == 'admin'){   
                        view()->share('admin', DB::table('admins')->where('id', session()->get('ADMIN_ID'))->first());
                        view()->share('perms', DB::table('permission')->where('userid', session()->get('ADMIN_ID'))->pluck('perm')->toArray());
                        view()->share('msgcnt', count(DB::table('chat')->where('sendtype', 'user')->where('seen', NULL)->get()));
                    }
                    else{
                        $perms = DB::table('permission')->where('userid', session()->get('ADMIN_ID'))->pluck('perm')->toArray();
                        $perms2 = ['dashboard', 'logout', 'admin/changemode', 'findcustomer', 'finditem', 'getref'. 'custupdate'];
                        $uri =  $url = request()->route()->uri;
                        if(in_array($uri, $perms) || in_array($uri, $perms2)){
                            if($uri == 'chats/{id}/{id2}' || $uri == 'admin/m/chats/{id}/{id2}'){
                                $url = url()->current();
                                $channel = substr($url, strrpos($url, '/' )+1) ;
                                if(in_array($channel, $perms)){
                                    view()->share('admin', DB::table('admins')->where('id', session()->get('ADMIN_ID'))->first());
                                    view()->share('perms', DB::table('permission')->where('userid', session()->get('ADMIN_ID'))->pluck('perm')->toArray());
                                view()->share('msgcnt', count(DB::table('chat')->where('sendtype', 'user')->where('seen', NULL)->get()));
                                }
                                else{
                                    $request->session()->flash('error','Access Denied');
                                    return redirect('/');
                                }
                            }
                            else{
                                view()->share('admin', DB::table('admins')->where('id', session()->get('ADMIN_ID'))->first());
                                view()->share('perms', DB::table('permission')->where('userid', session()->get('ADMIN_ID'))->pluck('perm')->toArray());
                                view()->share('msgcnt', count(DB::table('chat')->where('sendtype', 'user')->where('seen', NULL)->get()));
                            }
                         
                        }  
                        else{
                            $request->session()->flash('error','Access Denied');
                            return redirect('/');
                        }
                    }
                }
                else{
                    return redirect('/logout');
                }
            }
            
            
        }
        else{
            $request->session()->flash('error','Access Denied');
            return redirect('/');
        }
        return $next($request);
    }
}
