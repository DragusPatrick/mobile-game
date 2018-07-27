<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class PrizesController extends Controller {

    public function init(Request $request) {
        $client = new Client();
        // Try API request
        try {
            $apiResponse = $client->request('GET',API_BASE_URL . 'init?uuid=' . $request->cookie('uuid'));

            // Call was successful
            if($apiResponse->getStatusCode() == 200) {
                try {
                    $response = json_decode($apiResponse->getBody()->getContents());
                    $request->session()->put('grand_prize_name', $response->settings->content->grandPrize->name);
                    $request->session()->put('grand_prize_image', $response->settings->content->grandPrize->image);
                    $request->session()->put('config.prizes', $response->prizes);
                    $request->session()->put('config.levels', $response->levels);
                    $request->session()->put('config.texts', $response->texts);
                    $request->session()->put('config.prefix', $response->settings->msisdn->prefix);
                    $request->session()->put('config.credits', $response->creditPackages);
                    // Initialize the show play with credit page and play with credit check
                    if(false == $request->session()->has('play_with_credit')){
                        $request->session()->put('play_with_credit', false);
                    }
                    if(false == $request->session()->has('show_play_with_credit')){
                        $request->session()->put('show_play_with_credit', false);
                    } else {
                        if($request->session()->get('show_play_with_credit') == true){
                            // Show the play with credits page
                            return view('pages.play_again_credit');
                        }
                    }

                    // Check if user played pregame
                    if($request->session()->has('pregame_played') && $request->session()->get('pregame_played') == "yes"){
                        return redirect()->to('/games');
                    } else {
                        return redirect()->to('/init')->cookie('uuid', $this->generate_uuid(), 60*24*365);
                    }
                } catch (Exception $e) {
                    return redirect()->to('/error')->with('error', 'Game init error');
                }
            }
            else  {
                return redirect()->to('/error')->with('error', 'Game init error');
            }

        } catch (BadResponseException $e) {
            // 401, 403
            return redirect()->to('/logout');
        }
    }

    // info 1
    public function grand_prize(Request $request) {
        if($request->has('bypass')){
            return view('pages.grand_prize', [
                'name' => $request->session()->get('grand_prize_name'),
                'image' => $request->session()->get('grand_prize_image')
            ]);
        }

        // Check if the user has user_settings, otherwise create it
        if($request->session()->has('user_settings') == false){
            // Make request to get user settings from the user/info API call
            $client = new Client();
            // Try API request
            try {
                $settings_array["settings"]["show_homepage"] = 0;
                $apiResponse = $client->request('GET', API_BASE_URL . 'user/info?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey'));

                // Call was successful
                if($apiResponse->getStatusCode() == 200) {
                    try {
                        $response = json_decode($apiResponse->getBody()->getContents());
                        // Store information into the session
                        if(count($response->settings) > 0){
                            session()->put('user_settings', $response->settings);
                        }
                    } catch (Exception $e) {
                        // DO NOTHING
                    }
                }
                else  {
                    // DO NOTHING
                }
            } catch (BadResponseException $e) {
                // DO NOTHING
            }
        }
        // Further process the request
        if($request->session()->has('user_settings') && $request->session()->get('user_settings') && $request->session()->get('user_settings')->show_homepage == "0"){
            if($request->session()->has('pregame_played') && $request->session()->get('pregame_played') == "yes"){
                return redirect()->to('/games');
            }
            else {
                return redirect()->to('/pregames-1');
            }
        }


        return view('pages.grand_prize', [
            'name' => $request->session()->get('grand_prize_name'),
            'image' => $request->session()->get('grand_prize_image')
        ]);

    }

    // info 2
    public function on_boarding_first(Request $request) {
        if($request->has('bypass')){
            return view('pages.on_boarding_first');
        }

        if($request->session()->has('user_settings') && $request->session()->get('user_settings') && $request->session()->get('user_settings')->show_homepage == "0"){
            if($request->session()->has('pregame_played') && $request->session()->get('pregame_played') == "yes"){
                return redirect()->to('/games');
            }
            else {
                return redirect()->to('/pregames-1');
            }
        }
        return view('pages.on_boarding_first');
    }

    // info 3
    public function on_boarding_second(Request $request) {
        if($request->has('bypass')){
            return view('pages.on_boarding_second');
        }

        if($request->session()->has('user_settings') && $request->session()->get('user_settings') && $request->session()->get('user_settings')->show_homepage == "0"){
            if($request->session()->has('pregame_played') && $request->session()->get('pregame_played') == "yes"){
                return redirect()->to('/games');
            }
            else {
                return redirect()->to('/pregames-1');
            }
        }

        $client = new Client();
        // Try API request
        try {
            $settings_array["settings"]["show_homepage"] = 0;
            $apiResponse = $client->request('PUT', API_BASE_URL . 'user/info?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey'), [
                    'form_params' => $settings_array
                ]);

            // Call was successful
            if($apiResponse->getStatusCode() == 200) {
                try {
                    $response = json_decode($apiResponse->getBody()->getContents());
                    // redirect to pregames
                    // return redirect()->to('/pregames-1');
                } catch (Exception $e) {
                    // return redirect()->to('/pregames-1')->with('error', 'Game init error');
                }
            }
            else  {
                // return redirect()->to('/pregames-1')->with('error', 'Game init error');
            }
        } catch (BadResponseException $e) {
            // 401, 403
            // return redirect()->to('/logout');
        }

        return view('pages.on_boarding_second');
    }

    // info 4
    public function on_boarding_third(Request $request) {
        if($request->has('bypass')){
            return view('pages.on_boarding_third');
        }

        if($request->session()->has('user_settings') && $request->session()->get('user_settings') && $request->session()->get('user_settings')->show_homepage == "0"){
            if($request->session()->has('pregame_played') && $request->session()->get('pregame_played') == "yes"){
                return redirect()->to('/games');
            }
            else {
                return redirect()->to('/pregames-1');
            }
        }

        $client = new Client();
        // Try API request
        try {
            $settings_array["settings"]["show_homepage"] = 0;
            $apiResponse = $client->request('PUT', API_BASE_URL . 'user/info?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey'), [
                'form_params' => $settings_array
            ]);

            // Call was successful
            if($apiResponse->getStatusCode() == 200) {
                try {
                    $response = json_decode($apiResponse->getBody()->getContents());
                    // redirect to pregames
                    // return redirect()->to('/pregames-1');
                } catch (Exception $e) {
                    // return redirect()->to('/pregames-1')->with('error', 'Game init error');
                }
            }
            else  {
                // return redirect()->to('/pregames-1')->with('error', 'Game init error');
            }
        } catch (BadResponseException $e) {
            // 401, 403
            // return redirect()->to('/logout');
        }

        return view('pages.on_boarding_second');
    }


    private function generate_uuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }
}
