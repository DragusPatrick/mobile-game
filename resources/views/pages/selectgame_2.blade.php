<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 28/11/2017
 * Time: 13:50
 */
?>

@extends('layouts.main')

@section('content')
    <div id="hidden-objects-1-page" class="container">
        <header class="hidden-objects-1-header">
            <img src="{{ url('/images/logo.png') }}" class="index-logo">

            <div class="hidden-objects-1-header-content">
                <p class="hidden-objects-1-title">{{ session('config.texts')->pt->games->text_title }}</p>
                <p class="hidden-objects-1-p">{!! session('config.texts')->pt->games->text_subtitle !!}</p>
            </div>
        </header>

        @include('partials._userlevel')


        @foreach($games_data as $game)
            <div class="@if($games->{$game->id} == 'playable' || $games->{$game->id} == 'playing') selected @endif">
                @switch($games->{$game->id})
                    @case('playable')
                    <a href="/games/{{ $game->code }}?id={{ $game->id }}&code={{ $game->code }}">
                        <div class="selected select-1">
                            <p>{{ $game->name }} <img src="{{ url('/images/arrow-right.png') }}" class="select-icon"></p>
                        </div>
                    </a>
                    @break

                    @case('playing')
                    <a href="/games/{{ $game->code }}?id={{ $game->id }}&code={{ $game->code }}">
                        <div class="select-1">
                            <p>{{ $game->name }}<img src="{{ url('/images/arrow-right.png') }}" class="select-icon"></p>
                        </div>
                    </a>
                    @break

                    @case('completed')
                    <div class="select-1">
                        <p>{{ $game->name }} <img src="{{ url('/images/check.png') }}" class="select-icon"></p>
                    </div
                    @break
                    @default
                    <div class="select-1">
                        <p>{{ $game->name }} <img src="{{ url('/images/lock.png') }}" class="select-icon"></p>
                    </div>

                @endswitch
            </div>
        @endforeach


        <div class="select-tip">
            <p class="select-tip">{!!  session('config.texts')->pt->games->text_tip !!}</p>
        </div>

        @include('partials._grandprize')

    </div>
    @include('partials._prizebadge')
    @include('partials._footer')
@endsection


