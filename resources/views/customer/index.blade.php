@extends('layouts.customer')

@section('content')


<div id="page_loader" class="loading">
	<div>
		<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status">
		</div>
		<p>Espere un momento...</p>
	</div>
</div>

<div id="background-top">

</div>

<div class="container-fluid wrapper">
	@if ($bibi != false)
		<div class="card mb-4 shadow-sm">
			<div class="card-body">
				<div class="row no-gutters">
					<div class="col ">
						<h5 class="mt-2">Resultado de la busqueda: <span class="text-info">{{ $bibi }}</span></h5>
					</div>
				</div>

			</div>
		</div>
	@endif
	@if ($senter != false)
		<div class="card mb-4 shadow-sm">
			<div class="card-body">
				<div class="row no-gutters">
					<div class="col ">
						<h5 class="mt-2">Filtrado por empresa: <span class="text-info">{{ $senter->name }}</span></h5>
					</div>
				</div>

			</div>
		</div>
	@endif
	<div class="row">
		<div class="col-lg-3 col-12 order-2 order-lg-1">

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
		<div class="col-lg-9 col-12 order-1 order-lg-2">
			@forelse ($data as $k => $d)
				@if (count($d->inventory) > 0)

					<div class="card shadow-sm mb-4">

						<div class="card-header d-flex justify-content-between align-items-center">
							<a href="{{Request::get('buytype') == 'major'? '/categoria/'.$d->id.'?buytype=major' : '/categoria/'.$d->id}}" style="color: black;">
								<h5 class="negrita">{{ $d->name }}</h5>
							</a>
						</div>

						<div class="card-body bg-light">

							<div class="slickk">

								@php
									$count = 1;
								@endphp
								@foreach ($d->inventory as $producto)

									@break($count == 5)

									@if(Request::get('buytype') != 'major')
									<!--LOGICA PARA CAMBIAR EL ICONO DE LOS BOTONES CUANDO YA ESTA EN EL CARRITO EL PRODUCTO-->
									@php
									if (isset($carrito)) {
										foreach ($carrito as $item) {
											if ($item->options->sale_type == "al-menor") {
												$respuesta = Illuminate\Support\Arr::get($item, 'model.id', 0);
											}

										}
									}
									@endphp

										<div class="card border-info shadow">
												{{-- OTRA VEZ LA IMAGEN DEL CARRUCEL --}}
											<img style="height: 200px; object-fit: contain" data-src="{{ url('storage/app/public/'.$producto->product->image) }}" class="card-img-top">
											<div class="card-body body-producto" id="body-producto">
												@if($producto->product->oferta == 1)
												<small><span class="badge badge-primary mb-2" style="font-size: 1.5em;">Oferta</span></small>
												@endif
												<p class="truncated-text text-center"> {{ $producto->product_name }} </p>

												@if(Request::get('buytype') == 'minor')
													<p class="lead font-weight-light truncated-text text-center">{{ number_format($producto->product->retail_total_price * $dolar->price, 2, ',', '.') }} BsS</p>
												@elseif(Request::get('buytype') == 'major')
													<p class="lead font-weight-light truncated-text text-center">{{ number_format($producto->product->wholesale_total_individual_price * $dolar->price, 2, ',', '.') }} BsS</p>
												@else
													<p class="lead font-weight-light truncated-text text-center">{{ number_format($producto->product->retail_total_price * $dolar->price, 2, ',', '.') }} BsS</p>
												@endif
												<p class="text-left text-success"><small>Dolares:{{ number_format((($producto->product['retail_total_price']* $dolar->price) / $dolar->priceo), 2, ',', '.')}}$</small></p>

													<div class="">

														@if(isset($respuesta) && $respuesta != 0)

															<button
																id="comprar-{{ $producto->id }}"
																type="button"
																class="btn btn-block btn-primary addCartBtn"
																data-id="{{ $producto->id }}"
																data-producto="{{ $producto->product_name }}"
																data-precio="{{ $producto->product->retail_total_price }}"
																data-type="al-menor"
																data-cantidad="1"
															>
																<i class="fas fa-check" style=""></i>
																<label for="comprar-{{ $producto->id }}" class="font-weight-bold"></label>
															</button>



														@else

															<button
															id="comprar-{{ $producto->id }}"
																type="button"
																class="btn btn-block btn-primary addCartBtn"
																data-id="{{ $producto->id }}"
																data-producto="{{ $producto->product_name }}"
																data-precio="{{ $producto->product->retail_total_price }}"
																data-type="al-menor"
																data-cantidad="1"
															>
																<i class="fas fa-shopping-cart" style=""></i>
																Comprar
															</button>
														@endif
													</div>


											</div>
										</div>

									@else
									<!--LOGICA PARA CAMBIAR EL ICONO DE LOS BOTONES CUANDO YA ESTA EN EL CARRITO EL PRODUCTO-->
									@php
									if (isset($carrito)) {
										foreach ($carrito as $item) {
											if ($item->options->sale_type == "al-mayor") {
												$respuesta = Illuminate\Support\Arr::get($item, 'model.id', 0);
											}

										}
									}
									@endphp
										<div class="card border-info  shadow-sm">



											{{-- AQUI EL CARRUSEL DE IMAGENES  --}}


											<img style="height: 200px; object-fit: contain" data-src="{{ url('storage/app/public/'.$producto->product->image) }}"class="card-img-top">
											<div class="card-body body-producto">
												@if($producto->product->oferta == 1)
												<span class="badge badge-danger mb-2" style="font-size: 1.5em;">Oferta</span>
												@endif
												<h6 class="card-title font-weight-bold text-center"> <b>  {{ $producto->product_name }}</b> </h6>
												<p class="text-center">
													<span class="font-weight-bold">Unidad: </span>{{ ucfirst($producto->unit_type) }}<br>
													<span class="font-weight-bold">Cantidad: </span>{{ $producto->qty_per_unit }} <br>
													<span class="font-weight-bold">Precio por unidad: </span>{{ number_format($producto->product->wholesale_total_individual_price, 2, ',', '.') }} <br>
													<span class="font-weight-bold">Subtotal: </span>{{ number_format(($producto->product->wholesale_packet_price), 2, ',', '.') }} <br>
													<span class="font-weight-bold small">Iva: </span>{{number_format($producto->product->wholesale_iva_amount * $producto->qty_per_unit, 2, ',', '.')  }} <br>
												</p>

												<p class="lead font-weight-normal text-center">{{ number_format($producto->product->wholesale_total_packet_price + ($producto->product->wholesale_iva_amount * $producto->qty_per_unit), 2, ',', '.') }} BsS</p>
												<p class="text-right text-success">Dolares:{{ number_format($producto->product->wholesale_total_packet_price / $dolar->price, 2, ',', '.')}}$</p>
												<p class="text-center">{{ $producto->description}}</p>

													@if(isset($respuesta) && $respuesta != 0)

															<button
															id="comprar-{{ $producto->id }}"
																type="button"
															class="btn btn-block btn-primary addCartBtn"
															data-id="{{ $producto->id }}"
															data-producto="{{ $producto->product_name }}"
															data-precio="{{ $producto->product->wholesale_total_packet_price }}"
															data-type="al-mayor"
															data-cantidad="1"
															>
																<i class="fas fa-check"></i>
																<label for="comprar-{{ $producto->id }}"></label>
															</button>

													@else

														<button
															id="comprar-{{ $producto->id }}"
															type="button"
															class="btn btn-block btn-primary addCartBtn"
															data-id="{{ $producto->id }}"
															data-producto="{{ $producto->product_name }}"
															data-precio="{{ $producto->product->wholesale_total_packet_price }}"
															data-type="al-mayor"
															data-cantidad="1"
														>

															<i class="fas fa-shopping-cart mr-2"></i>
															<label for="comprar-{{ $producto->id }}" class="texto-carrito">Comprar</label>
														</button>

													@endif


											</div>
										</div>
									@endif

									@php
										$count++;
									@endphp
								@endforeach
							</div>

						</div>
					</div>

				@endif

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
			<div class="float-right">

			</div>
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
		@if(Request::get('buytype') == 'major')
		window.location = '/categoria/' + category + '?buytype=major'
		@else
		window.location = '/categoria/' + category

		@endif
	}

	function filterEnterprise(enterprise) {
		@if(Request::get('buytype') == 'major')
		window.location = '/empresa/'+enterprise+'?buytype=major';
		@else
		window.location = '/empresa/'+enterprise;
		@endif
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
				//$('#cart_counter').text(res)
				$('#cart_counter').empty()
				$('#cart_counter').append('<i class="fas fa-shopping-cart"></i>'+res)
				$('#cart_counter-2').removeClass('d-none')
				$('#cart_counter-2').empty()
				$('#cart_counter-2').append('<i class="fas fa-shopping-cart"></i>'+res)
				//$('#cart_counter-2').text(res)
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


		// saber si hay elementos en la Agregar a favoritos
		$.get('/get_wishlist', (res) => {
			if (res > 0) {
				$('#wishlist_counter').removeClass('d-none')
				$('#wishlist_counter').text(res)
			}
		})
		.catch((err) => {
			if (err.status != 401) {
				console.log("sin notificacion");
				//toastr.error('Ha ocurrido un error.')
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
					toastr.success('<span class="negrita">Producto añadido al carrito</span>')
					$('#cart_counter').removeClass('d-none')
					//$('#cart_counter').text(res)
					$('#cart_counter').empty()
					$('#cart_counter').append('<i class="fas fa-shopping-cart"></i>'+res)
					$('#cart_counter-2').removeClass('d-none')
					$('#cart_counter-2').empty()
					$('#cart_counter-2').append('<i class="fas fa-shopping-cart"></i>'+res)
					//$('#cart_counter-2').text(res)
				}

				that.removeAttr('disabled')
				that.html('<i class="fas fa-check"></i>')
				$('.texto-carrito').text("Comprar");


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

	})

</script>
@endpush
