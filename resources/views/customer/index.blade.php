@extends('layouts.customer')

@section('content')


<div id="page_loader" class="loading">
	<div>
		<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status">
		</div>
		<p>Espere un momento...</p>
	</div>
</div>


{{-- <div id="carouselExampleIndicators" class="carousel slide mt-5" data-ride="carousel">
	<ol class="carousel-indicators">
		<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
		<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
		<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
		<li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
		<li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
		<li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
	</ol>
	<div class="carousel-inner">
		<div class="carousel-item active">
			<img src="/img/banner1.png" style="height: 470px" class="d-block w-100" alt="...">
		</div>
		<div class="carousel-item">
			<img src="/img/banner2.jpg" style="height: 470px" class="d-block w-100" alt="...">
		</div>
		<div class="carousel-item">
			<img src="/img/banner3.jpg" style="height: 470px" class="d-block w-100" alt="...">
		</div>
		<div class="carousel-item">	
			<img src="/img/banner4.jpg" style="height: 470px" class="d-block w-100" alt="...">
		</div>
		<div class="carousel-item">
			<img src="/img/banner5.jpg" style="height: 470px" class="d-block w-100" alt="...">
		</div>
		<div class="carousel-item">
			<img src="/img/banner6.jpg" style="height: 470px" class="d-block w-100" alt="...">
		</div>
	</div>
	<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div> --}}

<div id="background-top">

</div>

