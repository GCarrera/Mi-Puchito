@extends('layouts.customer')

@section('content')

<div id="loading" class="loading">
	<div>
		<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status">
		</div>
		<p>Espere un momento...</p>
	</div>
</div>


<div style="margin-top: 90px"></div>

<div class="container-fluid wrapper my-5">
	<div class="row">
{{-- 		<div class="col-lg-3 col-12">
			<div class="card shadow-sm mb-4">
				<div class="card-header">
					<h5>Filtrado de productos</h5>
				</div>
				<div class="card-body">

				</div>
			</div>
		</div> --}}

		<div class="col-12">

			<div class="card shadow-sm mb-4">
				<div class="card-body">

					<ul class="list-group">
						
						@forelse ($products as $producto)
						<!--LOGICA PARA CAMBIAR EL ICONO DE LOS BOTONES CUANDO YA ESTA EN EL CARRITO EL PRODUCTO-->
						@php
						if (isset($carrito)) {
							foreach ($carrito as $item) {
								if ($item->options->sale_type == "al-menor") {
									# code...
									$respuesta1 = Illuminate\Support\Arr::get($item, 'model.id', 0);
								}	
												
							}
							foreach ($carrito as $item) {
								if ($item->options->sale_type == "al-mayor") {
									# code...
									$respuesta2 = Illuminate\Support\Arr::get($item, 'model.id', 0);
								}	
												
							}
						}
						@endphp
							<li class="list-group-item itempadre">
								<div class="row">

									<div class="col-md-2 col-sm-6 col-12 mt-3">
										
										<div class="">
											
											<img data-src="/storage/{{ $producto->product->image }}" style="height: 80px; position: relative; top: 0px; left: 15px;">
											@if(isset($respuesta1) && $respuesta1 != 0)
											<div class="alert alert-success" role="alert" class="font-weight-bold" style="font-size: 0.9em; margin-bottom: 0px;">
												<b>su producto a sido agreado al menor</b>
											</div>
											@endif
											@if(isset($respuesta2) && $respuesta2 != 0)
											<div class="alert alert-success" role="alert" class="" style="font-size: 0.9em; margin-bottom: 0px;">
												<b>su producto a sido agreado al mayor</b>
											</div>
											@endif
										</div>
											
										
									</div>

									<div class="col-md-2 col-sm-6 col-12 mt-3">
										
										<p class="text-muted small">PRODUCTO</p>
											<p class="">
												<small class="font-weight-bold">{{ $producto->product->inventory->product_name }}</small><br>
												<small>{{ $producto->product->inventory->description }}</small>
											</p>
										
									</div>

									<div class="col-md-2 col-sm-6 col-12 mt-3 padreprecio">
										<p class="text-muted small">PRECIO MENOR Bs</p>
										
										<span class="font-weight-bold precio">{{ number_format($producto->product->retail_total_price, 2, ',', '.') }}</span><br>
										<span class="text-muted"><small><span class="font-weight-bold">Iva:</span> {{ number_format($producto->product->retail_iva_amount, 2, ',', '.') }}</small></span><br>
										<span class="text-muted"><small><span class="font-weight-bold">Subtotal:</span> {{ number_format(($producto->product->retail_total_price-$producto->product->retail_iva_amount), 2, ',', '.') }}</small></span>
										<span class="text-success"><small><span class="">Dolares:</span> {{ number_format($producto->product->retail_total_price / $dolar->price, 2, ',', '.') }}$</small></span>
									</div>
									<div class="col-md-2 col-sm-6 col-12 mt-3 padreprecio">
										<p class="text-muted small">PRECIO MAYOR Bs</p>
										<span class="font-weight-bold precio">{{ number_format($producto->product->wholesale_total_packet_price + ($producto->product->wholesale_iva_amount * $producto->product->inventory->qty_per_unit), 2, ',', '.') }}</span><br>
										<!--
										<span class="text-muted small">
											<span class="preciopvp">Precio unidad: {{ number_format($producto->product->wholesale_total_individual_price, 2, ',', '.') }}</span>
										</span><br>
										-->
										<!--
										<span class="text-muted small">
											1 {{ $producto->inventory->unit_type }} de {{ $producto->product->inventory->qty_per_unit }} productos
										</span>
										-->
										<br>
										<span  class="text-muted small">
											<span class="font-weight-bold">Iva:</span> {{ number_format($producto->product->wholesale_iva_amount * $producto->product->inventory->qty_per_unit , 2, ',', '.') }}
										</span>
										<br>
										<span  class="text-muted small">
											<span class="font-weight-bold">Subtotal:</span> {{ number_format($producto->product->wholesale_total_packet_price , 2, ',', '.') }}
										</span>
										<p class="small text-success">Dolares:{{ number_format($producto->product->wholesale_total_packet_price / $dolar->price, 2, ',', '.')}}$</p>
									</div>

									{{-- <div class="col-md-2 col-sm-6 col-12">
										<p class="text-muted small">CALIFICACION</p>
										<input name="input-2" value="2.4" class="star-rating kv-ltr-theme-fas-star rating-loading" data-size="xs">
									</div> --}}

									<div class="col-md-4 col-sm-6 col-12">
										<!--
										<div class="my-4">
											@if(isset($respuesta1) && $respuesta1 != 0)
											<button
												type="button"
												class="btn btn-success addCartBtn mx-2"
												data-id="{{ $producto->id }}"
												data-producto="{{ $producto->product_name }}"
												data-precio="{{ $producto->product->retail_total_price }}"
												data-type="al-menor"
												data-cantidad="1"
												style="max-width: 40%;">
												<i class="fas fa-check mr-2"></i><span class="text">al menor agregado</span>
											</button>
											@else
											<button
												type="button"
												class="btn btn-primary addCartBtn mx-2"
												data-id="{{ $producto->id }}"
												data-producto="{{ $producto->product_name }}"
												data-precio="{{ $producto->product->retail_total_price }}"
												data-type="al-menor"
												data-cantidad="1">

												<i class="fas fa-shopping-cart mr-2"></i><span class="text">al menor</span>
											</button>
											@endif
											@if(isset($respuesta2) && $respuesta2 != 0)
											<button
												type="button"
												class="btn btn-success addCartBtn mx-2"
												data-id="{{ $producto->id }}"
												data-producto="{{ $producto->product_name }}"
												data-precio="{{ $producto->product->wholesale_total_packet_price }}"
												data-type="al-mayor"
												data-cantidad="1"
												style="max-width: 40%;">
												<i class="fas fa-check mr-2"></i><span class="text">al mayor agregado</span>
											</button>
											@else
											<button
												type="button"
												class="btn btn-primary addCartBtn mx-2"
												data-id="{{ $producto->id }}"
												data-producto="{{ $producto->product_name }}"
												data-precio="{{ $producto->product->wholesale_total_packet_price }}"
												data-type="al-mayor"
												data-cantidad="1">

												<i class="fas fa-shopping-cart mr-2"></i><span class="text">al mayor</span>
											</button>
											@endif
											<button data-id="{{ $producto->id }}" data-target="#del_wl_item" data-toggle="modal" class="btn btn-outline-danger btn-block eliminar mt-3">
												<i class="fas fa-times mr-2"></i>Eliminar
											</button>
										</div>
										-->
									</div>
								</div>
							</li>

						@empty

							<div class="card shadow-sm mb-4">
								<div class="card-body py-5">
									<h4 class="text-center">
										<i class="fas fa-heart fa-2x"></i><br>
										<p class="my-4">No hay productos en tu lista.</p>
										<a href="/home" class="btn btn-primary"><i class="fas fa-shopping-cart mr-2"></i>Buscar productos</a>
									</h4>
								</div>
							</div>

						@endforelse

					</ul>

				</div>
			</div>
		</div>
	</div>
