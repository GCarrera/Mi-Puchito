@include('layouts.header')


<!--Navbar-->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
	<a class="navbar-brand" href="#">
		 <img src="/img/pinchitos.png" width="35" height="35" class="d-inline-block align-top" alt="">
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">

		<!--<div class="w-50">

			<form class=" my-2 my-lg-0">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text"><i class="fas fa-search"></i></div>
					</div>
					<input class="form-control mr-sm-2" type="search" placeholder="Buscar">
				</div>
			</form>
		</div>-->

		<ul class="navbar-nav ml-auto">
			<li class="nav-item" id="piso-ventas">
				<a class="nav-link" href="{{ route('piso.ventas.index') }}"><i class="fas fa-coins mr-2	"></i>Pisos de ventas</a>
			</li>
			<li class="nav-item" id="despachos">
				<a class="nav-link" href="{{ route('despachos.almacen.index') }}"><i class="fas fa-coins mr-2	"></i>Despachos</a>
			</li>
			<li class="nav-item" id="dolar">
				<a class="nav-link" id="btn_dolar" href="#"><i class="fas fa-dollar-sign mr-2"></i>Dolar</a>
			</li>
			<li class="nav-item" id="admin">
				<a class="nav-link" href="{{ route('admin') }}"><i class="fas fa-coins mr-2	"></i>Ventas</a>
			</li>
			<li class="nav-item" id="inventario">
				<a class="nav-link" href="{{ route('inventario') }}"><i class="fas fa-clipboard-list mr-2"></i>Inventario</a>
			</li>
			<li class="nav-item" id="venta">
				<a class="nav-link" href="{{ route('venta') }}"><i class="fas fa-clipboard-list mr-2"></i>Costos</a>
			</li>
			<li class="nav-item" id="delivery">
				<a class="nav-link" href="{{ route('delivery') }}"><i class="fas fa-clipboard-list mr-2"></i>Delivery</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{ route('usuarios') }}"><i class="fas fa-users-cog mr-2"></i>Usuarios</a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-user mr-2"></i>admin
				</a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
					{{-- <a class="dropdown-item" href="/admin/cuentas-bancarias">Códigos de descuentos</a> --}}
					<a class="dropdown-item" href="/admin/cuentas-bancarias">Cuentas Bancarias</a>
					<a class="dropdown-item" href="{{ route('empresa_categorias') }}">Empresas y Categorias</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" onclick="$('#logoutform').submit()" href="#">Salir</a>
				</div>
			</li>
		</ul>
	</div>
</nav>

<!-- Modal DEL DOLAR -->
<div class="modal fade" id="modal-dolar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<div class="modal-header">
	        	<h5 class="modal-title" id="exampleModalLabel">Precio del dolat</h5>
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          	<span aria-hidden="true">&times;</span>
	        	</button>
      		</div>
	      	<div class="modal-body">
	       	
			<h6 class="font-weight-bold text-success">Precio actual establecido: <span id="dolar-precio"></span></h6>

			<p class="text-center">Establesca un nuevo precio.</p>

			<form action="establecer_dolar" method="post">
				@csrf
				<div class="text-center">
				<input type="text" placeholder="Ejem: 310000" name="precio">
				BS
				<button class="btn btn-primary" type="submit">Establecer</button>
				</div>
			</form>

	      	</div>
	      
    	</div>
  	</div>
</div>

<form class="d-none" id="logoutform" method="post" action="/logout">
	@csrf
</form>
<!--/.Navbar-->
<div id="app" style="height: 100%;">
@yield('content')
</div>
<footer id="sticky-footer" class="py-4 bg-dark text-white-50">
    <div class="container">
    	<p>Copyright &copy; 2020 - PrometheusV1</p>
    </div>
</footer>

@include('layouts.footer')