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
			<form action="/" method="GET" class="my-2 my-lg-0">
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
			@auth
				<li class="nav-item" id="inicio">
					<a class="nav-link" href="/home"><i class="fas fa-home mr-2"></i>Inicio</a>
				</li>
				<li class="nav-item" id="lista-de-deseos">
					<a class="nav-link" href="/lista-de-deseos"><i class="fas fa-heart mr-2"></i>
						<span class="badge badge-secondary d-none" id="wishlist_counter">0</span> Lista de deseos
					</a>
				</li>
				<li class="nav-item" id="shoppingcart">
					<a class="nav-link" href="/shoppingcart"><i class="fas fa-shopping-cart mr-2"></i>
						<span class="badge badge-danger d-none" id="cart_counter">0</span> <span class="d-none d-xl-inline">Mi </span>Carrito
					</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-user mr-2"></i>{{ auth()->user()->people->name }}
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
				<li class="nav-item" id="inicio">
					<a class="nav-link" href="/"><i class="fas fa-home mr-2"></i>Inicio</a>
				</li>
				<li class="nav-item" id="lista-de-deseos">
					<a class="nav-link disabled" data-toggle="tooltip" data-title="Inicia sesión para usar esta función" href="#">
						<i class="fas fa-heart mr-2"></i>
						Lista de deseos
					</a>
				</li>
				<li class="nav-item" id="shoppingcart">
					<a class="nav-link disabled" data-toggle="tooltip" data-title="Inicia sesión para usar esta función" href="#">
						<i class="fas fa-shopping-cart mr-2"></i>
						Mi Carrito
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/login" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-user mr-2"></i>Iniciar Sesión
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
			<div class="col-md-4 col-xl-5 mt-5">
				<div class="pr-xl-4"><a class="brand" href="index.html"><img class="brand-logo-light" src="img/logo.jpg" alt="Mi puchito's" srcset="img/logo.jpg 2x"></a>
					<p>Prometheus es una tienda virtual, dedicada a la venta de todo tipo de productos.</p>
					<!-- Rights-->
					<p class="rights"><span>©  </span><span class="copyright-year">2020</span><span> </span><span>Prometheus</span><span>. </span><span>Todos los derechos reservados.</span></p>
				</div>
			</div>
			<div class="col-md-4 mt-5">
				<h5>Contácto</h5>
				<dl class="contact-list">
					<dt>Dirección:</dt>
					<dd>Aragua, Cagua, Centro de Cagua, Calle Sabana Larga entre Rondon y Mariño Local 1 N° 104-10-19 Cerca de las Terrazas.</dd>
				</dl>
				<dl class="contact-list">
					<dt>Email:</dt>
					<dd><a href="mailto:promemtheus@gmail.com">promemtheus@gmail.com</a></dd>
				</dl>
				<dl class="contact-list">
					<dt>Teléfonos:</dt>
					<dd><a href="tel:04125555555">0412-555-5555</a> <span>ó</span> <a href="tel:04145555555">0414-555-5555</a>
					</dd>
				</dl>
			</div>
			<div class="col-md-4 col-xl-3 mt-5">
				<h5>Enlaces</h5>
				<ul class="nav-list">
					<li><a class="btn" href="{{url('/')}}">Inicio</a></li>
					@auth
						<li><a class="btn" href="{{url('lista-de-deseos')}}">Lista de Deseos</a></li>
						<li><a class="btn" href="{{url('shoppingcart')}}">Mi carrito</a></li>

					@endauth
					@guest
						<li><a class="btn" onclick="buttonPressed('wish')">Lista de Deseos</a></li>
						<li><a class="btn" onclick="buttonPressed('cart')">Mi carrito</a></li>
						 
						<li><a class="btn" href="{{url('login')}}">Iniciar sesión</a></li>
						<li><a class="btn" href="{{url('register')}}">Registrarse</a></li>
					@endguest
				</ul>
			</div>
		</div>
	</div>
    <div class="row no-gutters social-container">
		<div class="col-3 text-center mb-3">
			<a class="social-inner" href="#">
				<i class="fab fa-3x text-center fa-facebook"></i>
			</a>
		</div>
		<div class="col-3 text-center mb-3">
			<a class="social-inner" href="#">
				<i class="fab fa-3x fa-instagram"></i>
			</a>
		</div>
		<div class="col-3 text-center mb-3">
			<a class="social-inner" href="#">
				<i class="fab fa-3x fa-twitter"></i>
			</a>
		</div>
		<div class="col-3 text-center mb-3">
			<a class="social-inner" href="#">
				<i class="fab fa-3x fa-google" aria-hidden="true"></i>
			</a>
		</div>
    </div>
</footer>

@include('layouts.footer')
