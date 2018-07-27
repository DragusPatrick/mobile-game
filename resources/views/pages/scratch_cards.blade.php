<?php
/**
 * Created by PhpStorm.
 * User: draguspatrick
 * Date: 27/11/2017
 * Time: 17:26
 */
?>

@extends('layouts.main')
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
                <td>
                    <a href="/{{ $pregame->code }}-result?id={{ $pregame->id }}&code={{ $pregame->code }}&prizeid={{ $prize_id }}&index={{ $loop->index }}">
                        <img src="http://api.html.promo.stage.beecoded.ro/{{ $pregame->data->defaultImage }}" style="width: 65px;" />
                    </a>
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
