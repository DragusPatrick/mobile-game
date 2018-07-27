<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 27/11/2017
 * Time: 16:03
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
    </div>

    <div class="input signin-pages">
            @if(@session('message'))
                <span class="signin-message"> {{ @session('message') }}</span>
            @endif

            <form method="post" action="/signin">
                {{ csrf_field() }}
                <p class="signin-p txt-1" style="margin-bottom: 5px; position: relative;">
                    {{ session('config.texts')->pt->login->input_username }}
                    <input type="text" name="msisdn" id="msisdn-field" value="+{{ session('config.prefix') }}" placeholder="{{ session('config.texts')->pt->login->input_username }}r"/>
                </p>
                <br />
                <p class="signin-p txt-2" style="margin-bottom: 5px;">
                    {{ session('config.texts')->pt->login->input_password }}
                    @if(@session('error'))
                        <span class="signin-error" style="color: #D72A3D;"> {{ @session('error') }}</span>
                    @endif
                </p>
                <input type="text" name="password" placeholder=" {{ session('config.texts')->pt->login->input_password }}"/><br>
                <input type="submit" value=" {{ session('config.texts')->pt->login->button_login }}" class="submit"/>
            </form>
        </div>
    @include('partials._footer')
@endsection

