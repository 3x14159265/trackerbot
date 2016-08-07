@extends('app')
@section('title') TRACKERBOT Dashboard @endsection
@section('content')

<div ng-view></div>

@endsection

@section('scripts')
    <script src="{{ asset('/bower_components/angular/angular.min.js') }}"></script>
    <script src="{{ asset('/bower_components/angular-route/angular-route.min.js') }}"></script>
    <script src="{{ asset('/bower_components/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('/js/app.js') }}"></script>
@endsection
