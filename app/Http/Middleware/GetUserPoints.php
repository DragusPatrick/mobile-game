<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Auth;

class GetUserPoints
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
        if($request->session()->has('apikey')){
            $client = new Client();
            // Try API request
            try {
                $apiResponse = $client->request('GET', API_BASE_URL . 'user/info?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey'));

                // Call was successful
                if($apiResponse->getStatusCode() == 200) {
                    try {
                        $response = json_decode($apiResponse->getBody()->getContents());
                        $request->session()->put('points', $response->points);
                        // Store the last level
                        if(!$request->session()->has('last_level')){
                            $request->session()->put('last_level', $response->level);
                            $request->session()->put('show_level_up', false);
                        }
                        $request->session()->put('level', $response->level);
                        // Check if there is a difference between the last level and the new level
                        if($request->session()->get('last_level') != $request->session()->get('level')){
                            // The last level registered is different from the current user level
                            // Register a variable to know that we need to show the level-up screen
                            $request->session()->put('show_level_up', true);
                        }

                        $request->session()->put('user_settings', $response->settings);
                        $request->session()->put('eligible', $response->eligibility->eligible);
                        $request->session()->put('scheme', $response->eligibility->scheme);
                        if($response->eligibility->scheme == 'credits'){
                            $request->session()->put('credits', $response->eligibility->credits);
                        }
                        $request->session()->put('percentageCompleted', $response->percentageCompleted);
                        $request->session()->put('pregames', $response->pregames);
                        $request->session()->put('games', $response->games);
                        $request->session()->put('session_data', $response->session);

                        $playWithCredits = $request->session()->has('play_with_credit') && $request->session()->get('play_with_credit') == true;
                        if(empty($response->session) && $response->eligibility->scheme == "credits" && !$playWithCredits){
                            // Session is not started
                            $request->session()->put('show_play_with_credit', true);
                            return redirect()->to('/');
                        }

                        // Make check for user eligibility here
                        if(!empty($response->session) && $response->session->open == false && $response->eligibility->scheme == "credits"){
                            // User is on the credits scheme -> redirect to buy credits page
                            return redirect()->to('/buy-credits');
                        }
                        if($response->eligibility->eligible != true){
                            if($response->eligibility->scheme == "credits"){
                                // User is on the credits scheme -> redirect to buy credits page
                                return redirect()->to('/buy-credits');
                            } else {
                                // Redirect to not eligible, the user is not on the credits scheme
                                return redirect()->to('/not_eligible');
                            }
                        } else {
                            return $next($request);
                        }

                    } catch (\Exception $e) {
                        return redirect()->to('/error')->with('error', 'Application error: ' . $e->getMessage());
                    }
                }
                else  {
                    return redirect()->to('/error')->with('error', 'Application error');
                }
            } catch (BadResponseException $e) {
                // 401, 403
                return redirect()->to('/init');
            }
        } else {
            return redirect()->to('/signin');
        }
    }
}
