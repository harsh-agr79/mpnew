<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CustomerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->session()->has('USER_LOGIN')){
            view()->share('user', DB::table('customers')->where('id', session()->get('USER_ID'))->first());
            view()->share('msgcnt', count(DB::table('chat')->where('sid', session()->get('USER_ID'))->whereIn('sendtype', ['admin', 'staff', 'marketer'])->where('seen', NULL)->get()));
        }
        else{
            $request->session()->flash('error','Access Denied');
            return redirect('/');
        }
        return $next($request);
    }
}
