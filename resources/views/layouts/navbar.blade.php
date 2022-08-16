<nav class="navbar navbar-expand-lg navbar-light shadow-sm">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="offcanvasExample">
            <span class="navbar-toggler-icon" data-bs-target="#sidebar"></span>
        </button>
        <a class="navbar-brand me-auto ms-lg-0 ms-3 text-uppercase fw-bold" href="#"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavBar" aria-controls="topNavBar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="topNavBar">
            <ul class="navbar-nav ms-auto">
                <div class="form-check form-switch mt-2 me-3">
                    <input class="form-check-input" type="checkbox" id="night-mode">
                    <i class="fas fa-moon"></i>
                </div>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                      <span>{{ array_search(app()->getLocale(), config('app.available_locales')) }}</span>
                      <span class="fi fi-{{ app()->getLocale() }}"></span>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach(config('app.available_locales') as $locale_name => $available_locale)
                            @if($available_locale != app()->getLocale())
                                <li>
                                    <a class="dropdown-item" href="language/{{ $available_locale }}">
                                        <span> {{ $locale_name }} </span>
                                        <span class="fi fi-{{ $available_locale }}"></span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>

                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
