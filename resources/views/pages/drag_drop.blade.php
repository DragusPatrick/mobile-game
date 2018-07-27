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
            <p class="hidden-objects-1-title">{{ session('config.texts')->pt->drag->text_title }}</p>
            <p class="hidden-objects-1-p">{{ session('config.texts')->pt->drag->text_subtitle }}</p>
        </div>
    </header>

    <div class="drag-img">
        <div class="drag-mini">
            @foreach($selections as $selection)
                <a href="/games/drag-and-drop-select?id={{ $selection->id }}">
                    <img src="{{ IMAGES_BASE_PATH . $selection->image }}">
                </a>
            @endforeach
        </div>
        <div class="drag-maxi">
            <img src="{{ IMAGES_BASE_PATH . $original->image  }}">
        </div>
    </div>

    @include('partials._grandprize')
@include('partials._prizebadge')
@include('partials._footer')
@endsection
