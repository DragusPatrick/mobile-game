<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 29/11/2017
 * Time: 11:45
 */
?>

@extends('layouts.main')
@section('content')
<div id="hidden-objects-1-page" class="container">
    <header class="hidden-objects-1-header">
        <img src="{{ url('/images/logo.png') }}" class="index-logo">
    </header>

    @include('partials._userlevel')

    @if($prize->type == 1)
        <div class="yellow-banner-1 banner-red prize-result-banner">
            <img src="{{ url('/images/banner-points-large.png') }}" class="prize-result-bg" />
            <div class="yellowbanner-text-centered-2">{{ $prize->texts[0] }}</div>
            <div class="yellowbanner-text-centered-1">{{ $prize->texts[1] }}</div>
        </div>
    @else
        <div class="yellow-banner-1 prize-result-banner">
            <img src="{{ url('/images/banner-points-yellow-large.png') }}" class="prize-result-bg" />
            <div class="yellowbanner-text-centered-2">{{ $prize->texts[0] }}</div>
            <div class="yellowbanner-text-centered-1">{{ $prize->texts[1] }}</div>
            <img src="http://api.html.promo.stage.beecoded.ro/{{ $prize->image }}" class="prize-result-image">
        </div>
    @endif


    <a href="/games">
        <div class="playnow-red-button"><p>Play now</p></div>
    </a>

    @include('partials._grandprize')
</div>
@include('partials._footer')
@endsection

