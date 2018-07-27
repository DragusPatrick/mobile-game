<?php
/**
 * Created by PhpStorm.
 * User: laalex
 * Date: 19/12/2017
 * Time: 23:51
 */

namespace App\Http\Middleware;


use Closure;

class CheckForSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($request->session()->has('config')){
            return $next($request);
        } else {
            return redirect()->to('/');
        }
    }

}