<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorsController extends Controller
{
    public function app_error(Request $request){
        return view('pages.error');
    }
}
