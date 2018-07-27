<div id="footer" class="footer">
    @yield('badge')
    <hr>
    <ul class="footer-ul">
        @if(@session('apikey'))
            <li class="footer-li"><a href="/init">{{ session('config.texts')->pt->menu->button_play }}</a></li>
            <li class="footer-li"><a href="/logout">{{ session('config.texts')->pt->menu->button_logout }}</a></li>
        @else
            <li class="footer-li"><a href="/register">{{ session('config.texts')->pt->register->button_register }}</a></li>
            <li class="footer-li"><a href="/forgot-password">{{ session('config.texts')->pt->recover->button_recover }}</a></li>
        @endif
        <li class="footer-li"><a href="/terms">{{ session('config.texts')->pt->menu->button_tos }}</a></li>
        <li class="footer-li"><a href="#">@if(!@session('apikey'))<img src="{{ url('/images/lock.png') }}" class="select-icon">@endif{{ session('config.texts')->pt->menu->button_winners }}</a></li>
        <li class="footer-li"><a href="/grand_prize?bypass=1">@if(!@session('apikey'))<img src="{{ url('/images/lock.png') }}" class="select-icon">@endif{{ session('config.texts')->pt->menu->button_about }}</a></li>
        <li class="footer-li"><a href="#">@if(!@session('apikey'))<img src="{{ url('/images/lock.png') }}" class="select-icon">@endif{{ session('config.texts')->pt->menu->button_coupons }}</a></li>
        <li class="footer-li"><a href="#">@if(!@session('apikey'))<img src="{{ url('/images/lock.png') }}" class="select-icon">@endif{{ session('config.texts')->pt->menu->text_version }}</a></li>
    </ul>
</div>
</body>
</html>
