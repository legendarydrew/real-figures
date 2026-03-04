<a href="{{ route('contest') }}" {{ $current === 'contest' || $current === 'votes' ? 'class=active' : '' }}>Contest</a>
@if(\App\Facades\ContestFacade::shouldShowNews())
    <a href="{{ route('news') }}" {{ $current === 'news' || $current === 'news.show' ? 'class=active' : '' }}>News</a>
@endif
@if(\App\Facades\ContestFacade::shouldShowActs())
    <a href="{{ route('acts') }}" {{ $current === 'acts' ? 'class=active' : '' }}>Acts</a>
@endif
<a href="{{ route('rules') }}" {{ $current === 'rules' ? 'class=active' : '' }}>Rules</a>
<a class="donate-link" href="{{ route('donate') }}" {{ $current === 'donate' ? 'class=active' : '' }}>Donate!</a>
<a href="{{ route('about') }}" {{ $current === 'about' ? 'class=active' : '' }}>About</a>
<a href="{{ route('contact') }}" {{ $current === 'contact' ? 'class=active' : '' }}>Contact</a>
