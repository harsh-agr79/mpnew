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
            if(session()->get('ADMIN_TYPE') == 'admin'){   
                view()->share('admin', DB::table('admins')->where('id', session()->get('ADMIN_ID'))->first());
                view()->share('perms', DB::table('permission')->where('userid', session()->get('ADMIN_ID'))->pluck('perm')->toArray());
            }
            else{
                $perms = DB::table('permission')->where('userid', session()->get('ADMIN_ID'))->pluck('perm')->toArray();
                $perms2 = ['dashboard', 'logout', 'admin/changemode', 'findcustomer', 'finditem', 'getref'];
                $uri =  $url = request()->route()->uri;
                if(in_array($uri, $perms) || in_array($uri, $perms2)){
                    view()->share('admin', DB::table('admins')->where('id', session()->get('ADMIN_ID'))->first());
                    view()->share('perms', DB::table('permission')->where('userid', session()->get('ADMIN_ID'))->pluck('perm')->toArray());
                }  
                else{
                    $request->session()->flash('error','Access Denied');
                    return redirect('/');
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
