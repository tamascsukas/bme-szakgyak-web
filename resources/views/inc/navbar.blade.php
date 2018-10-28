    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="/">@lang('app.name')</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item{{ Ekko::areActiveRoutes(['/', 'devices'], ' active') }}">
            <a class="nav-link" href="{{ route('devices') }}">Mérési pontok</a>
          </li>
        </ul>
        <ul class="navbar-nav">
        <!-- Authentication Links -->
        @if (Auth::guest())
          <li class="nav-item"><a href="{{ route('login') }}" class="nav-link text-danger">Bejelentkezés</a></li>
          <li class="nav-item"><a href="{{ route('register') }}" class="nav-link text-danger">Regisztráció</a></li>
        @else
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
              {{ Auth::user()->name }} <span class="caret"></span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown" role="menu">
              <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                Kijelentkezés
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
            </div>
          </li>
        @endif
        </ul>
      </div>
    </nav>