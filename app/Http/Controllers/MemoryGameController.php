<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class MemoryGameController extends Controller
{
    public function start(Request $request){
        if($request->session()->has('apikey')){

            $client = new Client();
            try {
                $apiResponse = $client->request('POST', API_BASE_URL . 'games/start?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey'), [
                    'form_params' => [
                        'id' => $request->id
                    ]
                ]);
                // Call was successful
                if($apiResponse->getStatusCode() == 200) {
                    try {
                        $response = json_decode($apiResponse->getBody()->getContents());
                        $request->session()->put('memory_game_info', $response);

                        $gameData = null;
                        foreach($request->session()->get('games_data') as $game){
                            if($game->code == 'memory-game'){
                                $gameData = $game;
                            }
                        }
                        $gameBoxesFirst = $gameData->data->boxes;
                        $gameBoxesSecond = $gameData->data->boxes;
                        shuffle($gameBoxesFirst);
                        shuffle($gameBoxesSecond);
                        $gameBoxes = array_merge($gameBoxesFirst, $gameBoxesSecond);
                        $gameState["game_data"] = $gameData;
                        $gameState["boxes"] = $gameBoxes;
                        $gameState["matched_boxes"] = [];
                        $gameState["selected_boxes"] = [];
                        $gameState["refresh_page"] = false;
                        $gameState["game_finished"] = false;
                        $gameState["preview"] = false;
                        // Put game session in memory
                        $request->session()->put('memory_game_state', $gameState);
                        // Redirect to preview game cards
                        return redirect()->to('/games/memory-game-preview');

                    } catch (\Exception $e) {
                        return redirect()->to('/error')->with('error', 'Application error');
                    }
                }
                else  {
                    return redirect()->to('/error')->with('error', 'Application error');
                }
            } catch (BadResponseException $e) {
                $game_state = $request->session()->get('memory_game_state');
                if(!empty($game_state) && !empty($game_state["preview"]) && $game_state["preview"] == false){
                    return redirect()->to('/games/memory-game-preview');
                } else {
                    // Check if the session has a memory game state
                    if($request->session()->has('memory_game_state')){
                        // Fix for bug when all the state is already completed
                        $gameState = $request->session()->get('memory_game_state');
                        $gameState['matched_boxes'] = [];
                        $request->session()->put('memory_game_state', $gameState);
                        // Redirect to play as expected
                        return redirect()->to('/games/memory-game-play');
                    } else {
                        // Build new memory game state from games information
                        $gameData = null;
                        foreach($request->session()->get('games_data') as $game){
                            if($game->code == 'memory-game'){
                                $gameData = $game;
                            }
                        }
                        $request->session()->put('memory_game_info', $gameData);

                        $gameBoxesFirst = $gameData->data->boxes;
                        $gameBoxesSecond = $gameData->data->boxes;
                        shuffle($gameBoxesFirst);
                        shuffle($gameBoxesSecond);
                        $gameBoxes = array_merge($gameBoxesFirst, $gameBoxesSecond);
                        $gameState["game_data"] = $gameData;
                        $gameState["boxes"] = $gameBoxes;
                        $gameState["matched_boxes"] = [];
                        $gameState["selected_boxes"] = [];
                        $gameState["refresh_page"] = false;
                        $gameState["game_finished"] = false;
                        $gameState["preview"] = true;
                        // Put game session in memory
                        $request->session()->put('memory_game_state', $gameState);
                        return redirect()->to('/games/memory-game-play');
                    }
                }
            }
        } else {
            return redirect()->to('/signin');
        }
    }

    public function preview(Request $request){
        $gameData = $request->session()->get('memory_game_state')["game_data"];
        $boxes = $request->session()->get('memory_game_state')["boxes"];
        $matched_boxes = $request->session()->get('memory_game_state')["matched_boxes"];
        $selectedBoxes = $request->session()->get('memory_game_state')["selected_boxes"];
        $refreshPage = $request->session()->get('memory_game_state')["refresh_page"];
        $gameFinished = $request->session()->get('memory_game_state')["game_finished"];
        $preview = $request->session()->get('memory_game_state')["preview"];
        $prizeInfo = get_prize_info(session('session_data')->prizeId);
        if(false == $preview){
            return view('pages.memory_game_preview', [
                'game_data' => $gameData,
                'boxes' => $boxes,
                'counter' => 0,
                'matched_boxes' => $matched_boxes,
                'selected_boxes' => $selectedBoxes,
                'refresh_page' => $refreshPage,
                'game_finished' => $gameFinished,
                'prize' => $prizeInfo
            ]);
        } else {
            return redirect()->to('/games/memory-game-play');
        }

    }

    public function play(Request $request){
        $gameData = $request->session()->get('memory_game_state')["game_data"];
        $boxes = $request->session()->get('memory_game_state')["boxes"];
        $matched_boxes = $request->session()->get('memory_game_state')["matched_boxes"];
        $selectedBoxes = $request->session()->get('memory_game_state')["selected_boxes"];
        $refreshPage = $request->session()->get('memory_game_state')["refresh_page"];
        $gameFinished = $request->session()->get('memory_game_state')["game_finished"];
        $prizeInfo = get_prize_info(session('session_data')->prizeId);
        if(count($matched_boxes) == 12){
            return redirect()->to('/games/memory-game-finish');
        } else {
            return view('pages.memory_game', [
                'game_data' => $gameData,
                'boxes' => $boxes,
                'counter' => 0,
                'matched_boxes' => $matched_boxes,
                'selected_boxes' => $selectedBoxes,
                'refresh_page' => $refreshPage,
                'game_finished' => $gameFinished,
                'prize' => $prizeInfo
            ]);
        }
    }


    public function select(Request $request){
        $curentSelection = $request->select;
        $game_state = $request->session()->get('memory_game_state');
        array_push($game_state['selected_boxes'], $curentSelection);
        if(count($game_state['selected_boxes']) == 2){
            // Check if the selections match
            if($game_state['boxes'][$game_state['selected_boxes'][0]]->id == $game_state['boxes'][$game_state['selected_boxes'][1]]->id){
                // if selections match add them to matched_boxes in the session
                $game_state['matched_boxes'] = array_merge($game_state['matched_boxes'], $game_state['selected_boxes']);
                $game_state['selected_boxes'] = [];
                $request->session()->put('memory_game_state', $game_state);
                return redirect()->to('/games/memory-game-play');
            } else {
                $request->session()->put('memory_game_state', $game_state);
                // Redirect to reload selections if they match or don't match
                return redirect()->to('/games/memory-game-intermediary');
            }
        } else {
            $request->session()->put('memory_game_state', $game_state);
            return redirect()->to('/games/memory-game-play');
        }
    }

    public function intermediary_selections(Request $request){
        $gameData = $request->session()->get('memory_game_state')["game_data"];
        $boxes = $request->session()->get('memory_game_state')["boxes"];
        $matched_boxes = $request->session()->get('memory_game_state')["matched_boxes"];
        $selectedBoxes = $request->session()->get('memory_game_state')["selected_boxes"];
        $refreshPage = $request->session()->get('memory_game_state')["refresh_page"];
        $gameFinished = $request->session()->get('memory_game_state')["game_finished"];
        $prizeInfo = get_prize_info(session('session_data')->prizeId);
        return view('pages.memory_game_reload', [
            'game_data' => $gameData,
            'boxes' => $boxes,
            'counter' => 0,
            'matched_boxes' => $matched_boxes,
            'selected_boxes' => $selectedBoxes,
            'refresh_page' => $refreshPage,
            'game_finished' => $gameFinished,
            'prize' => $prizeInfo
        ]);
    }

    public function reload_selections(Request $request){
        $game_state = $request->session()->get('memory_game_state');
        $game_state['selected_boxes'] = [];
        $request->session()->put('memory_game_state', $game_state);
        return redirect()->to('/games/memory-game-play');
    }

    public function finish(Request $request){
        $game_state = $request->session()->get('memory_game_state');
        $game_state['game_finished'] = true;
        $request->session()->put('memory_game_state', $game_state);
        $gameId = $game_state['game_data']->id;

        // Check if the current playing game exists in the session
        if(isset(session('session_data')->playing) && isset(session('session_data')->playing->{"${gameId}"})){
            $gamePlayId = session('session_data')->playing->{"${gameId}"};
            if($request->session()->has('apikey')){
                $client = new Client();
                try {
                    $apiResponse = $client->request('POST', API_BASE_URL . 'games/finish?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey'), [
                        'form_params' => [
                            'gamePlayId' => $gamePlayId,
                            'time' => 59
                        ]
                    ]);

                    // Call was successful
                    if($apiResponse->getStatusCode() == 200) {
                        try {
                            $response = json_decode($apiResponse->getBody()->getContents());
                            $request->session()->put('game_finished_result', $response);
                            return redirect()->to('/games/finish-screen');

                        } catch (\Exception $e) {
                            return redirect()->to('/error')->with('error', 'Application error');
                        }
                    }
                    else  {
                        return redirect()->to('/error')->with('error', 'Application error');
                    }
                } catch (BadResponseException $e) {
                    if($e->getCode() == 409){
                        return redirect()->to('/games/finish-screen');
                    } else {
                        switch($e->getCode()){
                            case 404:
                                // Game play ID was not found
                                return redirect()->to('/error')->with('error', 'Application error');
                                break;
                            default:
                                return redirect()->to('/error')->with('error', 'Application error');
                                break;
                        }
                    }
                }
            } else {
                return redirect()->to('/signin');
            }
        } else {
            // Game does not exist. It has been played and it's not available in the current session, redirect back to /
            return redirect()->to('/games');
        }
    }
}
