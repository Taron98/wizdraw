<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- WF Icon -->
    <link rel="shortcut icon" href="{{ asset('login') }}" title="WF">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

</head>
<body>

    <h3>"Wizdraw (server)" is running out of free storage space.</h3>

    <ul>
        <li>Current project storage folder size is {{ $project['size'] }} {{ $project['unit'] }}</li>
        <li>Current server <b>{{ request()->ip() }}</b> storage size is {{ $server['size'] }} {{ $server['unit'] }}</li>
    </ul>

    <p>Please clear storage space.</p>

</body>
</html>
