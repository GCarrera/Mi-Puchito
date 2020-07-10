@extends('layouts.customer')

@section('content')


<div id="page_loader" class="loading">
	<div>
		<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status">
		</div>
		<p>Espere un momento...</p>
	</div>
</div>


<div id="carouselExampleIndicators" class="carousel slide mt-5" data-ride="carousel">
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
</div>



<div class="container-fluid my-5 wrapper">
	<div class="row">
		<div class="col-lg-3 col-12">
			<div class="card shadow-sm mb-4">
				<div class="card-body">
					<div class="form-group mb-4">
						<label for="empresas">Mostrar los productos por el tipo de compra</label>
						<select class="form-control" id="tipo_prod">
							<option disabled selected>Selecciona</option>
							<option value="mayor">Al Mayor</option>
							<option value="menor">Al Menor</option>
						</select>
					</div>

					<button class="btn btn-primary btn-block mr-2" type="button" id="typebtn">
						<i class="fas fa-shopping-cart mr-2"></i>Mostrar
					</button>
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
							<select class="form-control" id="empresas">
								<option disabled selected>Selecciona una empresa</option>
								@foreach ($empresas as $emp)
									<option {{ $emp->id }}>{{ $emp->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group mb-4">
							<label for="empresas">Por categorias</label>
							<select class="form-control" id="categorias">
								<option disabled selected>Selecciona una categoria</option>
								@foreach ($categorias as $categoria)
									<option {{ $categoria->id }}>{{ $categoria->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="d-flex justify-content-center">
							<button class="btn btn-primary mr-2" type="reset">
								<i class="fas fa-sync mr-2"></i>Reiniciar
							</button>

							<button class="btn btn-primary" type="reset">
								<i class="fas fa-search mr-2"></i>Filtrar
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-lg-9 col-12">

			@forelse ($data as $k => $d)

				<div class="card shadow-sm mb-4">

					<div class="card-header d-flex justify-content-between align-items-center">
						<h5>{{ $k }}</h5>
						{{-- <a href="/categoria/{{ $k }}" class="btn btn-primary">Ver todos</a> --}}
					</div>
					<div class="card-body">

						<div class="slickk">
							@foreach ($d as $producto)
								@if( ! $al_mayor )
									
									<div class="card shadow-sm">
										<img style="height: 200px; object-fit: contain" src='storage/{{ $producto->image }}' class="card-img-top">
										<div class="card-body">
											<h5 class="card-title font-weight-light">{{ $producto->inventory->product_name }}</h5>

											{{-- <input name="star-rating" value="3.4" class="kv-ltr-theme-fas-star star-rating rating-loading" data-size="xs"> --}}

											<p class="lead font-weight-normal">{{ number_format($producto->retail_total_price, 2, ',', '.') }} Bs</p>

											@auth
												<button data-id="{{ $producto->id }}" class="btn btn-outline-primary btn-block mb-2 addToWishlist">
													<i class="fa fa-heart" data-toggle="tooltip" data-title="Agregar a favoritos"></i>
												</button>
												<button
													type="button"
													class="btn btn-primary btn-block addCartBtn"
													data-id="{{ $producto->id }}"
													data-producto="{{ $producto->inventory->product_name }}"
													data-precio="{{ $producto->retail_total_price }}"
													data-type="al-menor"
													data-cantidad="1"
												>

													<i class="fas fa-shopping-cart mr-2"></i><span class="text">Comprar</span>
												</button>
											@else
												<button class="btn btn-outline-primary btn-block mb-2" disabled>
													<i class="fa fa-heart" data-toggle="tooltip" data-title="Inicia sesión para usar esta función"></i>
												</button>
												<button type="button" class="btn btn-primary btn-block" disabled>
													<span data-toggle="tooltip" data-title="Inicia sesión para poder comprar">
														<i class="fas fa-shopping-cart mr-2"></i><span class="text">Comprar</span>
													</span>
												</button>
											@endauth

										</div>
									</div>

								@else
									<div class="card shadow-sm">
										<img style="height: 200px; object-fit: contain" src='storage/{{ $producto->image }}' class="card-img-top">
										<div class="card-body">
											<h5 class="card-title font-weight-light">{{ $producto->inventory->product_name }}</h5>

											<p>
												<span class="font-weight-bold">Unidad: </span>{{ ucfirst($producto->inventory->unit_type) }}<br>
												<span class="font-weight-bold">Cantidad: </span>{{ $producto->inventory->qty_per_unit }}
											</p>

											<p class="lead font-weight-normal">{{ number_format($producto->wholesale_total_packet_price, 2, ',', '.') }} Bs</p>

											@auth
												<button data-id="{{ $producto->id }}" class="btn btn-outline-primary btn-block mb-2 addToWishlist">
													<i class="fa fa-heart" data-toggle="tooltip" data-title="Agregar a favoritos"></i>
												</button>
												<button
													type="button"
													class="btn btn-primary btn-block addCartBtn"
													data-id="{{ $producto->id }}"
													data-producto="{{ $producto->inventory->product_name }}"
													data-precio="{{ $producto->wholesale_total_packet_price }}"
													data-type="al-mayor"
													data-cantidad="1"
												>

													<i class="fas fa-shopping-cart mr-2"></i><span class="text">Comprar</span>
												</button>
											@else
												<button  class="btn btn-outline-primary btn-block mb-2" disabled>
													<i class="fa fa-heart" data-toggle="tooltip" data-title="Agregar a favoritos"></i>
												</button>
												<button type="button" class="btn btn-primary btn-block" disabled>
													<i class="fas fa-shopping-cart mr-2"></i><span class="text">Comprar</span>
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
				// console.log(res)
				if (res == 'rejected') {
					toastr.info('Este producto ya está agregado con un tipo de compra distinto.')
				}
				else {

					$('#cart_counter').removeClass('d-none')
					$('#cart_counter').text(res)
				}

				that.removeAttr('disabled')
				that.html('<i class="fas fa-shopping-cart mr-2"></i><span class="text">Comprar</span>')
			})
			.fail((err) => {
				toastr.error('Ha ocurrido un error.')
				console.error(err)
			})
		});

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