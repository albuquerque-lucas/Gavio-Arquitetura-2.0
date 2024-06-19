@props(['route', 'text'])

<li class="nav-item">
    <a class="nav-link {{ request()->routeIs($route) ? 'active' : '' }}" href="{{ route($route) }}">{{ $text }}</a>
</li>
