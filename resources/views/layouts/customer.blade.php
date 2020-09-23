
@include('layouts.header')

<!--Navbar-->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
	<a class="navbar-brand" href="/">
		 <img src="/img/pinchitos.png" width="35" height="35" class="d-inline-block align-top" alt="">
	</a>
	<a href="/shoppingcart" class="ml-auto">
	<span style="line-height: 20px; font-size: 2em" class="ml-auto mr-2 badge badge-danger d-none d-lg-none" id="cart_counter-2" style="width: 35px; height: 25px;">0</span>
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>

	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">

	
		<div class="col-md-6 col-sm-12 col-xs-12">
			@if(Request::get('buytype') == 'major')
			<form action="" method="GET" class="my-2 my-lg-0">
			<input type="hidden" name="buytype" value="major">
			@else
			<form action="" method="GET" class="my-2 my-lg-0">
			@endif
				<div class="input-group mb-2">
					<input name="search" class="form-control SearchProductNameAutoComplete" type="search" placeholder="Buscar productos" aria-describedby="button-addon2">
					<div class="input-group-append">
						<button class="btn btn-primary input-group-text" type="submit" id="button-addon2">
							<i class="fas fa-search"></i>
						</button>
					</div>
				</div>		
			</form>
		</div>

		<ul class="navbar-nav ml-auto">
		   
		    <li class="nav-item" id="inicio">
		     <a class="nav-link" href="/home"><i class="fas fa-home mr-2" ></i>Inicio</a>
		    </li>
		    <li class="nav-item" id="lista-de-deseos">
		    @guest
		    <li class="nav-item" id="lista-de-deseos">
				<a class="nav-link disabled" data-toggle="tooltip" data-title="Inicia sesión para usar esta función" href="#">
					<i class="fas fa-heart mr-2"></i>
					Favoritos
				</a>
			</li>
			@endguest
			@auth
		     <a class="nav-link" href="/lista-de-deseos"><i class="fas fa-heart mr-2" ></i>
		      <span class="badge badge-danger d-none" id="wishlist_counter">0</span><span style="font-size: 12px"> Favoritos</span>
		     </a>
		    </li>
		    @endauth
		    <li class="nav-item" id="shoppingcart">
		     <a class="nav-link" href="/shoppingcart"><i class="fas fa-shopping-cart mr-2" ></i>
		      <span class="badge badge-danger d-none" id="cart_counter">0</span> <span style="font-size: 12px">Carrito</span>
		     </a>
		    </li>
		   @auth
		    <li class="nav-item dropdown">
		     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		      <i class="fas fa-user mr-2" ></i>{{ auth()->user()->people->name }}
		     </a>
		     <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
		      <a class="dropdown-item" href="{{ route('perfil') }}">Perfil</a>
		      {{-- <a class="dropdown-item" href="#">Historial</a> --}}
		      {{-- <a class="dropdown-item" href="#">Configuración</a> --}}
		      <div class="dropdown-divider"></div>
		      <a class="dropdown-item" onclick="$('#logoutform').submit()" href="#">Salir</a>
		     </div>
		    </li>
		   @endauth
		   @guest
	
		    <li class="nav-item">
		     <a class="nav-link" href="{{ route('register') }}" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">
		      <i class="fas fa-user mr-2"></i>Registrarme
		     </a>
		    </li>
		    <li class="nav-item">
		     <a class="nav-link" href="/login" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">
		      <i class="fas fa-sign-in-alt mr-2"></i>Entrar
		     </a>
		    </li>
		   @endguest
		  </ul>
	</div>
</nav>

<form class="d-none" id="logoutform" method="post" action="/logout">
	@csrf
</form>
<!--/.Navbar-->

@yield('content')


<footer class="section footer-classic context-dark bg-image bg-light">
	<div class="container">
		<div class="row row-30 text-center">
			<div class="col-md-4 col-xl-4 mt-5">
				<div class="pr-xl-4"><a class="brand" href="index.html"><img class="brand-logo-light" src="/img/pinchitos.png" alt="Mi puchito's"  style="width: 35%;"></a>
					<p class="text-justify">Servicio en linea sencillo practico y seguro, integrado al sistema de centralizacion de procesos empresariales PROMETHEUS.</p>
					
				</div>
			</div>
			<div class="col-md-4 mt-5 mb-3">
				<i class="far fa-address-book" style="font-size: 5em; color: #007bff;"></i>
				<dl class="contact-list">
					<dt>Dirección:</dt>
					<dd>Aragua, Cagua, Centro de Cagua, Calle Sabana Larga entre Rondon y Mariño Local 1 N° 104-10-19 Cerca de las Terrazas.</dd>
				</dl>
				<dl class="contact-list">
					<dt><i class="far fa-envelope" style="color: #dc3545; font-size: 1.5em;" ></i></dt>
					<dd>comercialmipuchitoca@gmail.com</dd>
				</dl>
				
			</div>
			<div class="col-md-4 col-xl-4 mt-5">
				<i class="fas fa-search" style="font-size: 4.5em;"></i>
				<p class="font-weight-bold">Enlaces:</p>
				<ul class="nav-list text-left">
					<li><a class="" href="{{url('/')}}">Inicio</a></li>
					@auth
						<li><a class="" href="{{url('lista-de-deseos')}}">Favoritos</a></li>
						<li><a class="" href="{{url('shoppingcart')}}">Mi carrito</a></li>

					@endauth
					@guest
						<li><a class="" onclick="buttonPressed('wish')">Favoritos</a></li>
						<li><a class="" onclick="buttonPressed('cart')">Mi carrito</a></li>
						 
						<li><a class="" href="{{url('login')}}">Iniciar sesión</a></li>
						<li><a class="" href="{{url('register')}}">Registrarse</a></li>
					@endguest
				</ul>
			</div>
		</div>
	</div>
    <div class="row no-gutters social-container">
		<div class="col-3 text-center mb-3">
			<a class="social-inner" target="_blank" href="https://www.facebook.com/mipuchitoca-113161223702409/">
				<i class="fab fa-3x text-center fa-facebook"></i>
			</a>
		</div>
		<div class="col-2 text-center mb-3">
			<a class="social-inner" target="_blank" href="https://www.instagram.com/mipuchito.ca/">

				<img src="/img/icon-instagram.svg" alt="" width="100" height="80" style="position: relative; top: -30px; right: 20px; overflow-y: hidden;">
			</a>
		</div>
		<div class="col-2 text-center mb-3">
			<a class="social-inner" target="_blank" href="https://twitter.com/MipuchitoCa">
				<i class="fab fa-3x fa-twitter"></i>
			</a>
		</div>
		<div class="col-2 text-center mb-3">
			<a class="social-inner" target="_blank" href="https://api.whatsapp.com/send?phone=584243372191">
				<i class="fab fa-3x fa-whatsapp" style="color: #28a745;"></i>
			</a>
		</div>
		<div class="col-3 text-center mb-3">
			<a class="social-inner" target="_blank" href="https://mail.google.com/">
				<i class="far fa-3x fa-envelope" style="color: #dc3545;" ></i>
			</a>
		</div>
    </div>
    <div style="background-color: teal; color: black;">
    	<p class="text-center text-white m-0 py-3"><span>©</span>2020 Prometheus. Todos los derechos reservados</p>
    </div>
</footer>

@include('layouts.footer')
