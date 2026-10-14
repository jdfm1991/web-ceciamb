<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- SEO --}}
        <meta name="description" content="{{ \App\Models\ClinicInfo::getValue('slogan', 'Atención médica de calidad con sensibilidad humana.') }}">
        <meta name="author" content="{{ \App\Models\ClinicInfo::getValue('name', 'Hospital de Clínicas de Ceciamb') }}">

        {{-- Open Graph --}}
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ \App\Models\ClinicInfo::getValue('name', 'Hospital de Clínicas de Ceciamb') }}">
        <meta property="og:title" content="{{ config('app.name') }}">
        <meta property="og:description" content="{{ \App\Models\ClinicInfo::getValue('slogan', 'Atención médica de calidad.') }}">
        <meta property="og:image" content="{{ \App\Models\ClinicInfo::getLogoUrl() }}">
        <meta property="og:url" content="{{ url()->current() }}">

        {{-- Twitter Card --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ config('app.name') }}">
        <meta name="twitter:description" content="{{ \App\Models\ClinicInfo::getValue('slogan') }}">
        <meta name="twitter:image" content="{{ \App\Models\ClinicInfo::getLogoUrl() }}">

        {{-- Favicon --}}
        <link rel="icon" type="image/png" href="{{ \App\Models\ClinicInfo::getLogoUrl() }}">

        <title inertia>{{ config('app.name', 'Hospital de Clínicas de Ceciamb') }}</title>

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>