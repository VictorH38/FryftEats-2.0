<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="@yield('description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Vite resources -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;1,700&family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="{{ asset('fonts/font-awesome.min.css') }}" rel="stylesheet">

    <!--===== Favicon =====-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset("/favicon/apple-touch-icon.png") }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("/favicon/favicon-32x32.png") }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("/favicon/favicon-16x16.png") }}">
    <link rel="manifest" href="{{ asset("/favicon/site.webmanifest") }}">

    <title>@yield('title')</title>


    @yield('style')
</head>

<body>
    <div id="header">
        <a href="{{ route('home.index') }}" id="brand-name">FryftEats</a>

        <div id="nav-wrapper">
            <div id="nav">
                <div class="nav-link">
                    <a class="{{ request()->routeIs('home.index') ? 'active' : '' }}" href="{{ route('home.index') }}">Home</a>
                </div>

                <div class="nav-link">
                    <a class="{{ request()->routeIs('search.index') ? 'active' : '' }}" href="{{ route('search.index') }}">Search</a>
                </div>

                <div class="nav-link">
                    <a class="{{ request()->routeIs('favorites.index') ? 'active' : '' }}" href="{{ route('favorites.index') }}">Favorites</a>
                </div>

                @if (Auth::check())
                    <div class="nav-link"><a href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></div>
                    <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <div class="nav-link">
                        <a class="{{ request()->routeIs('auth.index') ? 'active' : '' }}" href="{{ route('auth.showLoginForm') }}">Login</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @yield('main')

    <footer id="footer">
        <div class="footer-content">
            <div id="footer-links">
                <a href="{{ route('contact.index') }}">Contact Us</a>
                <a href="{{ route('reports.create') }}">Report a Restaurant</a>
                <a href="{{ route('privacy.index') }}">Privacy Policy</a>
            </div>
            
            <span>© {{ date('Y') }} FryftEats. All rights reserved.</span>
        </div>
    </footer>

    @yield('script')
</body>
</html>
