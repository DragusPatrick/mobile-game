<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 04/12/2017
 * Time: 19:16
 */
?>

@extends('layouts.main')
@section('content')
<div id="signin-page" style="height: 260px">
    <div class="logo">
        <img src="{{ url('/images/logo.png') }}" class="index-logo">
    </div>

    <div class="bg" style="height: 260px">
        <div class="signin-text">{!! session('config.texts')->pt->header->text_prize !!}</div>
        <img src="{{ IMAGES_BASE_PATH . $image }}">
        <div class="signin-text-2">{!! session('config.texts')->pt->homepage->text_slide_1 !!}</div>
    </div>

    <form action="">
        <div class="input input-1">
            <input type="submit" value="{{ session('config.texts')->pt->homepage->button_play_again_in }}" class="submit">
        </div>
    </form>
</div>
@include('partials._footer')
@endsection
