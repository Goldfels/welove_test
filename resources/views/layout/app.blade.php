<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <script src="{{ asset('js/app.js') }}" defer></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>
        <nav class="navbar navbar-dark bg-dark navbar-expand-lg mb-3">
            <a class="navbar-brand" href="/">{{ config('app.name', 'Laravel') }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Navigáció">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <ul class="navbar-nav">
                    <li class="nav-item @if (isset($nav) ? $nav == 'main' : false) active @endif">
                        <a class="nav-link" href="/">{{ __('app.projects') }}</a>
                    </li>
                    <li class="nav-item @if (isset($nav) ? $nav == 'edit' : false) active @endif">
                        <a class="nav-link" href="/edit">{{ __('app.editcreate') }}</a>
                    </li>
                    @if(isset($nav) ? $nav == 'main' : false)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('app.filter') }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="/">{{__('app.none') }}</a>
                                @foreach($statuses as $status)
                                    <a class="dropdown-item" href="{{ route('index', ['filter' => $status->id]) }}">{{ $status->name }}</a>
                                @endforeach
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
        @yield('content')
        @yield('script')
    </body>
</html>
