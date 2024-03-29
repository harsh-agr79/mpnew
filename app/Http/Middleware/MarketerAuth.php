<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class MarketerAuth {
    /**
    * Handle an incoming request.
    *
    * @param  \Closure( \Illuminate\Http\Request ): ( \Symfony\Component\HttpFoundation\Response )  $next
    */

    public function handle( Request $request, Closure $next ): Response {
        if ( $request->session()->has( 'ADMIN_LOGIN' ) && in_array( session()->get( 'ADMIN_TYPE' ), [ 'marketer' ] ) ) {
            $time = session()->get( 'ADMIN_TIME' );
            $cutime = time();
            if ( DB::table( 'admins' )->where( 'id', session()->get( 'ADMIN_ID' ) )->first()->disabled == 'on' ) {
                return redirect('/err');
            } else {
                if ( $cutime - $time <= 3600 ) {
                    view()->share( 'admin', DB::table( 'admins' )->where( 'id', session()->get( 'ADMIN_ID' ) )->first() );
                    view()->share( 'perms', DB::table( 'permission' )->where( 'userid', session()->get( 'ADMIN_ID' ) )->pluck( 'perm' )->toArray() );
                } else {
                    return redirect( '/logout' );
                }
            }

           
        } else {
            $request->session()->flash( 'error', 'Access Denied' );
            return redirect( '/' );
        }
        return $next( $request );
    }
}
