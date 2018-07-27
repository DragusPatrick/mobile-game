@extends('layouts.main')

@section('header')
    <meta http-equiv="refresh" content="5;URL=/games/memory-game-play">
@endsection

@section('content')
<div id="scratch-page">
    <header class="scratch-header">
        <img src="{{ url('/images/logo.png') }}" class="index-logo">

        <div class="scratch-header-content">
            <p class="scratch-title">{{ $game_data->name }}</p>
            <p class="scratch-p">{{ $game_data->subtitle }}</p>
        </div>
    </header>

    @for($i = 1; $i <= $game_data->data->rows; $i++)
        <div class="memory-images-0 memory-images">
            @for($j = 1; $j <= $game_data->data->columns; $j++)
                <img src="{{ IMAGES_BASE_PATH . $boxes[$counter]->image }}" />
                <?php $counter += 1; ?>
            @endfor
        </div>
    @endfor
    @include('partials/_grandprize')
</div>
@include('partials._prizebadge')
@include('partials._footer')
@endsection

