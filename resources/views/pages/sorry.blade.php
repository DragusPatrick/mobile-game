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

    <div class="bg bg-min bg-811">
        <div id="congrats-line">{{ session('config.texts')->pt->sorry->text_title }}</div>
        <div id="congrats-prize">{!! session('config.texts')->pt->sorry->text_subtitle !!}</div>
        <div id="level">
            <?php
            $level = session('level') - 1;
            $levelName = session('config.levels')[$level]->name;
            $levelBadge = session('config.levels')[$level]->badge;
            ?>
            <div id="level-name">{{ $levelName }}</div>
            <div id="level-text">level</div>
        </div>
        <div id="level-icon">
            <img src="{{ IMAGES_BASE_PATH . $levelBadge  }}" />
        </div>
        <div id="points">
            <div id="points-scode">
                {{ session('points') }}
            </div>
            <div id="points-text">
                points
            </div>
        </div>
    </div>

    <div class="input input-1">
        <form action="/games">
            <input type="submit" value="{{ session('config.texts')->pt->sorry->button_play_next_game }}" class="submit">
        </form>
    </div>
</div>
@include('partials._footer')
@endsection

