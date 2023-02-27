<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @routes
    <link href="{{ mix('backend/css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('backend/js/app.js') }}" defer></script>
</head>

<body>
    @inertia
</body>

</html>
