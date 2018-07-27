<?php
/**
 * Created by PhpStorm.
 * User: laalex
 * Date: 20/12/2017
 * Time: 22:19
 */

namespace App\Http\Middleware;
use Closure;

class GamesCheck
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
        $games = $request->session()->get('games');
        $canPlayPregames = false;
        foreach ($games as $key => $status){
            if(intval($key) < 4 && ($status == "playable" || $status == "playing")){
                $canPlayPregames = true;
                break;
            }
        }
        if($canPlayPregames){
            return $next($request);
        } else {
            return redirect()->to('/games/play-again-tomorrow');
        }
    }
}