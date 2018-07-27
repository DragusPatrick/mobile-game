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
<div id="hidden-objects-1-page" class="container">
    <header class="hidden-objects-1-header">
        <img src="{{ url('/images/logo.png') }}" class="index-logo">

        <div class="hidden-objects-1-header-content">
            <p class="hidden-objects-1-title">{{ session('config.texts')->pt->difference->text_title }}</p>
            <p class="hidden-objects-1-p">{{ session('config.texts')->pt->difference->text_subtitle }}</p>
        </div>
    </header>

    <div class="hidden-objects-images">
        <div class="hidden-image-container">
            @if(count($foundDifferences) > 0)
                @foreach($foundDifferences as $differenceFound)
                    <div class="found-spot" style="width: {{ $mapElementWidth }}px; height: {{ $mapElementHeight }}px; left: {{ (intval($differenceFound['x'])-1) * $mapElementWidth }}px; top: {{ (intval($differenceFound['y'])-1) * $mapElementHeight }}px;"></div>
                @endforeach
            @endif
            <img usemap="#original" class="spot-difference-image" src="{{ IMAGES_BASE_PATH . $originalImage->image }}">
        </div>
        <div class="hidden-image-container">
            @if(count($foundDifferences) > 0)
                @foreach($foundDifferences as $differenceFound)
                    <div class="found-spot" style="width: {{ $mapElementWidth }}px; height: {{ $mapElementHeight }}px; left: {{ (intval($differenceFound['x'])-1) * $mapElementWidth }}px; top: {{ (intval($differenceFound['y'])-1) * $mapElementHeight }}px;"></div>
                @endforeach
            @endif
            <img usemap="#difference" class="spot-difference-image" src="{{ IMAGES_BASE_PATH . $differentImage->image }}">
        </div>
    </div>

    <!-- Build element maps -->
    <map id="original" name="original">
        @for($i = 1; $i <= $rows; $i++)
            @for($j=1; $j <= $cols; $j++)
                <area shape="rect" coords="{{ ($i-1) * $mapElementWidth }},{{ ($j-1) * $mapElementHeight }},{{ $mapElementWidth * $i  }}, {{ $mapElementHeight * $j }}" href="/games/spot-the-difference-select?x={{ $i }}&y={{ $j }}" alt="{{ $i }}, {{ $j }}">
            @endfor
        @endfor
    </map>
    <map id="difference" name="difference">
        @for($i = 1; $i <= $rows; $i++)
            @for($j=1; $j <= $cols; $j++)
                <area shape="rect" coords="{{ ($i-1) * $mapElementWidth }},{{ ($j-1) * $mapElementHeight }},{{ $mapElementWidth * $i  }}, {{ $mapElementHeight * $j }}" href="/games/spot-the-difference-select?x={{ $i }}&y={{ $j }}" alt="{{ $i }}, {{ $j }}" alt="Sun">
            @endfor
        @endfor
    </map>

    @include('partials._grandprize')
</div>

@include('partials._prizebadge')

@include('partials._footer')
@endsection

