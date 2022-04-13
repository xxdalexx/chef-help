<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Theme</a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

        <li>
            <span class="dropdown-item">
                {{ $current['title'] }}
            </span>
        </li>

        <li>
            <hr class="dropdown-divider" />
        </li>

        @foreach($themes as $theme)
            <li>
                <a class="dropdown-item" href="{{ $theme['route'] }}">
                    {{ $theme['title'] }}
                </a>
            </li>
        @endforeach

    </ul>
</li>
