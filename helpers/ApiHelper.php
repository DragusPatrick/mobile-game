<?php
/**
 * Created by PhpStorm.
 * User: laalex
 * Date: 14/12/2017
 * Time: 23:35
 */

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

const API_BASE_URL = "http://api.phase4.promo.stage.beecoded.ro/api/v1/";
const IMAGES_BASE_PATH = "http://api.phase4.promo.stage.beecoded.ro";

if(!function_exists('get_prize_info')){

    function get_prize_info($id){
        $prizes = session('config.prizes');
        $foundPrize = [];
        foreach($prizes as $prize){
            if($prize->id == $id){
                $foundPrize = $prize;
            }
        }
        return $foundPrize;
    }

}