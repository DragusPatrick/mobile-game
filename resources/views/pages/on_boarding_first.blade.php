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

    <div class="bg bg-min">
       <center><img class="winner-one-page" src="{{ url('/images/Winner.png') }}"></center>
        <div class="on_boarding_text_second">{!! session('config.texts')->pt->homepage->text_slide_2 !!}</div>
    </div>

    <div class="input input-1">
        <form action="/on-boarding-second">
            @if(request()->get('bypass'))
                <input type="hidden" name="bypass" value="1" />
            @endif
            <input type="submit" value="{{ session('config.texts')->pt->homepage->button_next }}" class="submit">
        </form>
    </div>
</div>
@include('partials._footer')
@endsection

