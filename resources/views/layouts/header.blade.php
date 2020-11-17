<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="{{ asset('favicon.ico') }}">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Prometheus V1') }}</title>

	<!-- Fonts -->
	{{-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}

	<!-- Plugin styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/fontawesome/css/all.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/animate.css') }}" rel="stylesheet">
	<link href="{{ asset('slick/slick.css') }}" rel="stylesheet">
	<link href="{{ asset('slick/slick-theme.css') }}" rel="stylesheet">
	<link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
	<link href="{{ asset('css/toastr.css') }}" rel="stylesheet">
	<link href="{{ asset('css/bootstrap-select.css') }}" rel="stylesheet">
	<!-- <link href="{{ asset('bootstrap-star-rating/css/star-rating.css') }}" rel="stylesheet"> -->
	<!-- <link href="{{ asset('bootstrap-star-rating/themes/krajee-fas/theme.min.css') }}" rel="stylesheet"> -->
	<link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">

	<link href="{{ asset('css/index.css') }}" rel="stylesheet">

	<link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet">
	<style>
		.truncated-text {
			text-overflow: ellipsis;
			overflow: hidden;
			white-space: nowrap;
		}
		.mt-70 {
			margin-top: 70px;
		}
	</style>
	@stack('styles')

</head>
<body class="animated fadeIn">
