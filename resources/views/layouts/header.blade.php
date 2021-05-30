<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="{{ asset('pubic/favicon.ico') }}">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Prometheus V1') }}</title>

	<!-- Fonts -->
	{{-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}

	<!-- Plugin styles -->
	<link href="{{ asset('public/css/toastr.css') }}" rel="stylesheet">
	<link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('public/css/fontawesome/css/all.min.css') }}" rel="stylesheet">
	<link href="{{ asset('public/css/animate.css') }}" rel="stylesheet">
	<link href="{{ asset('public/slick/slick.css') }}" rel="stylesheet">
	<link href="{{ asset('public/slick/slick-theme.css') }}" rel="stylesheet">
	<link href="{{ asset('public/css/bootstrap.css') }}" rel="stylesheet">
	<link href="{{ asset('public/css/bootstrap-select.css') }}" rel="stylesheet">
	<!-- <link href="{{ asset('public/bootstrap-star-rating/css/star-rating.css') }}" rel="stylesheet"> -->
	<!-- <link href="{{ asset('public/bootstrap-star-rating/themes/krajee-fas/theme.min.css') }}" rel="stylesheet"> -->
	<link href="{{ asset('public/css/datatables.min.css') }}" rel="stylesheet">

	<link href="{{ asset('public/css/index.css') }}" rel="stylesheet">

	<link href="{{ asset('public/css/daterangepicker.css') }}" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">
	<style>
		.truncated-text {
			text-overflow: ellipsis;
			overflow: hidden;
			white-space: nowrap;
		}
		.mt-70 {
			margin-top: 70px;
		}

		.footer-top { padding: 60px 0; background: #333; text-align: left; color: #aaa; }
		.footer-top h3 { padding-bottom: 10px; color: #fff; }

		.footer-about img.logo-footer { max-width: 74px; margin-top: 0; margin-bottom: 18px; }
		.footer-about p a { color: #aaa; border-bottom: 1px dashed #666; }
		.footer-about p a:hover, .footer-about p a:focus { color: #fff; border-color: #aaa; }

		.footer-contact p { word-wrap: break-word; }
		.footer-contact i { padding-right: 10px; font-size: 18px; color: #666; }
		.footer-contact p a { color: #aaa; border-bottom: 1px dashed #666; }
		.footer-contact p a:hover, .footer-contact p a:focus { color: #fff; border-color: #aaa; }

		.footer-social a { display: inline-block; margin-right: 20px; margin-bottom: 8px; color: #777; border: 0; }
		.footer-social a:hover, .footer-social a:focus { color: #aaa; border: 0; }
		.footer-social i { font-size: 24px; vertical-align: middle; }

		.footer-bottom { padding: 15px 0; background: #444; text-align: left; color: #aaa; }

		.footer-copyright p { margin: 0; padding: 0.5rem 0; }
		.footer-copyright a { color: #fff; border: 0; }
		.footer-copyright a:hover, .footer-copyright a:focus { color: #aaa; border: 0; }

		/* footer navbar */
		.navbar { padding: 0; background: #444; backface-visibility: hidden; }

		.navbar-dark .navbar-nav { font-size: 15px; color: #fff; font-weight: 400; }
		.navbar-dark .navbar-nav .nav-link { color: #fff; border: 0; }
		.navbar-dark .navbar-nav .nav-link:hover { color: #aaa; }
		.navbar-dark .navbar-nav .nav-link:focus { color: #aaa; outline: 0; }

		.navbar-expand-md .navbar-nav .nav-link { padding-left: 1rem; padding-right: 1rem; }
	</style>
	@stack('styles')

</head>
<body class="animated fadeIn">
<div id="app">
