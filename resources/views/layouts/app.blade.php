<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Integra') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/fontawesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/general.css') }}" rel="stylesheet">
    <!-- TOP Javascript -->
    <script src="https://use.fontawesome.com/a0199b7c84.js"></script>
    <script src="{{ asset('plugins/accounting/accounting.min.js') }}" type="text/javascript"></script>
    @yield('top_javascript')

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Integra') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    @if(!isset($bandera))
                        <p class="navbar-text">@yield('title')</p>
                    @elseif(isset($bandera))
                        <a style="text-decoration: none; color: gray;" href="{{ route('home') }}" class="navbar-text">@yield('title')</a>
                        <div class="navbar-text btn-group btn-group-toggle">
                            <label class="btn btn-default">
                                <a style="text-decoration: none; color: gray;" href="{{ route('partido', ['partido' => 'PRI']) }}" > PRI </a>
                            </label>
                            <label class="btn btn-default">
                                <a style="text-decoration: none; color: gray;" href="{{ route('partido', ['partido' => 'PVEM']) }}" > PVEM </a>
                            </label>
                            <label class="btn btn-default">
                                <a style="text-decoration: none; color: gray;" href="{{ route('partido', ['partido' => 'PANAL']) }}" > PANAL </a>
                            </label>

                            <label class="btn btn-default" style="background-color: white; color: white"> * </label>

                            <label class="btn btn-default">
                                <a style="text-decoration: none; color: gray;" href="{{ route('partido', ['partido' => 'PAN']) }}" > PAN </a>
                            </label>
                            <label class="btn btn-default">
                                <a style="text-decoration: none; color: gray;" href="{{ route('partido', ['partido' => 'PRD']) }}" > PRD </a>
                            </label>
                            <label class="btn btn-default">
                                <a style="text-decoration: none; color: gray;" href="{{ route('partido', ['partido' => 'MC']) }}" > MC </a>
                            </label>

                            <label class="btn btn-default" style="background-color: white; color: white"> * </label>

                            <label class="btn btn-default">
                                <a style="text-decoration: none; color: gray;" href="{{ route('partido', ['partido' => 'MORENA']) }}" > MORENA </a>
                            </label>
                            <label class="btn btn-default">
                                <a style="text-decoration: none; color: gray;" href="{{ route('partido', ['partido' => 'PT']) }}" > PT </a>
                            </label>
                            <label class="btn btn-default">
                                <a style="text-decoration: none; color: gray;" href="{{ route('partido', ['partido' => 'PES']) }}" > PES </a>
                            </label>
                        </div>
                    @endif
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @else
                            <li></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">

                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>
    <!-- Scripts -->
    @yield('bottom_javascript')
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
