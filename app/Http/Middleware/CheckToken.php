<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $header= request()->header('Authorization');
        $user=User::where('AUTHORIZATION',$header)->get('otp');
//        dd($user);
        if (! $user==null){
            return $next($request);
        }
        else {
            return redirect()->route('failed_logout');
        }
    }
}
