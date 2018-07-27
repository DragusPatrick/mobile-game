<div class="scratch-prize">
    <h4 class="prize-h4">{{ session('config.texts')->pt->footer->text_you_can_win }}</h4>
    <h3 class="prize-h2">{{ session('grand_prize_name') }}</h3>
    <img src="http://api.html.promo.stage.beecoded.ro/{{ session('grand_prize_image') }}" class="scratch-prize-img" style="margin-left: -5px;">
</div>
