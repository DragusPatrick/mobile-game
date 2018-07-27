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
    <div id="signin-page" style="height: 150px">
        <div class="logo">
            <img src="{{ url('/images/logo.png') }}" class="index-logo">
        </div>
        <div id="general-header">
            <div class="heading">{!! session('config.texts')->pt->credits->text_play_more !!}</div>
            <div class="text">{!! session('config.texts')->pt->credits->text_buy_more !!}</div>
        </div>

    </div>
    <div class="buy-credits-container">
        <p>{!! session('config.texts')->pt->credits->text_wait_confirmation !!}</p>
        <a href="/" class="button-link red-btn buy-btn full-width">
            {!! session('config.texts')->pt->credits->button_go_home !!}
        </a>
    </div>
    <br />
    <div class="clearfix"></div>
    <div class="pull-footer-up">
        @include('partials._footer')
    </div>
@endsection
