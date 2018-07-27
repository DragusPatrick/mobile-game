<?php
/**
 * Created by PhpStorm.
 * User: laalex
 * Date: 20/12/2017
 * Time: 22:19
 */

namespace App\Http\Middleware;
use Closure;

class PregamesCheck
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
        $pregames = $request->session()->get('pregames');
        $canPlayPregames = false;
        foreach ($pregames as $key => $status){
            if($status == "playable"){
                $canPlayPregames = true;
                break;
            }
        }
        if($canPlayPregames){
            return $next($request);
        } else {
            return redirect()->to('/games');
        }
    }
}
