<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 04/12/2017
 * Time: 20:37
 */
?>

@extends('layouts.main')
@section('content')
<div id="signin-page">
    <div class="logo">
        <img src="{{ url('/images/logo.png') }}" class="index-logo">
    </div>

    <div class="bg bg-min bg-multi">
        <img src="{{ url('/images/coin_yellow.png') }}" class="im-2">
        <div class="signin-text-2">{!! session('config.texts')->pt->homepage->text_slide_4 !!}</div>
    </div>

    <div class="input input-1">
        <form action="/pregames-1">
            <input type="submit" value="{{ session('config.texts')->pt->homepage->button_start }}" class="submit">
        </form>
    </div>
</div>
@include('partials._footer')
@endsection

