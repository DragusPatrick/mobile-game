<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 27/11/2017
 * Time: 16:24
 */
?>

@extends('layouts.main')

@section('header')
    <meta http-equiv="refresh" content="3;URL=/result?id={{ $id }}&code={{ $code }}">
@endsection

@section('content')
<div id="scratch-page">
    <header class="scratch-header">
        <img src="{{ url('/images/logo.png') }}" class="index-logo">

        <div class="scratch-header-content">
            <p class="scratch-title">{{ session('config.texts')->pt->wheel->text_title }}</p>
            <p class="scratch-p">{{ session('config.texts')->pt->wheel->text_subtitle }}</p>
        </div>
    </header>

    <div class="input spinning">
        <input type="submit" value="Spinning..." disabled="disabled" class="submit">
    </div>

    <div class="spin-image-1">
        <img src="{{ url('/images/wheel-mark.png') }}" class="spin-mark">
        <img src="{{ url('/images/spin.gif') }}" class="spin-pimg">
    </div>


    @include('partials._grandprize')
</div>
@include('partials._footer')
@endsection
