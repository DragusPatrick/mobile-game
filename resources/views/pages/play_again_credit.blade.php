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
            <img src="{{ IMAGES_BASE_PATH . session('grand_prize_image') }}">
            <div class="signin-text-2">{!! session('config.texts')->pt->homepage->text_slide_1 !!}</div>
        </div>

    </div>

    <div class="buy-credits-container">
        <form action="/play-with-credit" method="post">
            {{ csrf_field() }}
            <!-- Credit item -->
            <button class="red-btn btn-play-again buy-btn full-width relative" name="play_again" value="yes" type="submit">
                {{ session('config.texts')->pt->congratulations->button_play_again_for }} <img class="coin-image" src="{{ url('images/coin_yellow.png') }}" /> 1
            </button>
            <!-- /Credit item -->
        </form>
    </div>
    <br />

    <div class="clearfix"></div>
    @include('partials._grandprize')
    <div class="clearfix"></div>
    <div class="pull-footer-up">
        @include('partials._footer')
    </div>
@endsection
