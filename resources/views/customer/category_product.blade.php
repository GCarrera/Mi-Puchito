@extends('layouts.customer')

@section('content')


<div id="page_loader" class="loading">
	<div>
		<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status">
		</div>
		<p>Espere un momento...</p>
	</div>
</div>

<img data-src="/img/banner1.png" style="height: 470px; margin-top: 4%;" class="d-block w-100" alt="...">

<div class="container-fluid my-5 animated wrapper">

	<div class="card mb-4 shadow-sm">
		<div class="card-body">
			<div class="row no-gutters">
				<div class="col-4 col-md-2">
					<h5 class="mt-2">{{ $data->name }}</h5>
				</div>
				<div class="col col-md-5">
					<p class="mb-0 mt-2 d-none d-md-block">{{ count($data->inventory) }} productos encontrados</p>
				</div>
				<div class="col col-md-2">
					<p class="mb-0 mt-2 d-none d-md-block text-right">Usted está comprando:</p>
				</div>
				<div class="col-8 col-md-3">
					
						<ul class="nav nav-pills ml-md-5">
							<li class="nav-item">
								<a class="nav-link {{ Request::get('buytype') == 'major' ? 'active' : 'normal' }}" href="{{url('/categoria/'.$data->id.'?buytype=major')}}">Al mayor</a>
							</li>
							<li class="nav-item">
								<a class="nav-link {{ Request::get('buytype') != 'major' ? 'active' : 'normal' }}" href="{{url('/categoria/'.$data->id.'?buytype=minor')}}">Al menor</a>
							</li>
						</ul>
					
				
				</div>
			</div>
			
			
		</div>
	</div>
	<div class="row">
		
		<div class="col-lg-12">
			
	
			<div class="row">
				
				@foreach ($data->inventory as $producto)

				<div class="col-lg-3 col-12">
					@if(Request::get('buytype') != 'major')
					<!--LOGICA PARA CAMBIAR EL ICONO DE LOS BOTONES CUANDO YA ESTA EN EL CARRITO EL PRODUCTO-->
					@php
					if (isset($carrito)) {
						foreach ($carrito as $item) {
							if ($item->options->sale_type == "al-menor") {
								# code...
								$respuesta = Illuminate\Support\Arr::get($item, 'model.id', 0);
							}	
											
						}
					}
					@endphp

					<div class="card shadow-sm mb-4 w-100">
					
						<div class="card-body">
							
							<img style="height: 200px; object-fit: contain" data-src='/storage/{{ $producto->product['image'] }}' class="card-img-top">
							@if($producto->product['oferta'] == 1)
							<span class="badge badge-danger mb-2" style="font-size: 1.5em;">Oferta</span>
							@endif	
							<h5 class="card-title text-center font-weight-bold">{{ $producto->product_name }}</h5>
														
							<div class="text-center">
								<span class="font-weight-light">Subtotal: {{ number_format($producto->product['retail_total_price'] - $producto->product['retail_iva_amount'], 2, ',', '.') }} Bs</span>
								<br>
								<span class="font-weight-light small">iva: {{ number_format($producto->product['retail_iva_amount'], 2, ',', '.') }} Bs</span><br>
								<span class="font-weight-normal">Total:{{ number_format($producto->product['retail_total_price'], 2, ',', '.') }} Bs</span>
								<p class="text-right text-success">Dolares:{{ number_format($producto->product['retail_total_price'] / $dolar->price, 2, ',', '.')}}$</p>
							</div>
							<!--botones de comprar y listar-->
						
									<button 
										data-id="{{ $producto->product->id }}" 
										class="btn btn-block addToWishlist"
										data-producto="{{ $producto->product_name }}"
										data-precio="{{ $producto->product->retail_total_price }}"

										>
											<i class="fa fa-heart" style="color: #dc3545;"></i>
											<b class="text-danger">Lista de Deseos</b>
									</button>
										
								
								@if(isset($respuesta) && $respuesta != 0)
								
									<button
										type="button"
										class="btn btn-block btn-primary addCartBtn"
										data-id="{{ $producto->id }}"
										data-producto="{{ $producto->product_name }}"
										data-precio="{{ $producto->product->retail_total_price }}"
										data-type="al-menor"
										data-cantidad="1"
									>
										<i class="fas fa-check"></i>

									</button>
									
								
								
								@else
								
								<button
										type="button"
										class="btn btn-block btn-primary addCartBtn"
										data-id="{{ $producto->id }}"
										data-producto="{{ $producto->product_name }}"
										data-precio="{{ $producto->product['retail_total_price']}}"
										data-type="al-menor"
										data-cantidad="1"
								>
									<i class="fas fa-shopping-cart"></i>
									<b class="texto-carrito">Comprar</b>
								</button>
								
							
								@endif
	
						</div>
					</div>

					@else
					<!--LOGICA PARA CAMBIAR EL ICONO DE LOS BOTONES CUANDO YA ESTA EN EL CARRITO EL PRODUCTO-->
					@php
					if (isset($carrito)) {
						foreach ($carrito as $item) {
							if ($item->options->sale_type == "al-mayor") {
								# code...
								$respuesta = Illuminate\Support\Arr::get($item, 'model.id', 0);
							}	
											
						}
					}
					@endphp
					<div class="card shadow-sm mb-4 w-100">
						<div class="card-body">

							<img style="height: 200px; object-fit: contain" data-src='/storage/{{ $producto->product['image'] }}' class="card-img-top">
							@if($producto->product['oferta'] == 1)
							<span class="badge badge-danger mb-2" style="font-size: 1.5em;">Oferta</span>
							@endif
							<h5 class="card-title text-center font-weight-bold">{{ $producto->product_name }}</h5>
							<div class="text-center">
								<span class="font-weight-bold">Unidad: </span>{{ ucfirst($producto->unit_type) }}<br>
								<span class="font-weight-bold">Cantidad: </span>{{ $producto->qty_per_unit }} <br>
								<span class="font-weight-bold">Precio por unidad: </span>{{ number_format($producto->product['wholesale_total_individual_price'], 2, ',', '.') }} <br>
								<span class="font-weight-bold">Subtotal: </span>{{ number_format(($producto->product['wholesale_packet_price'] - $producto->product['wholesale_iva_amount']), 2, ',', '.') }} <br>
								<span class="font-weight-bold">Iva: </span>{{number_format($producto->product['wholesale_iva_amount'], 2, ',', '.')  }} <br>
								<p class="lead font-weight-normal">Total: {{ number_format($producto->product['wholesale_total_packet_price'], 2, ',', '.') }} Bs</p>
							</div>
							<p class="text-right text-success">Dolares:{{ number_format($producto->product->wholesale_total_packet_price / $dolar->price, 2, ',', '.')}}$</p>
							
							<!--BOTONES DE LISTA DE DESEOS Y COMPRAR-->

										<button data-id="{{ $producto->id }}" class="btn btn-block mb-2 addToWishlist">
											<i class="fa fa-heart" data-toggle="tooltip" data-title="Agregar a favoritos" style="color: #dc3545;"></i>
											<b class="d-block text-danger">Lista de deseos</b>
										</button>
								
									@if(isset($respuesta) && $respuesta != 0)
									
										<button
											type="button"
										class="btn btn-block btn-primary addCartBtn"
										data-id="{{ $producto->id }}"
										data-producto="{{ $producto->product_name }}"
										data-precio="{{ $producto->product->wholesale_total_packet_price }}"
										data-type="al-mayor"
										data-cantidad="1"
										>
											<i class="fas fa-check"></i>
										</button>
										
									
										
									@else
									
										<button
											type="button"
											class="btn btn-block btn-primary addCartBtn"
											data-id="{{ $producto->id }}"
											data-producto="{{ $producto->product_name }}"
											data-precio="{{ $producto->product['wholesale_total_packet_price'] }}"
											data-type="al-mayor"
											data-cantidad="1"
										>

											<i class="fas fa-shopping-cart mr-2" style="color: #007bff;"></i>
											<b>Comprar</b>
										</button>
								
									@endif
							
						</div>
					</div>
					@endif
				</div>

				@endforeach

			</div>
		</div>
	</div>
</div>





@endsection

@push('scripts')
<script>

	$(() => {

		// $('.rating').rating({
		// 	theme: 'krajee-fas',
		// 	emptyStar: '<i class="fas fa-star></i>',
		// 	filledStar: '<i class="fas fa-star></i>'
		// })

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
					toastr.success('<i>Producto añadido al carrito</i>')
					$('#cart_counter').removeClass('d-none')
					$('#cart_counter').text(res)
					$('#cart_counter-2').removeClass('d-none')
					$('#cart_counter-2').text(res)
				}

				that.removeAttr('disabled')
				that.html('<i class="fas fa-check"></i>')
				$('.texto-carrito').text("Producto agregado");
			})
			.fail((err) => {
				toastr.error('Ha ocurrido un error.')
				console.error(err)
			})
		});

	})

</script>
@endpush