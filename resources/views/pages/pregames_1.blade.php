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
            <p class="hidden-objects-1-title">{{ session('config.texts')->pt->pregames->text_title }}</p>
            <p class="hidden-objects-1-p">{!!  session('config.texts')->pt->pregames->text_subtitle !!}</p>
        </div>
    </header>

    @include('partials._userlevel')

    @foreach($pregames_data as $pregame)
        <div class="@if($pregames->{$pregame->id} == 'playable') selected @endif">
        @switch($pregames->{$pregame->id})
            @case('playable')
                <a href="/{{ $pregame->code }}?id={{ $pregame->id }}&code={{ $pregame->code }}">
                    <div class="selected select-1">
                        <p>{{ $pregame->name }} <img src="{{ url('/images/arrow-right.png') }}" class="select-icon"></p>
                    </div>    
                </a>
                @break
            @case('completed')
            <div class="select-1">
                <p>{{ $pregame->name }} <img src="{{ url('/images/check.png') }}" class="select-icon"></p>
            </div>
                @break
            @default
            <div class="select-1">
                <p>{{ $pregame->name }} <img src="{{ url('/images/lock.png') }}" class="select-icon"></p>
            </div>    
        @endswitch
        </div>
    @endforeach


    <div class="select-tip">
        <p class="select-tip">{!!  session('config.texts')->pt->pregames->text_tip  !!}</p>
    </div>

    @include('partials._grandprize')
</div>
@include('partials._footer')
@endsection
