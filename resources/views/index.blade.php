<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __("index.title") }}</title>
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <header class="header">
            <h1>{{ __('index.title') }}</h1>
        </header>
        <div class="content">
            <main>
                <a href="{{ asset('api/documentation') }}">{{ __('index.documentation') }}</a>
            </main>
            <aside>
                <form action="{{ route('token') }}" method="post">
                    <h2>{{ __('index.token.form_title') }}</h2>
                    @csrf
                    <label for="name-token"></label>
                    <input id="name-token" type="text" name="name_token" placeholder="{{ __('index.token.name_placeholder') }}" required />
                    <input type="submit" value="{{ __('index.token.submit') }}">
                </form>
            </aside>
        </div>
        <footer class="footer">

        </footer>
    </body>
</html>
