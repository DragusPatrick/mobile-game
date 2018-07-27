<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 27/11/2017
 * Time: 11:25
 */

namespace App\Http\Controllers;


use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;

class GamesController extends Controller {

    public function index(Request $request){
        if($request->session()->has('apikey')){

            $client = new Client();
            try {
                $apiResponse = $client->request('GET', API_BASE_URL . 'games?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey'));

                // Call was successful
                if($apiResponse->getStatusCode() == 200) {
                    try {
                        $response = json_decode($apiResponse->getBody()->getContents());
                        $request->session()->put('games_data', $response);
                        // Get user level
                        $userLevel = null;
                        foreach(session('config.levels') as $level){
                            if(session('level') == $level->id){
                                $userLevel = $level;
                            }
                        }

                        $games = $request->session()->get('games');
                        $games_data = $response;
                        $games_data = array_filter($games_data, function($item){
                           return in_array($item->code, ['memory-game', 'spot-the-difference', 'drag-and-drop']);
                        });

                        if($request->session()->has('session_data') && !empty($request->session()->get('session_data'))){
                            $prizeInfo = get_prize_info(session('session_data')->prizeId);

                            return view('pages.selectgame_2', [
                                'games' => $games,
                                'games_data' => $games_data,
                                'user_level' => $userLevel,
                                'user_session' => session('session_data'),
                                'prize' => $prizeInfo
                            ]);
                        }
                        if(empty($request->session()->get('session_data')) && $request->session()->get('credits') > 0){
                            $request->session()->put('show_play_with_credit', true);
                            $request->session()->put('play_with_credit', false);
                            // Show play again for one credit screen
                            return view('pages.play_again_credit');
                        } else {
                            return redirect()->to('buy-credits');
                        }

                    } catch (\Exception $e) {
                        dd("JSON exception");
                        return redirect()->to('/error')->with('error', 'Application error');
                    }
                }
                else  {
                    dd("code not 200");
                    return redirect()->to('/error')->with('error', 'Application error');
                }
            } catch (BadResponseException $e) {
                // 401, 403
                dd("403", $e->getMessage());
//                return redirect()->to('/logout');
            }
        } else {
            return redirect()->to('/signin');
        }
    }

    public function game_finished(Request $request){
        $finished_result_data = $request->session()->get('game_finished_result');
        $prize_type = 'text';
        $prize_id = $finished_result_data->prize->id;
        $won_prize_data = "";
        foreach($request->session()->get('config.prizes') as $prize){
            if($prize->id == $prize_id){
                $won_prize_data = $prize;
                if($prize->type == 2){
                    $prize_type = 'image';
                    break;
                }
            }
        }
        return view('pages.playagain_811', [
            'data' => $finished_result_data,
            'prize_type' => $prize_type,
            'prize_data' => $won_prize_data,
            'show_level_up' => session('show_level_up')
        ]);
    }

    public function game_failed(Request $request){
        return view('pages.sorry');
    }

    public function level_up(Request $request){
        // Store the new level
        $request->session()->put('last_level', $request->session()->get('level'));
        // Show level up screen
        return view('pages.level_up', [
            'redirect' => '/games',
        ]);
    }

    public function play_again_tomorrow(Request $request){
        if(session()->has('session_data') && false == empty(session('session_data')->playAgain)){
            $date = Carbon::parse(session('session_data')->playAgain);
            $play_in = $date->format('d.m.Y');
            return view('pages.play_again_in', [
                'name' => $request->session()->get('grand_prize_name'),
                'image' => $request->session()->get('grand_prize_image'),
                'play_in' => $play_in
            ]);
        }
        else
        {
            return redirect()->to('/init');
        }
    }

}
