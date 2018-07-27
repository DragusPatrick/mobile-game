<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class SpotDifferenceController extends Controller
{
    public function start(Request $request){
        $game_id = $request->id;
        $game_code = $request->code;

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
                        $request->session()->put('spot_game_info', $response);

                        $gameData = null;
                        foreach($request->session()->get('games_data') as $game){
                            if($game->code == 'spot-the-difference'){
                                $gameData = $game;
                            }
                        }
                        $gameState ["game_data"] =  $gameData;
                        $gameState["differences"] = $gameData->data->differences;
                        $gameState["original_image"] = $gameData->data->originalImage;
                        $gameState["different_image"] = $gameData->data->differentImage;
                        $gameState["found_differences"] = [];
                        $gameState["finished_game"] = false;
                        $request->session()->put('spot_game_state', $gameState);
                        // Redirect to preview game cards
                        return redirect()->to('/games/spot-the-difference-play');

                    } catch (\Exception $e) {
                        return redirect()->to('/error')->with('error', 'Application error');
                    }
                }
                else  {
                    return redirect()->to('/error')->with('error', 'Application error');
                }
            } catch (BadResponseException $e) {
                // Check if we already have the information for the game, if not, reset the game state
                $gameData = null;
                foreach($request->session()->get('games_data') as $game){
                    if($game->code == 'spot-the-difference'){
                        $gameData = $game;
                    }
                }
                $gameState ["game_data"] =  $gameData;
                $gameState["differences"] = $gameData->data->differences;
                $gameState["original_image"] = $gameData->data->originalImage;
                $gameState["different_image"] = $gameData->data->differentImage;
                $gameState["found_differences"] = [];
                $gameState["finished_game"] = false;
                $request->session()->put('spot_game_state', $gameState);

                return redirect()->to('/games/spot-the-difference-play');
            }
        } else {
            return redirect()->to('/signin');
        }
    }

    public function play(Request $request){
        $gameState = $request->session()->get('spot_game_state');
        $gameData = $gameState["game_data"];
        $differences = $gameState["differences"];
        $originalImage = $gameState["original_image"];
        $differentImage = $gameState["different_image"];
        $foundDifferences = $gameState["found_differences"];

        $rows = 12;
        $cols = 6;
        $clickBlockWidth = 200/$rows;
        $clickBlockHeight = 100/$cols;


        if(count($foundDifferences) > 0 && count($foundDifferences) == count($differences)){
            return redirect()->to('/games/spot-the-difference-finish');
        } else {
            $prizeInfo = get_prize_info(session('session_data')->prizeId);
            return view('pages.spot_the_difference', [
                'game_data' => $gameData,
                'differences' => $differences,
                'originalImage' => $originalImage,
                'differentImage' => $differentImage,
                'foundDifferences' => $foundDifferences,
                'mapElementWidth' => $clickBlockWidth,
                'mapElementHeight' => $clickBlockHeight,
                'cols' => $cols,
                'rows' => $rows,
                'prize' => $prizeInfo
            ]);
        }
    }

    public function select(Request $request){
        $gameState = $request->session()->get('spot_game_state');
        $differences = $gameState["differences"];
        $foundDifferences = $gameState["found_differences"];

        $x = $request->x;
        $y = $request->y;

        foreach($differences as $difference){
            if($difference->x == $x && $difference->y == $y){
                // We found a difference
                array_push($foundDifferences, ['x' => $x, 'y' => $y]);
                // Push the found difference into game state array
                $gameState["found_differences"] = $foundDifferences;
                $request->session()->put('spot_game_state', $gameState);
                // Redirect back to play screen
                return redirect()->to('/games/spot-the-difference-play');
            }
        }

        // Nothing matched, get back to play screen
        return redirect()->to('/games/spot-the-difference-play');
    }

    public function finish(Request $request){

        $gameState = $request->session()->get('spot_game_state');
        $gameState["finished_game"] = true;
        $request->session()->put('spot_game_state', $gameState);

        $gameId = $gameState['game_data']->id;


        if(isset(session('session_data')->playing) && isset(session('session_data')->playing->{"${gameId}"})) {
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
                        // TODO: Error occured -> redirect to where?
                        dd("error occured here other than 409");
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
