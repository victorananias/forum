<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fab fa-angellist"></i>Dummy Førum
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" 
                data-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" 
                aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Navegar
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        @if(auth()->check())
                            <a class="dropdown-item" href="/threads?by={{ auth()->user()->name }}">Minhas Threads</a>
                        @endif
                        <a class="dropdown-item" href="/threads">Todas as Threads</a>
                        <a class="dropdown-item" href="/threads?popular=1">Threads Populares</a>
                        <a class="dropdown-item" href="/threads?unanswered=1">Threads Não Respondidas</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/threads/create">Criar Thread</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Channels
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        @foreach ($channels as $channel)
                            <a class="dropdown-item" href="/threads/{{ $channel->slug }}">
                                {{ $channel->slug }}
                            </a>
                        @endforeach
                    </div>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @else

                    <user-notifications></user-notifications>

                    <li class="nav-item dropdown">
                        
                        <a id="navbarDropdown" class="btn nav-link text-light fa-lg" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fas fa-user-astronaut"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            
                            <a class="dropdown-item" href="{{ route('profile', Auth::user()) }}">Meu Perfil</a>
                            
                        </div>
                    </li>

                @endguest
            </ul>
        </div>
    </div>
</nav>