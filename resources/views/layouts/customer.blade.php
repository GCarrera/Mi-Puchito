@include('layouts.header')

<!--Navbar-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
	<a class="navbar-brand" href="#">
		 <img src="/img/brand.png" width="35" height="35" class="d-inline-block align-top" alt="">
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">

		<div class="w-50">
			<form class="my-2 my-lg-0">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text"><i class="fas fa-search"></i></div>
					</div>
					<input class="form-control mr-sm-2 SearchProductNameAutoComplete" type="search" placeholder="Buscar productos">
				</div>
			</form>
		</div>

		<ul class="navbar-nav ml-auto">
			<li class="nav-item" id="inicio">
				<a class="nav-link" href="/home"><i class="fas fa-home mr-2"></i>Inicio</a>
			</li>
			@auth
				<li class="nav-item" id="lista-de-deseos">
					<a class="nav-link" href="/lista-de-deseos"><i class="fas fa-heart mr-2"></i>
						<span class="badge badge-secondary d-none" id="wishlist_counter">0</span> Lista de deseos
					</a>
				</li>
				<li class="nav-item" id="shoppingcart">
					<a class="nav-link" href="/shoppingcart"><i class="fas fa-shopping-cart mr-2"></i>
						<span class="badge badge-danger d-none" id="cart_counter">0</span> Mi Carrito
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
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-user mr-2"></i>Invitado
					</a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
						<a class="dropdown-item"  href="/login">Iniciar sesión</a>
					</div>
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


<footer id="sticky-footer" class="py-4 bg-dark text-white-50">
    <div class="container">
    	<p>Copyright &copy; 2020 - PrometheusV1</p>
    </div>
</footer>

@include('layouts.footer')