<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://bootstrap-vue.org/_nuxt/css/cd11b29.css" as="style">
    <link rel="stylesheet" href="https://bootstrap-vue.org/_nuxt/css/4b2063b.css" as="style">
    <link rel="stylesheet" href="https://bootstrap-vue.org/_nuxt/css/a5bee3e.css" as="style">
    <link rel="stylesheet" href="https://bootstrap-vue.org/_nuxt/css/56df4ca.css" as="style">
    <link rel="stylesheet" href="https://bootswatch.com/5/cerulean/bootstrap.css" as="style">
    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>