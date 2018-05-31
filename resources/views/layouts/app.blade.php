<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ Localization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Purchasing management - Clarimount">
    {{-- <meta name="description" content="Always know where your assets are, with complete detailed logs, warranty alerts, service contracts alerts, time tracking, and more."> --}}

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="permissions" content='{!! auth()->user()->exposed_permissions !!}'>

    <title>{{ isset($title) ? $title . ' | Purchase Management' : 'Purchase Management' }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ url('/favicon.ico') }}" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include('menu.sidebar')
        <div id="wrapper">
            <div id="content">
                <div class="header">
                    <div class="primary">
                        <div class="context">
                            <div class="level">
                                <div class="level-left">
                                    @yield('header')
                                </div>
                                <div class="level-right has-text-right">
                                    @if(!Auth::guest())
                                        {{ auth()->user()->name }} -
                                            <a class="navbar-item" href="{{ route('logout') }}"
                                                                            onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                                {{ trans('auth.logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                    @endif
                                    <nav class="actions-nav">
                                        <inbox-navbar-button></inbox-navbar-button>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="page-panel">
                    <div class="page-panel-inner">
                        <div class="page-panel-content">

                            @if(session()->has('messages'))
                                <div class="content">
                                    @component('components.alerts.basic', ['status' => session()->has('status') ? session()->get('status') : 'default'])
                                        <ul>
                                            @foreach(session()->get('messages') as $message)
                                                <li>{{ $message }}</li>
                                            @endforeach
                                        </ul>
                                    @endcomponent
                                </div>
                            @endif

                            @yield('content')
                            @include('layouts.global-modals')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
