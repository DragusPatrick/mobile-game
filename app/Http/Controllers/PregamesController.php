<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 27/11/2017
 * Time: 11:25
 */

namespace App\Http\Controllers;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;

class PregamesController extends Controller {

    public function index(Request $request) {
        if($request->session()->has('apikey')){
            $client = new Client();
            try {
                $apiResponse = $client->request('GET', API_BASE_URL . 'pregames?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey'));

                // Call was successful
                if($apiResponse->getStatusCode() == 200) {
                    try {
                        $response = json_decode($apiResponse->getBody()->getContents());
                        $request->session()->put('pregames_data', $response);

                        // Get user level
                        $userLevel = null;
                        foreach(session('config.levels') as $level){
                            if(session('level') == $level->id){
                                $userLevel = $level;
                            }
                        }

                        return view('pages.pregames_1', [
                            'pregames' => $request->session()->get('pregames'),
                            'pregames_data' => $response,
                            'user_level' => $userLevel
                        ]);

                    } catch (\Exception $e) {
                        return redirect()->to('/error')->with('error', 'Application error');
                    }
                }
                else  {
                    dd("not 200");
                    return redirect()->to('/error')->with('error', 'Application error');
                }
            } catch (BadResponseException $e) {
                // 401, 403
                return redirect()->to('/logout');
            }
        } else {
            return redirect()->to('/signin');
        }
    }

    public function spin_the_wheel(Request $request) {
        if($request->session()->has('apikey')){
            $client = new Client();
            try {
                $apiResponse = $client->request('POST', API_BASE_URL . 'pregames/pick?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey'), [
                    'form_params' => [
                        'id' => $request->id
                    ]
                ]);

                // Call was successful
                if($apiResponse->getStatusCode()){
                    try {
                        $response = json_decode($apiResponse->getBody()->getContents());
                        $request->session()->put('playing_pregame', $request->id);
                        $request->session()->put('playing_pregame_code', $request->code);
                        $request->session()->put('playing_pregame_prize_info', $response);
                        // Get pregame information
                        foreach($request->session()->get('pregames_data') as $pregame){
                            if($pregame->id = $request->id){
                                $request->session()->put('playing_pregame_data', $pregame);
                            }
                        }
                        return view('pages.spin_the_wheel', [
                            'pregame' => $pregame
                        ]);
                    } catch (\Exception $e) {
                        return redirect()->to('/error')->with('error', 'Application error');
                    }
                } else {
                    return redirect()->to('/error')->with('error', 'Application error');
                }
            } catch (BadResponseException $e) {
                // 401, 403
                if($e->getCode() === 409){
                    $request->session()->put('playing_pregame', $request->id);
                    // Get pregame information
                    $pregame_data = $request->session()->get('pregames_data');
                    $current_pregame = null;
                    foreach($pregame_data as $pregame){
                        if($pregame->id == $request->id && $pregame->code == $request->code){
                            $current_pregame = $pregame;
                        }
                    }
                    if(is_null($current_pregame)){
                        return redirect()->to('/error')->with('error', 'Application error');
                    } else {
                        $request->session()->put('playing_pregame_data', $current_pregame);
                        return view('pages.spin_the_wheel', [
                            'pregame' => $current_pregame
                        ]);
                    }
                } else {
                    return redirect()->to('/error')->with('error', 'Application error');
                }
            }
        } else {
            return redirect()->to('/signin');
        }
    }

    public function spinning(Request $request) {
        if($request->session()->has('apikey')){
            $client = new Client();
            try {
                $apiResponse = $client->request('POST', API_BASE_URL . 'pregames/play?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey'), [
                    'form_params' => [
                        'id' => $request->id
                    ]
                ]);

                // Call was successful
                if($apiResponse->getStatusCode()){
                    try {
                        $response = json_decode($apiResponse->getBody()->getContents());
                        $currentPrize = $request->session()->get('playing_pregame_prize_info');
                        $pregameData = $request->session()->get('playing_pregame_data');
                        foreach($pregameData->prizes as $prize){
                            if($prize->id == $currentPrize->prizeId){
                                $request->session()->put('playing_pregame_prize', $prize);
                            }
                        }
                        return view('pages.spinning', [
                            'id' => $request->id,
                            'code' => $request->code
                        ]);
                    } catch (\Exception $e) {
                        return redirect()->to('/error')->with('error', 'Application error');
                    }
                } else {
                    return redirect()->to('/error')->with('error', 'Application error');
                }
            } catch (BadResponseException $e) {
                // 401, 403
                if($e->getCode() === 409){
                    return view('pages.spinning', [
                        'id' => $request->id,
                        'code' => $request->code
                    ]);
                } else {
                    return redirect()->to('/error')->with('error', 'Application error');
                }
            }
        } else {
            return redirect()->to('/signin');
        }
    }



    public function result(Request $request) {
        // Get user level
        $userLevel = null;
        foreach(session('config.levels') as $level){
            if(session('level') == $level->id){
                $userLevel = $level;
            }
        }

        if(empty(session()->get('playing_pregame_prize'))){
            $prizeId = session()->get('session_data')->prizeId;
            foreach(session()->get('config.prizes') as $prizeIterator){
                if($prizeIterator->id == $prizeId){
                    session()->put('playing_pregame_prize', $prizeIterator);
                }
            }
        }

        $prizeData = $request->session()->get('playing_pregame_prize');

        return view('pages.pregames_result', [
            'prize' => $prizeData,
            'user_level' => $userLevel
        ]);
    }

    public function scratch_cards(Request $request) {
        if($request->session()->has('apikey')){
            $client = new Client();
            try {
                $apiResponse = $client->request('POST', API_BASE_URL . 'pregames/pick?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey'), [
                    'form_params' => [
                        'id' => $request->id
                    ]
                ]);

                // Call was successful
                if($apiResponse->getStatusCode()){
                    try {
                        $response = json_decode($apiResponse->getBody()->getContents());
                        $request->session()->put('playing_pregame', $request->id);
                        $request->session()->put('playing_pregame_code', $request->code);
                        $request->session()->put('playing_pregame_prize_info', $response);
                        // Get pregame information
                        foreach($request->session()->get('pregames_data') as $pregame){
                            if($pregame->id == $request->id){
                                $request->session()->put('playing_pregame_data', $pregame);
                            }
                        }
                        $prizeId = $request->session()->get('playing_pregame_prize_info')->prizeId;
                        return view('pages.scratch_cards', [
                            'pregame' => $pregame,
                            'prize_id' => $prizeId
                        ]);
                    } catch (\Exception $e) {
                        return redirect()->to('/error')->with('error', 'Application error');
                    }
                } else {
                    return redirect()->to('/error')->with('error', 'Application error');
                }
            } catch (BadResponseException $e) {
                // 401, 403
                if($e->getCode() === 409){
                    return redirect()->to('/games');
                } else {
                    return redirect()->to('/error')->with('error', 'Application error');
                }
            }
        } else {
            return redirect()->to('/signin');
        }
    }

    public function scratch_cards_result(Request $request) {
        if($request->session()->has('apikey')){
            $client = new Client();
            try {
                $apiResponse = $client->request('POST', API_BASE_URL . 'pregames/play?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey'), [
                    'form_params' => [
                        'id' => $request->id
                    ]
                ]);

                // Call was successful
                if($apiResponse->getStatusCode()){
                    try {
                        $response = json_decode($apiResponse->getBody()->getContents());
                        $pregame = $request->session()->get('playing_pregame_data');
                        $prizeId = $request->prizeid;
                        $selected_prize = null;
                        foreach($pregame->prizes as $prize){
                            if($prize->id == $prizeId){
                                $selected_prize = $prize;
                            }
                        }
                        $request->session()->put('playing_pregame_prize', $selected_prize);
                        return view('pages.scratch_cards_result', [
                            'pregame' => $pregame,
                            'prize_id' => $prizeId,
                            'selected_prize' => $selected_prize,
                            'id' => $request->id,
                            'code' => $request->code,
                            'selectedIndex' => $request->index
                        ]);
                    } catch (\Exception $e) {
                        return redirect()->to('/error')->with('error', 'Application error');
                    }
                } else {
                    return redirect()->to('/error')->with('error', 'Application error');
                }
            } catch (BadResponseException $e) {
                // 401, 403
                if($e->getCode() === 409){
                    $request->session()->put('playing_pregame', $request->id);
                    // Get pregame information
                    $pregame = $request->session()->get('playing_pregame_data');
                    $prizeId = $request->prizeid;
                    foreach($pregame->prizes as $prize){
                        if($prize->id == $prizeId){
                            $selected_prize = $prize;
                        }
                    }
                    $request->session()->put('playing_pregame_prize', $selected_prize);
                    return view('pages.scratch_cards_result', [
                        'pregame' => $pregame,
                        'prize_id' => $prizeId,
                        'selected_prize' => $selected_prize,
                        'id' => $request->id,
                        'code' => $request->code
                    ]);
                } else {
                    return redirect()->to('/error')->with('error', 'Application error');
                }
            }
        } else {
            return redirect()->to('/signin');
        }
    }
}
