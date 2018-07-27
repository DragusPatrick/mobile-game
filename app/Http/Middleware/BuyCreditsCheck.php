<?php
/**
 * Created by PhpStorm.
 * User: laalex
 * Date: 20/12/2017
 * Time: 22:19
 */

namespace App\Http\Middleware;
use Closure;

class BuyCreditsCheck
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

        if($request->session()->has('credits_waiting') && $request->session()->get('credits_waiting') == true ){
            // We're waiting for the credits to load. Check how many credits we have
            if($request->session()->has('credits') && $request->session()->get('credits') == 0){
                return redirect()->to('/credits-wait');
            } else {
                $request->session()->put('credits_waiting', false);
                return $next($request);
            }
        }

        return $next($request);
    }
}
