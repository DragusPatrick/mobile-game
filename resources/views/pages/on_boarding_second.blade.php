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
        <img src="{{ url('/images/pro-icon.png') }}" class="im-1">
        <img src="{{ url('/images/newbie-icon.png') }}" class="im-2">
        <img src="{{ url('/images/pro-icon.png') }}" class="im-3">
        <div class="signin-text-2">{!! session('config.texts')->pt->homepage->text_slide_3 !!}</div>
    </div>
    <div class="input input-1">
        @if(session()->has('scheme') && session('scheme') == 'credits')
            <form action="/on-boarding-third">
        @else
            <form action="/pregames-1">
        @endif
            @if(request()->get('bypass'))
                <input type="hidden" name="bypass" value="1" />
            @endif
            <input type="submit" value="{{ session('config.texts')->pt->homepage->button_start }}" class="submit">
        </form>
    </div>
</div>
@include('partials._footer')
@endsection

