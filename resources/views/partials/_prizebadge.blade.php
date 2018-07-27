@if(!empty($prize))
    @section('badge')
            <div class="prize-badge @if($prize->type == 1) red-prize-badge @endif">
                @if($prize->type == 1)
                    <img src="{{ url('/images/banner-points-large.png') }}">
                @else
                    <img src="{{ url('/images/banner-points-yellow-large.png') }}">
                @endif
                <p class="badge-heading">{{ $prize->texts[0] }}</p>
                <p class="badge-content">{{ $prize->texts[1] }}</p>
                @if(!empty($prize->image))
                    <img src="http://api.html.promo.stage.beecoded.ro/{{ $prize->image }}" class="badge-image" />
                @endif
            </div>
    @endsection
@endif
