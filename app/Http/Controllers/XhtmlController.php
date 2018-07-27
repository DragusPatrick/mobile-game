<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 27/11/2017
 * Time: 11:25
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class XhtmlController extends Controller {

    public function index() {
        return redirect()->to('/signin');
        // return view('pages.splash-screen');
    }
}
