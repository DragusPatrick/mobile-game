<div class="selectgame-img-1">
    <img src="{{ url('/images/level-bg.png') }}" class="img-1">
    <div class="text-centered-1" style="word-spacing: 10px;">{{ $user_level->name }} {{ session('points') }}</div>
    <div class="text-centered-2" style="word-spacing: 40px;">{{ session('config.texts')->pt->header->text_level }} {{ session('config.texts')->pt->header->text_points }}</div>
    <img src="{{ IMAGES_BASE_PATH . $user_level->badge }}" class="img-2">
</div>

@if(session('scheme') == 'credits')
    <div class="selectgame-img-1 coins-band">
        <img src="{{ url('/images/level-bg.png') }}" class="img-1">
        <div class="text-centered-1 coins-text" style="word-spacing: 0px !important;">{{ session('credits') }}
            @if(session('credits') == 1)
                {{ session('config.texts')->pt->credits->text_credit }}
            @else
                {{ session('config.texts')->pt->credits->text_credits }}
            @endif
        </div>
        @if(session('credits') < 1)
            <img src="{{ url('/images/coin_red.png') }}" class="img-2">
        @else
            <img src="{{ url('/images/coin_yellow.png') }}" class="img-2">
        @endif
    </div>
@endif
