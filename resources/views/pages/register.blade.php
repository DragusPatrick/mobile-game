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
<div id="signin-page">
    <div class="logo">
        <img src="{{ url('/images/logo.png') }}" class="index-logo">
    </div>

    <div class="bg">
        <div class="signin-text">{!! session('config.texts')->pt->header->text_prize !!}</div>
        <img src="{{ IMAGES_BASE_PATH . session('grand_prize_image') }}">
    </div>

    <div class="input input-1">
        <form method="post" action="/process_register">
            {{ csrf_field() }}
            <p class="signin-p signin-p-2" style="position: relative;">
                {{ session('config.texts')->pt->register->input_username }}
                <input type="text" name="msisdn" id="msisdn-field" value="+{{ session('config.prefix') }}" placeholder="{{ session('config.texts')->pt->login->input_username }}r"/>
            </p>
            <br>
            @if(@session('error'))
                <div class="signin-error"> {{ @session('error') }}</div>
            @endif
            <input type="submit" value="{{ session('config.texts')->pt->register->button_register }}" class="submit">
        </form>
    </div>
</div>
<div id="footer" class="footer forgot-footer" style="margin-top: 160px;">
    <hr>
    <ul class="footer-ul">
        @if(@session('apikey'))
            <li class="footer-li"><a href="/init">{{ session('config.texts')->pt->menu->button_play }}</a></li>
            <li class="footer-li"><a href="/logout">{{ session('config.texts')->pt->menu->button_logout }}</a></li>
        @else
            <li class="footer-li"><a href="/register">{{ session('config.texts')->pt->register->button_register }}</a></li>
            <li class="footer-li"><a href="/forgot-password">{{ session('config.texts')->pt->recover->button_recover }}</a></li>
        @endif
        <li class="footer-li"><a href="/terms">{{ session('config.texts')->pt->menu->button_tos }}</a></li>
        <li class="footer-li"><a href="#"><img src="{{ url('/images/lock.png') }}" class="select-icon"> {{ session('config.texts')->pt->menu->button_winners }}</a></li>
        <li class="footer-li"><a href="/grand_prize?bypass=1"><img src="{{ url('/images/lock.png') }}" class="select-icon"> {{ session('config.texts')->pt->menu->button_about }}</a></li>
        <li class="footer-li"><a href="#"><img src="{{ url('/images/lock.png') }}" class="select-icon"> {{ session('config.texts')->pt->menu->button_coupons }}</a></li>
        <li class="footer-li"><a href="#"><img src="{{ url('/images/lock.png') }}" class="select-icon"> {{ session('config.texts')->pt->menu->text_version }}</a></li>
    </ul>
</div>
</body>
</html>

@endsection