</div>


{{-- MODAL PAR ELIMINAR PRODUCTO  --}}
<div class="modal fade" id="del_wl_item" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Eliminar producto</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<input type="hidden" id="productohidden">

				<p class="text-center font-weight-light">¿Estás seguro de querer sacar el producto de la lista de deseos?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
				<button type="button" id="eliminar" data-dismiss="modal" class="btn btn-primary"><i class="fas fa-check mr-2"></i>Sí, estoy seguro</button>
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>

	$(() => {
		toastr.options = {
			"closeButton": true,
			"progressBar": true,
			"positionClass": "toast-bottom-left",
		}
		
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
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

			$('#loading').fadeOut()
		})
		.catch((err) => {
			toastr.error('Ha ocurrido un error.')
			console.error(err)
		})

		// saber si hay elementos en la lista de deseos
		$.get('/get_wishlist', (res) => {
			if (res > 0) {
				$('#wishlist_counter').removeClass('d-none')
				$('#wishlist_counter').text(res)
			}
		})
		.catch((err) => {
			toastr.error('Ha ocurrido un error.')
			console.error(err)
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
			}).done((res) => {
				if (res == 'rejected') {
					toastr.info('Este producto ya está agregado con un tipo de compra distinto.')
				} else {
					$('#cart_counter').removeClass('d-none')
					$('#cart_counter').text(res)
					toastr.success('Producto agregado satisfactoriamente.')
				}

				that.removeAttr('disabled')
				that.html('<i class="fas fa-shopping-cart mr-2"></i><span class="text">Comprar</span>')
			}).fail((err) => {
				toastr.error('Ha ocurrido un error.')
				console.error(err)
			})
		});


		$('.eliminar').click(function(){
			let id = $(this).data('id')
			// $('#productohidden').val(id)
			let producto = $(this).parents('.itempadre')

			$('#eliminar').click(() => {

				// let id = $('#productohidden').val()

				$.ajax(`/lista-de-deseos/${id}`, { method: 'delete' })
				.done((res) => {

					$('#wishlist_counter').text(res)
					producto.remove()

					if ($('.itempadre').length < 1) {
						$('.list-group').html(`
							<h5 class="text-center my-5">
								<i class="fas fa-2x fa-heart mb-3"></i><br>
								No hay productos en tu lista.
							</h5>
						`)
					}

					toastr.success('Producto eliminado correctamente.')

				})
				.catch((err) => {
					toastr.error('Ha ocurrido un error.')
					console.error(err)
				})
			})
		})

	})

</script>
@endpush