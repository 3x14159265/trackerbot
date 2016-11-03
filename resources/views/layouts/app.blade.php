<!DOCTYPE html>
<html lang="en" ng-app="tracker">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@section('title') @show</title>
    @show @section('meta_description')
        <meta name="description"
              content=""/>
    @show

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,300" rel="stylesheet" type="text/css">
	<link href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower_components/sweetalert/dist/sweetalert.css') }}" rel="stylesheet">


    @yield('styles')
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="{!! asset('assets/site/ico/favicon.ico')  !!} ">
</head>
<body>
@include('partials.nav')

<div class="container">
@yield('content')
</div>
@include('partials.footer')

<!-- Scripts -->
<script src="{{ asset('/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/js/trackerbot.js') }}"></script>
<script>window.TrackerBot = new TrackerBot('CYXLF4SRHJ6J', true)</script>

@yield('scripts')

</body>
</html>
