<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ Localization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' | ' . config('app.name', 'Clarimount') : config('app.name', 'Clarimount') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        html {
           height: 100%;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            /*background-color: #303641;*/
        }
        .container {
          display: flex;  
          flex-direction: column;
        }
        .login-image {
            margin: 50px auto;
        }
    </style>
</head>
<body>

    <div id="app" class="{{ Route::currentRouteName() === 'login' || Route::currentRouteName() === 'register' ? 'sidebar-collapsed' : '' }}">
            @yield('content')
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
