
@include('layouts.header')

<!--Navbar-->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">

			<a class="navbar-brand" href="/">
				<img src="{{ asset('public/img/pinchitos.png') }}" width="35" height="35" class="d-inline-block align-top" alt="">
			</a>


			<div class="row">

				<div class="col">
					<a href="/shoppingcart" class="ml-auto">
						<span class="ml-auto mr-2 badge badge-danger d-none d-lg-none" id="cart_counter-2">0</span>
					</a>
					<span id="buy_counter"></span>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
				</div>

			</div>


	<div class="collapse navbar-collapse" id="navbarSupportedContent">


		<div class="col-md-5 col-sm-12 col-xs-12">
			@if(Request::get('buytype') == 'major')
			<form action="/" method="GET" class="my-2 my-lg-0">
			<input type="hidden" name="buytype" value="major">
			@else
			<form action="/" method="GET" class="my-2 my-lg-0">
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
		     <a class="nav-link" href="/"><i class="fas fa-home mr-2" ></i>Inicio</a>
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
		      <a class="dropdown-item" href="{{ route('perfil') }}" id="perfil_count_buy">
						Perfil
					</a>
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


<!-- Footer -->
<footer class="">
	<div class="footer-top bg-dark">
		<div class="container bg-dark">
			<div class="row">
				<div class="col-md-4 col-lg-4 footer-about wow fadeInUp">
					<img class="logo-footer" src="{{ asset('public/img/pinchitos-inv.png') }}" alt="logo-footer" data-at2x="public/img/pinchitos.png">
					<p>
						Servicio en linea sencillo practico y seguro, integrado al sistema de centralizacion de procesos empresariales PROMETHEUS.
					</p>
				</div>
				<div class="col-md-4 col-lg-4 offset-lg-1 footer-contact wow fadeInDown">
					<h3>Dirección:</h3>
						<p>Aragua, Cagua, Centro de Cagua, Calle Sabana Larga entre Rondon y Mariño Local 1 N° 104-10-19 Cerca de las Terrazas.</p>
						<p>comercialmipuchitoca@gmail.com</p>
				</div>
				<div class="col-md-4 col-lg-3 footer-social wow fadeInUp">
					<h3>Enlaces</h3>
					<ul class="list-group list-group-flush">
						<li class="list-group-item list-group-item-action bg-dark">
							<a href="{{url('/')}}">Inicio</a>
						</li>
						@auth
						<li class="list-group-item list-group-item-action bg-dark">
							<a href="{{url('lista-de-deseos')}}">Favoritos</a>
						</li>
						<li class="list-group-item list-group-item-action bg-dark">
							<a href="{{url('shoppingcart')}}">Mi carrito</a>
						</li>
						@endauth
						@guest
						<li class="list-group-item list-group-item-action bg-dark">
							<a onclick="buttonPressed('wish')">Favoritos</a>
						</li>
						<li class="list-group-item list-group-item-action bg-dark">
							<a onclick="buttonPressed('cart')">Mi carrito</a>
						</li>
						<li class="list-group-item list-group-item-action bg-dark">
							<a href="{{url('login')}}">Iniciar sesión</a>
						</li>
						<li class="list-group-item list-group-item-action bg-dark">
							<a href="{{url('register')}}">Registrarse</a>
						</li>
						@endguest
					</ul>
				</div>

			</div>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="container">
			<div class="row">
				<div class="col-md-6 footer-copyright">
					<p>&copy; 2021 Prometheus. Todos los derechos reservados</p>
				</div>
				<div class="col-md-1 footer-copyright align-middle">
					<p><a target="_blank" href="https://www.facebook.com/mipuchitoca-113161223702409/"><i class="fab fa-facebook fa-lg"></i></a></p>
				</div>
				<div class="col-md-1 footer-copyright align-middle">
					<p><a target="_blank" href="https://www.instagram.com/mipuchito.ca/"><i class="fab fa-instagram fa-lg"></i></a></p>
				</div>
				<div class="col-md-1 footer-copyright align-middle">
					<p><a target="_blank" href="https://twitter.com/MipuchitoCa"><i class="fab fa-twitter fa-lg"></i></a></p>
				</div>
				<div class="col-md-1 footer-copyright align-middle">
					<p><a target="_blank" href="https://api.whatsapp.com/send?phone=584243372191"><i class="fab fa-whatsapp fa-lg"></i></a></p>
				</div>
				<div class="col-md-1 footer-copyright align-middle">
					<p><a target="_blank" href="https://mail.google.com/"><i class="fab fa-google-plus-g fa-lg"></i></a></p>
				</div>

			</div>
		</div>
	</div>
</footer>

@include('layouts.footer')
