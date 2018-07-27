<?php
/**
 * Created by PhpStorm.
 * User: laalex
 * Date: 01/02/2018
 * Time: 12:52
 */
namespace App\Http\Controllers;


use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;

class CreditsController extends Controller
{
    /**
     * @param Request $request
     * @desc Show buy credits page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function buy(Request $request)
    {
        return view('pages.buy_credits', [
            'credits' => $request->session()->get('config.credits'),
            'image' => $request->session()->get('grand_prize_image')
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function buy_submit(Request $request){
        $packageId = $request->package_id;
        if(empty($packageId)){
            // Select erro message here
            return redirect()->back()->with('error', '');
        } else {
            if($request->session()->has('apikey')){

                $client = new Client();
                try {
                    $apiResponse = $client->request('POST', API_BASE_URL . 'user/credits/buy?uuid=' . $request->cookie('uuid') . '&apikey=' . $request->session()->get('apikey'), [
                        'form_params' => [
                            'packageId' => $packageId
                        ]
                    ]);

                    // Call was successful
                    if($apiResponse->getStatusCode() == 200) {
                        try {
                            $response = json_decode($apiResponse->getBody()->getContents());
                            // Redirect to credits wait page
                            $request->session()->put('credits_waiting', true);
                            return redirect()->to('/credits-wait');

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
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function wait(Request $request){
        // Check if we have credits
        if($request->session()->has('credits') && $request->session()->get('credits') > 0){
            // We have credits, redirect to init
            return redirect()->to('/');
            $request->session()->put('credits_waiting', false);
        }

        return view('pages.credits_wait');
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function play_with_credit(Request $request){
        $request->session()->put('credits_waiting', false);
        $request->session()->put('show_play_with_credit', false);
        $request->session()->put('play_with_credit', true);
        // Redirect back to init
        return redirect()->to('/');
    }

}