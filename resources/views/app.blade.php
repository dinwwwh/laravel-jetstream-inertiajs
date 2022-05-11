<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        @jslocalization
        @routes
        @production
            @php
                $manifest = json_decode(file_get_contents(public_path('dist/manifest.json')), true);
            @endphp
            <script type="module" src="/dist/{{ $manifest['resources/js/app.ts']['file'] }}"></script>
            <link rel="stylesheet" href="/dist/{{ $manifest['resources/js/app.ts']['css'][0] }}">
        @else
            <script type="module" src="http://localhost:3030/@vite/client"></script>
            <script type="module" src="http://localhost:3030/resources/js/app.ts"></script>
        @endproduction
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
