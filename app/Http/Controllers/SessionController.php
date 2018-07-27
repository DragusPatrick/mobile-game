<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 27/11/2017
 * Time: 11:25
 */

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;

class SessionController extends Controller {

    public function signin(Request $request) {
        // User is signed in -> redirect to init
        if($request->session()->has('apikey')) {
            return redirect()->to('/grand_prize');
        }
        // Show auth screen
        return view('pages.signin');
    }

    public function register(Request $request){
        // User is signed in -> redirect to init
        if($request->session()->has('apikey')) {
            return redirect()->to('/grand_prize');
        }
        return view('pages.register');
    }

    // Register new msisdn
    public function process_register(Request $request) {
        // User is signed in -> redirect to init
        if($request->session()->has('apikey')) {
            return redirect()->to('/grand_prize');
        }
        $msisdn = $request->msisdn;

        if(empty($msisdn)){
            return redirect()->back()->with('error', session('config.texts')->pt->register->error_username_empty);
        }

        if(false === (substr($msisdn,1, 2) === session('config.prefix'))){
            return redirect()->back()->with('error', session('config.texts')->pt->login->error_username_invalid);
        }

        $client = new Client();
        try {
            $apiResponse = $client->request('POST', API_BASE_URL . 'user/subscribe-prompt', [
                'form_params' => [
                    'msisdn' => str_replace('+','',$msisdn),
                ]
            ]);

            // Call was successful
            switch($apiResponse->getStatusCode()){
                case 304:
                    return redirect()->back()->with('error', session('config.texts')->pt->register->error_upstream_6);
                    break;
                case 200:
                    try {
                        $response = json_decode($apiResponse->getBody()->getContents());
                        return redirect()->to('/signin')->with('message', session('config.texts')->pt->register->message_success);
                    } catch (Exception $e) {
                        return redirect()->back()->with('error', session('config.texts')->pt->register->error_upstream_400);
                    }
                    break;
                case 400:
                    return redirect()->back()->with('error', session('config.texts')->pt->register->error_upstream_400);
                    break;
                default:
                    return redirect()->back()->with('error', session('config.texts')->pt->register->error_upstream_400);
                    break;
            }

        } catch (BadResponseException $e) {
            return redirect()->back()->with('error', session('config.texts')->pt->register->error_upstream_400);
        }
    }


    public function login(Request $request){
        // User is signed in -> redirect to init
        if($request->session()->has('apikey')) {
            return redirect()->to('/grand_prize');
        }
        $msisdn = $request->msisdn;

        $password = $request->password;

        if(empty($msisdn) && empty($password)){
            return redirect()->back()->with('error', session('config.texts')->pt->login->error_username_password_empty);
        }

        if(empty($msisdn)){
            return redirect()->back()->with('error', session('config.texts')->pt->login->error_username_empty);
        }


        if(false === (substr($msisdn,1, 2) === session('config.prefix'))){
            return redirect()->back()->with('error', session('config.texts')->pt->login->error_username_invalid);
        }

        if(empty($password)){
            return redirect()->back()->with('error', session('config.texts')->pt->login->error_password_empty);
        }

        $client = new Client();


        // Try API request
        try {
            $apiResponse = $client->request('POST',API_BASE_URL . 'user/login', [
                'form_params' => [
                    'msisdn' => str_replace('+','',$msisdn),
//                    'msisdn' => $msisdn,
                    'password' => $password
                ]
            ]);

            // Call was successful
            if($apiResponse->getStatusCode() == 200) {
                try {
                    $response = json_decode($apiResponse->getBody()->getContents());
                    if($response->apikey){
                        // Set api key into session
                        $request->session()->put('apikey', $response->apikey);
                        return redirect()->to('/grand_prize');
                    } else {
                        // We didn't get API key, return back with error
                        return redirect()->back()->with('error', session('config.texts')->pt->login->error_username_password_invalid);
                    }
                } catch (Exception $e){
                    return redirect()->back()->with('error', session('config.texts')->pt->login->error_server_bad_response);
                }
            }
            // Error on authentication (username or password is wrong)
            else
            {
                return redirect()->back()->with('error', session('config.texts')->pt->login->error_username_password_invalid);
            }
        } catch (BadResponseException $e) {
            return redirect()->back()->with('error', session('config.texts')->pt->login->error_username_password_invalid);
        }
    }

    // Check if user is eligibility to play or not
    public function not_eligible(Request $request){
        $client = new Client();
        try {
            $apiResponse = $client->request('GET', API_BASE_URL . 'user/status?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey'));

            // Call was successful
            switch($apiResponse->getStatusCode()){
                case 200:
                    try {
                        $response = json_decode($apiResponse->getBody()->getContents());
                        if($response->eligible === true){
                            if(session()->get('session_data')->open == true) {
                                return redirect()->to('/init');
                            } else {
                                dd("sessioncontroller@noteligible");
                                return redirect()->to('/games/play-again-tomorrow');
                            }
                        } else {
                            return view('pages.not_eligible', [
                                'name' => $request->session()->get('grand_prize_name'),
                                'image' => $request->session()->get('grand_prize_image')
                            ]);
                        }
                    } catch (Exception $e) {
                        return view('pages.not_eligible', [
                            'name' => $request->session()->get('grand_prize_name'),
                            'image' => $request->session()->get('grand_prize_image')
                        ]);
                    }
                    break;
                default:
                    return view('pages.not_eligible', [
                        'name' => $request->session()->get('grand_prize_name'),
                        'image' => $request->session()->get('grand_prize_image')
                    ]);
                    break;
            }

        } catch (BadResponseException $e) {
            return view('pages.not_eligible', [
                'name' => $request->session()->get('grand_prize_name'),
                'image' => $request->session()->get('grand_prize_image')
            ]);
        }
    }

    public function logout(Request $request){
        // Flush storage -- remove all data from storage
        $request->session()->flush();
        return redirect()->to('/signin');
    }

    public function remind(Request $request){
        $msisdn = $request->msisdn;

        if(empty($msisdn)){
            return redirect()->back()->with('error', session('config.texts')->pt->recover->error_username_empty);
        }

        if(false === (substr($msisdn,1, 2) === session('config.prefix'))){
            return redirect()->back()->with('error', session('config.texts')->pt->login->error_username_invalid);
        }

        $client = new Client();
        try {
            $apiResponse = $client->request('POST', API_BASE_URL . '/user/remind-password', [
                'form_params' => [
                    'msisdn' => str_replace('+','',$msisdn),
                ]
            ]);

            // Call was successful
            switch($apiResponse->getStatusCode()){
                case 200:
                    try {
                        $response = json_decode($apiResponse->getBody()->getContents());
                        return redirect()->to('/signin')->with('message', session('config.texts')->pt->recover->message_success);
                    } catch (Exception $e) {
                        return redirect()->back()->with('error', session('config.texts')->pt->recover->error_upstream_400);
                    }
                    break;
                case 400:
                    return redirect()->back()->with('error', session('config.texts')->pt->recover->error_upstream_3);
                    break;
                case 500:
                    return redirect()->back()->with('error', session('config.texts')->pt->recover->error_upstream_500);
                    break;
                case 502:
                    return redirect()->back()->with('error', session('config.texts')->pt->recover->error_upstream_502);
                    break;
                default:
                    return redirect()->back()->with('error', session('config.texts')->pt->recover->error_upstream_400);
                    break;
            }

        } catch (BadResponseException $e) {
            return redirect()->back()->with('error', session('config.texts')->pt->recover->error_upstream_3);
        }
    }


    public function debug(Request $request){
        dd($request->session());
    }
}