<!--
<img src="img/amazon.jpg" alt="" class="img-fluid mt-5" style="background-size: cover; background-position: center; height: 30%;">
-->
<div class="container-fluid wrapper">
	<div class="row">
		<div class="col-lg-3 col-12">
			<div class="card shadow-sm ">
				<div class="card-body">
					<div class="form-group mb-4">
						<label for="empresas">Usted está comprando:</label>
						<div class="nav-list">
							<a class="list-group-item list-group-item-action {{ Request::get('buytype') == 'major' ? 'active' : 'normal' }}" href="{{url('?buytype=major')}}">Al mayor</a>
							<a class="list-group-item list-group-item-action {{ Request::get('buytype') != 'major' ? 'active' : 'normal' }}" href="{{url('?buytype=minor')}}">Al menor</a>
						</div>
					</div>
				</div>
			</div>
			<div class="card shadow-sm mb-4">
				<div class="card-header">
					<h5>Filtrado de productos</h5>
				</div>
				<div class="card-body">
					<form>
						<div class="form-group mb-4">
							<label for="empresas">Por empresas</label>
							<select class="form-control" id="empresas" onchange="filterEnterprise(this.value)">
								<option disabled selected>Selecciona una empresa</option>
								<option value=""> Todos</option>
								@foreach ($empresas as $emp)
									{{Request::get('enterprise') == $emp->id ? $selected = 'selected' : $selected = ''}}
									<option value="{{ $emp->id }}" {{$selected}}>{{ $emp->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group mb-4">
							<label for="empresas">Por categorias</label>
							<select class="form-control" id="categorias" onchange="filterCategory(this.value)">
								<option disabled selected >Selecciona una categoria</option>
								<option value="">Todas</option>
								@foreach ($categories as $categoria)
									{{Request::get('category') == $categoria->id ? $selected = 'selected' : $selected = ''}}
									<option value="{{ $categoria->id }}" {{$selected}}>{{ $categoria->name }}</option>
								@endforeach
							</select>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-lg-9 col-12">
			@forelse ($data as $k => $d)
				<div class="card shadow-sm mb-4">
					
					<div class="card-header d-flex justify-content-between align-items-center">
						<a href="/categoria/{{ $d->id }}" style="color: black;">
							<h5 class="font-weight-bold">{{ $d->name }}</h5>
						</a>
						{{-- <a href="/categoria/{{ $k }}" class="btn btn-primary">Ver todos</a> --}}
					</div>
					
					<div class="card-body bg-light">

						<div class="slickk">
							@foreach ($d->inventory as $producto)
							
							
								@if(Request::get('buytype') != 'major')
								<!--LOGICA PARA CAMBIAR EL ICONO DE LOS BOTONES CUANDO YA ESTA EN EL CARRITO EL PRODUCTO-->
								@php
								if (isset($carrito)) {
									foreach ($carrito as $item) {
										if ($item->attributes->sale_type == "al-menor") {
											# code...
											$respuesta = Illuminate\Support\Arr::get($item, 'associatedModel.id', 0);
										}	
														
									}
								}
								@endphp
									
									<div class="card shadow">
										@if($producto->product->oferta == 1)
										<span class="badge badge-danger rounded-circle" style="position: absolute; right: 0px; top: 5px; font-size: 1.5em;">Oferta</span>
										@endif
										<img style="height: 200px; object-fit: contain" src="{{ url('storage/'.$producto->product->image) }}" class="card-img-top">
										<div class="card-body body-producto" id="body-producto">
											<h5 class="card-title font-weight-bold truncated-text text-center">{{ $producto->product_name }}</h5>

											{{-- <input name="star-rating" value="3.4" class="kv-ltr-theme-fas-star star-rating rating-loading" data-size="xs"> --}}
											<h6 class="font-weight-normal truncated-text text-center">Subtotal: <span class="font-weight-bold">{{number_format($producto->product->retail_total_price - $producto->product->retail_iva_amount, 2, ',', '.') }}</span></h6>
											<h6 class="font-weight-normal truncated-text text-center">Iva: <span class="font-weight-bold">{{ number_format($producto->product->retail_iva_amount, 2, ',', '.') }}</span></h6> 
											@if(Request::get('buytype') == 'minor')
												<p class="lead font-weight-light truncated-text text-center">{{ number_format($producto->product->retail_total_price, 2, ',', '.') }} Bs</p>
											@elseif(Request::get('buytype') == 'major')
												<p class="lead font-weight-light truncated-text text-center">{{ number_format($producto->product->wholesale_total_individual_price, 2, ',', '.') }} Bs</p>
											@else
												<p class="lead font-weight-light truncated-text text-center">{{ number_format($producto->product->retail_total_price, 2, ',', '.') }} Bs</p>
											@endif

											@auth
											
											<div class="">
												<div class="row text-center">
													<div class="col-6">
														<button 
															data-id="{{ $producto->product->id }}" 
															class="btn btn-block addToWishlist"
															data-producto="{{ $producto->product_name }}"
															data-precio="{{ $producto->product->retail_total_price }}"

														>
															<i class="fa fa-heart" style="color: #dc3545;"></i>
														</button>
														<b>Lista de Deseos</b>
													</div>
													@if(isset($respuesta) && $respuesta != 0)
													<div class="col-6">
														<button
															type="button"
															class="btn btn-block addCartBtn"
															data-id="{{ $producto->id }}"
															data-producto="{{ $producto->product_name }}"
															data-precio="{{ $producto->product->retail_total_price }}"
															data-type="al-menor"
															data-cantidad="1"
														>
															<i class="fas fa-check" style="color: #28a745;"></i>
														</button>
														<b>Producto agregado</b>
													</div>
													
													@else
													<div class="col-6">
														<button
															type="button"
															class="btn btn-block addCartBtn"
															data-id="{{ $producto->id }}"
															data-producto="{{ $producto->product_name }}"
															data-precio="{{ $producto->product->retail_total_price }}"
															data-type="al-menor"
															data-cantidad="1"
														>
															<i class="fas fa-shopping-cart" style="color: #007bff;"></i>
														</button>
														<b class="texto-carrito">Agregar al carrito</b>
													</div>
													@endif
												</div>
											</div>
												
											@else
											<div class="">
												<div class="row text-center">
													<div class="col-6">
														<button 
															onclick="buttonPressed('wish')" 
															type="button" 
															class="btn btn-block"
															data-title="Añadir a la lista de deseos" 
															data-toggle="tooltip"
														>
															<i class="fa fa-heart" style="color: #dc3545;"></i>
														</button>

														<b>Lista de Deseos</b>
													</div>
													<div class="col-6">
														<button 
															onclick="buttonPressed('cart')" 
															class="btn btn-block"
															data-title="Comprar" 
															data-toggle="tooltip"
														>
															<i class="fas fa-shopping-cart" style="color: #007bff;"></i>
														</button>
														<b>Agregar al carrito</b>
													</div>
												</div>
											</div>		
											@endauth

										</div>
									</div>

								@else
								<!--LOGICA PARA CAMBIAR EL ICONO DE LOS BOTONES CUANDO YA ESTA EN EL CARRITO EL PRODUCTO-->
								@php
								if (isset($carrito)) {
									foreach ($carrito as $item) {
										if ($item->attributes->sale_type == "al-mayor") {
											# code...
											$respuesta = Illuminate\Support\Arr::get($item, 'associatedModel.id', 0);
										}	
														
									}
								}
								@endphp
									<div class="card shadow-sm">
										@if($producto->product->oferta == 1)
										<span class="badge badge-danger rounded-circle" style="position: absolute; right: 0px; top: 5px; font-size: 1.5em;">Oferta</span>
										@endif
										<img style="height: 200px; object-fit: contain" src='storage/{{ $producto->product->image }}' class="card-img-top">
										<div class="card-body text-center body-producto">
											<h5 class="card-title font-weight-bold">{{ $producto->product_name }}</h5>

											<p>
												<span class="font-weight-bold">Unidad: </span>{{ ucfirst($producto->unit_type) }}<br>
												<span class="font-weight-bold">Cantidad: </span>{{ $producto->qty_per_unit }} <br>
												<span class="font-weight-bold">Precio por unidad: </span>{{ number_format($producto->product->wholesale_total_individual_price, 2, ',', '.') }} <br>
												<span class="font-weight-bold">Subtotal: </span>{{ number_format(($producto->product->wholesale_packet_price - $producto->product->wholesale_iva_amount), 2, ',', '.') }} <br>
												<span class="font-weight-bold">Iva: </span>{{number_format($producto->product->wholesale_iva_amount, 2, ',', '.')  }} <br>
											</p>

											<p class="lead font-weight-normal">{{ number_format($producto->product->wholesale_total_packet_price, 2, ',', '.') }} Bs</p>

											@auth
											<div class="row text-center">

												<div class="col-6">
													<button data-id="{{ $producto->id }}" class="btn btn-block mb-2 addToWishlist">
														<i class="fa fa-heart" data-toggle="tooltip" data-title="Agregar a favoritos" style="color: #dc3545;"></i>
														<b class="d-block">Lista de deseos</b>
													</button>
												</div>
												@if(isset($respuesta) && $respuesta != 0)
													<div class="col-6">
														<button
															type="button"
														class="btn btn-block addCartBtn"
														data-id="{{ $producto->id }}"
														data-producto="{{ $producto->product_name }}"
														data-precio="{{ $producto->product->wholesale_total_packet_price }}"
														data-type="al-mayor"
														data-cantidad="1"
														>
															<i class="fas fa-check" style="color: #28a745;"></i>
														</button>
														<b>Producto agregado</b>
													</div>
													
												@else
												<div class="col-6">
													<button
														type="button"
														class="btn btn-block addCartBtn"
														data-id="{{ $producto->id }}"
														data-producto="{{ $producto->product_name }}"
														data-precio="{{ $producto->product->wholesale_total_packet_price }}"
														data-type="al-mayor"
														data-cantidad="1"
													>

														<i class="fas fa-shopping-cart mr-2" style="color: #007bff;"></i>
													</button>
													<b class="texto-carrito">Agregar al carrito</b>
												</div>
												@endif
											</div>
											@else
												<button  class="btn btn-block mb-2" disabled>
													<i class="fa fa-heart" data-toggle="tooltip" data-title= "Agregar a favoritos" style="color: #dc3545;"></i>
												</button>
												<button type="button" class="btn btn-block" disabled>
													<i class="fas fa-shopping-cart mr-2"></i><span class="text" style="color: #007bff;">Comprar</span>
												</button>
											@endauth

										</div>
									</div>
								@endif
							@endforeach
						</div>

					</div>
				</div>
			@empty

				<div class="card shadow-sm mb-4">
					<div class="card-body py-5">
						<h4 class="text-center">
							<i class="fas fa-shopping-cart fa-2x"></i><br>
							<p class="mt-4">No hay productos disponibles para la venta.</p>
						</h4>
					</div>
				</div>

			@endforelse

		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>

	function getParameterByName(name) {
		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
		return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}

	function filterCategory(category) {
		window.location = '/categoria/' + category
	}

	function filterEnterprise(enterprise) {
		window.location = '?enterprise=' + enterprise
	}

	$(() => {

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		toastr.options = {
			"closeButton": true,
			"progressBar": true,
			"positionClass": "toast-bottom-left",
			"timeOut": 10000
		}

		$('.slickk').slick({
			dots: true,
			centerMode: true,
			centerPadding: '60px',
			slidesToShow: 3,
			slidesToScroll: 1,
			autoplay: true,
			autoplaySpeed: 2000,
			infinite: true,
			responsive: [
				{
					breakpoint: 768,
					settings: {
						centerMode: false,
						dots: true,
						arrows: true,
						slidesToShow: 3
					}
				},
				{
					breakpoint: 576,
					settings: {
						centerMode: false,
						dots: true,
						arrows: true,
						slidesToShow: 2
					}
				},
				{
					breakpoint: 480,
					settings: {
						centerMode: false,
						dots: true,
						arrows: true,
						slidesToShow: 1,
					}
				}
			]
		});

		$('.SearchProductNameAutoComplete').autoComplete({
			minLength: 2,
			resolverSettings: {
				url: '/traer_productos',
			}
		});

		// saber si hay elementos en el carrito para mostrar la candidad en color rojo en el navbar
		$.get('/get_shoppingcart', (res) => {
			if (res > 0) {
				$('#cart_counter').removeClass('d-none')
				$('#cart_counter').text(res)
				$('#cart_counter-2').removeClass('d-none')
				$('#cart_counter-2').text(res)
			}

			$('#page_loader').fadeOut()
		})
		.catch((err) => {
			if (err.status != 401) {
				toastr.error('Ha ocurrido un error.')
				console.error(err)
			}
			$('#page_loader').fadeOut()
		})


		// saber si hay elementos en la lista de deseos
		$.get('/get_wishlist', (res) => {
			if (res > 0) {
				$('#wishlist_counter').removeClass('d-none')
				$('#wishlist_counter').text(res)
			}
		})
		.catch((err) => {
			if (err.status != 401) {
				toastr.error('Ha ocurrido un error.')
				console.error(err)
			}
		})


		$('.addCartBtn').click(function(e){

			let data = $(this).data()
			let that = $(this)

			$.post({
				url: '/shoppingcart',
				data,
				beforeSend(){
					that.attr('disabled', true)
					that.html('<i class="fas fa-circle-notch fa-spin fa-"></i>')
				}
			})
			.done((res) => {
				console.log(res)
				
				if (res == 'rejected') {
					toastr.info('Este producto ya está agregado con un tipo de compra distinto.')
				} else {
					toastr.success('<i>Producto añadido al carrito</i>')
					$('#cart_counter').removeClass('d-none')
					$('#cart_counter').text(res)
					$('#cart_counter-2').removeClass('d-none')
					$('#cart_counter-2').text(res)
				}

				that.removeAttr('disabled')
				that.html('<i class="fas fa-check" style="color: #28a745;"></i>')
				$('.texto-carrito').text("Producto agregado");
				
				
			})
			.fail((err) => {
				toastr.error('Ha ocurrido un error.')
				console.error(err)
			})
		});

		// Para añadir cuando está logueado
		
		$('.addToWishlist').click(function(){
			let producto = $(this).data('id')
			let that = $(this)

			$.post({
				url: '/lista-de-deseos',
				data: { productoid: producto },
				beforeSend(){
					that.attr('disabled', true)
					that.html('<i class="fas fa-circle-notch fa-spin fa-"></i>')
				}
			})
			.done((res) => {
				if (res.type == 'warning') {
					toastr.warning(res.mess)
				}
				else {
					toastr.success(res.mess)
					$('#wishlist_counter').removeClass('d-none')
					$('#wishlist_counter').text(res.wl_qty)
				}

				that.removeAttr('disabled')
				that.html('<i class="fas fa-heart"></i>')
			})
			.fail((err) => {
				console.log(err)
			})
		})
		

		$('#typebtn').click(() => {
			let tipo_prod = $('#tipo_prod').val()

			if (tipo_prod == 'mayor') {
				window.location.href = '/ventas-al-mayor'
			}
			else if (tipo_prod == 'menor') {
				window.location.href = '/home'
			}
			else {
				$('#tipo_prod')[0].setCustomValidity('Selecciona una opción')
			}
		})

	 //    $('.star-rating').rating({
	 //    	displayOnly: true,
		// 	theme: 'krajee-fas',
		// 	containerClass: 'is-star',
		// 	starCaptions: {1: 'Muy malo', 2: 'Malo', 3: 'Más o menos', 4: 'Bueno', 5: 'Excelente'},
		// 	starCaptionClasses: {1: 'text-danger', 2: 'text-warning', 3: 'text-info', 4: 'text-primary', 5: 'text-success'},
		// });

	})

</script>
@endpush