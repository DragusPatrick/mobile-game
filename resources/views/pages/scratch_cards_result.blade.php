<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 27/11/2017
 * Time: 17:26
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
                <p class="scratch-title">{{ $pregame->name }}</p>
                <p class="scratch-p">{!! $pregame->subtitle !!}</p>
            </div>
        </header>

        <table class="scratch-table" style="width: 100%">
            @foreach($pregame->prizes as $prize)
                @if(($loop->index) % 3 == 0 || $loop->index + 1 == 1)
                    <tr>
                        @endif
                        <td style="width: 33%">
                            @if($loop->index == $selectedIndex)
                                <div style="width: 55px; position:relative;" class="won-card">
                                    <img style="width:55px;" src="{{ url('images/scratch-active.png') }}"/>
                                    @if($selected_prize->type != 2)
                                        <span id="pregame-text-1" style="position: absolute;
    top: 15px;
    left: 5px;
    right: 5px;
    font-size: 19px;
    text-align: center;
    color: #fff;
    font-weight: bold;
    bottom: 5px;
    vertical-align: middle;
    display: block;
    line-height: 20px;
    height: 68px;">{{ $selected_prize->texts[0] }}</span>
                                    @endif

                                    <span id="pregame-text-2" style="    position: absolute;
    top: 32px;
    left: 5px;
    right: 5px;
    font-size: 8px;
    text-align: center;
    color: #fff;
    font-weight: bold;
    bottom: 5px;
    vertical-align: middle;
    display: block;
    line-height: 20px;
    height: 68px;">{{ $selected_prize->texts[1] }}</span>
                                </div>
                            @else
                                <img style="width: 55px"
                                     src="{{ IMAGES_BASE_PATH . $pregame->data->defaultImage }}"/>
                            @endif
                        </td>
                        @if(($loop->index + 1) % 3 == 0)
                    </tr>
                @endif
            @endforeach
        </table>

        @include('partials._grandprize')
    </div>
    @include('partials._footer')
@endsection
