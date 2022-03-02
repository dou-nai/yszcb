
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>云书展</title>
    <meta name="keywords" content="云书展">
    <meta name="description" content="云书展" />

</head>

<body>

<div id="app">
    <app></app>
</div>


</body>

<script src="{{ mix('js/app.js','dist') }}"></script>
