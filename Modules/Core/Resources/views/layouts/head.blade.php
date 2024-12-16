<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/assets/img/logo-fav.png">
    <title>{{ env('APP_NAME') }} :: {{$page_title??''}}</title>
    <link rel="stylesheet" type="text/css" href="/assets/lib/perfect-scrollbar/css/perfect-scrollbar.css" />
    <link rel="stylesheet" type="text/css" href="/assets/lib/material-design-icons/css/material-design-iconic-font.min.css" />
    <link rel="stylesheet" href="/assets/lib/jquery.gritter/css/jquery.gritter.css" type="text/css" />
    <link rel="stylesheet" href="/assets/css/app.css" type="text/css" />
    <link rel="stylesheet" href="/assets/css/font.css" type="text/css" />
    @yield('css')
</head>
