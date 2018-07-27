@extends('layouts.main')

@section('header')
    <meta http-equiv="refresh" content="1;URL=/games/memory-game-reload">
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
                    @if(in_array($counter, $selected_boxes) || in_array($counter, $matched_boxes))
                        <img src="{{ IMAGES_BASE_PATH . $boxes[$counter]->image }}" />
                    @else
                        <img src="{{ IMAGES_BASE_PATH . $game_data->data->defaultImage }}" />
                    @endif
                <?php $counter += 1; ?>
            @endfor
        </div>
    @endfor
    @include('partials._grandprize')
</div>
@include('partials._prizebadge')
@include('partials._footer')
@endsection

