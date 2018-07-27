<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class DragAndDropController extends Controller
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
                        $request->session()->put('drag_game_info', $response);

                        $gameData = null;
                        foreach($request->session()->get('games_data') as $game){
                            if($game->code == 'drag-and-drop'){
                                $gameData = $game;
                            }
                        }

                        $gameState ["game_data"] =  $gameData;
                        $gameState["correct"] = $gameData->data->images->correct;
                        $gameState["original"] = $gameData->data->images->original;
                        $gameState["wrong1"] = $gameData->data->images->wrong1;
                        $gameState["wrong2"] = $gameData->data->images->wrong2;
                        $gameState["finished_game"] = false;
                        $request->session()->put('drag_game_state', $gameState);

                        // Redirect to preview game cards
                        return redirect()->to('/games/drag-and-drop-play');

                    } catch (\Exception $e) {
                        return redirect()->to('/error')->with('error', 'Application error');
                    }
                }
                else  {
                    return redirect()->to('/error')->with('error', 'Application error');
                }
            } catch (BadResponseException $e) {
                // Recreate game state if not exists
                $gameData = null;
                foreach($request->session()->get('games_data') as $game){
                    if($game->code == 'drag-and-drop'){
                        $gameData = $game;
                    }
                }

                $gameState ["game_data"] =  $gameData;
                $gameState["correct"] = $gameData->data->images->correct;
                $gameState["original"] = $gameData->data->images->original;
                $gameState["wrong1"] = $gameData->data->images->wrong1;
                $gameState["wrong2"] = $gameData->data->images->wrong2;
                $gameState["finished_game"] = false;
                $request->session()->put('drag_game_state', $gameState);
                return redirect()->to('/games/drag-and-drop-play');
            }
        } else {
            return redirect()->to('/signin');
        }
    }

    public function play(Request $request){
        $gameState = $request->session()->get('drag_game_state');
        $gameData = $gameState["game_data"];
        $correct = $gameState["correct"];
        $original = $gameState["original"];
        $wrong1 = $gameState["wrong1"];
        $wrong2 = $gameState["wrong2"];
        $baseItems = [$correct, $wrong1, $wrong2];
        shuffle($baseItems);
        $selections = $baseItems;
        $prizeInfo = get_prize_info(session('session_data')->prizeId);
        return view('pages.drag_drop', [
            'game_data' => $gameData,
            'selections' => $selections,
            'original' => $original,
            'prize' => $prizeInfo,
        ]);

    }

    public function select(Request $request){
        $gameState = $request->session()->get('drag_game_state');
        $gameData = $gameState["game_data"];
        $correct = $gameState["correct"];
        $dragged = $request->id;
        if($dragged == $correct->id){
            return redirect()->to('/games/drag-and-drop-finish?status=finished');
        } else {
            return redirect()->to('/games/drag-and-drop-finish?status=failed');
        }
    }

    public function finish(Request $request){
        $gameState = $request->session()->get('drag_game_state');
        $gameState["finished_game"] = true;
        $request->session()->put('drag_game_state', $gameState);

        $gameId = $gameState['game_data']->id;
        if(isset(session('session_data')->playing) && isset(session('session_data')->playing->{"${gameId}"})) {
            $gamePlayId = session('session_data')->playing->{"${gameId}"};
            $gameFinishApiUrl = API_BASE_URL . 'games/finish?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey');
            $formParams = [
                'gamePlayId' => $gamePlayId,
                'time' => 59
            ];

            if($request->status != 'finished'){
                $gameFinishApiUrl = API_BASE_URL . 'games/did-not-finish?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey');
                $formParams = [
                    'id' => $gamePlayId,
                ];
            }

            if($request->session()->has('apikey')){
                $client = new Client();
                try {
                    $apiResponse = $client->request('POST', $gameFinishApiUrl, [
                        'form_params' => $formParams
                    ]);

                    // Call was successful
                    if($apiResponse->getStatusCode() == 200) {
                        try {
                            $response = json_decode($apiResponse->getBody()->getContents());
                            $request->session()->put('game_finished_result', $response);
                            if($request->status == 'finished'){
                                return redirect()->to('/games/finish-screen');
                            } else {
                                return redirect()->to('/games/sorry-screen');
                            }

                        } catch (\Exception $e) {
                            return redirect()->to('/error')->with('error', 'Application error');
                        }
                    }
                    else  {
                        return redirect()->to('/error')->with('error', 'Application error');
                    }
                } catch (BadResponseException $e) {
                    if($e->getCode() == 409){
                        if($request->status == 'finished'){
                            return redirect()->to('/games/finish-screen');
                        } else {
                            return redirect()->to('/games/sorry-screen');
                        }
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
