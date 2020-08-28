<ul class="dropdown menu bottom left" data-dropdown-menu>
    <li>
        <a href="#">Currency</a>
        <ul class="menu">
            @foreach(app('currencies') as $code => $value)
                <li class="{{ (Cookie::get('currency') == $code ? 'active' : '') }}"><a href="{{ route('currency.change', $code) }}">{{ $code }}</a></li>
            @endforeach
        </ul>
    </li>
</ul>
