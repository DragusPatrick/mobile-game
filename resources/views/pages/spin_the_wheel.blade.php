<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 27/11/2017
 * Time: 16:24
 */
?>

@extends('layouts.main')
@section('content')
<div id="scratch-page">
    <header class="scratch-header">
        <img src="{{ url('/images/logo.png') }}" class="index-logo">

        <div class="scratch-header-content">
            <p class="scratch-title">{{ $pregame->name }}</p>
            <p class="scratch-p">{{ session('config.texts')->pt->wheel->text_title }}</p>
        </div>
    </header>

    <form action="/spinning">
        <div class="input input-1">
            <form action="/spinning?id={{ $pregame->id }}&code={{ $pregame->code }}"><input type="submit" value="{{ session('config.texts')->pt->wheel->text_subtitle }}" /></form>
        </div>
    </form>

    <div class="spin-image-1">
        <img src="{{ url('/images/wheel2.png') }}" class="spin-pimg spin-wheel-static">
    </div>


    @include('partials._grandprize')
</div>
@include('partials._footer')
@endsection
