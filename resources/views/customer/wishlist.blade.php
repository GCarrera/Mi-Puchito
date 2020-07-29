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
							<li class="list-group-item itempadre">
								<div class="row">

									<div class="col-md-4 col-sm-6 col-12">
										<p class="text-muted small">PRODUCTO</p>
										<div class="d-flex justify-content-start">
											<img src="/storage/{{ $producto->inventory->product->image }}" style="height: 70px;" class="mr-2">
											<p>
												<small class="font-weight-bold">{{ $producto->inventory->product_name }}</small><br>
												<small>{{ $producto->inventory->description }}</small>
											</p>
										</div>
									</div>

									<div class="col-md-2 col-sm-6 col-12 padreprecio">
										<p class="text-muted small">PRECIO MENOR Bs</p>
										<span class="font-weight-bold precio">{{ number_format($producto->inventory->product->retail_total_price, 2, ',', '.') }}</span>
									</div>
									<div class="col-md-2 col-sm-6 col-12 padreprecio">
										<p class="text-muted small">PRECIO MAYOR Bs</p>
										<span class="font-weight-bold precio">{{ number_format($producto->inventory->product->wholesale_total_packet_price, 2, ',', '.') }}</span><br>
										<span class="text-muted small">
											<span class="preciopvp">{{ number_format($producto->inventory->product->wholesale_total_individual_price, 2, ',', '.') }}</span> c/u
										</span><br>
										<span class="text-muted small">
											1 {{ $producto->inventory->unit_type }} de {{ $producto->inventory->qty_per_unit }} productos
										</span>
									</div>

									{{-- <div class="col-md-2 col-sm-6 col-12">
										<p class="text-muted small">CALIFICACION</p>
										<input name="input-2" value="2.4" class="star-rating kv-ltr-theme-fas-star rating-loading" data-size="xs">
									</div> --}}

									<div class="col-md-4 col-sm-6 col-12 d-flex justify-content-end">
										<div class="mt-4">
											<button data-id="{{ $producto->inventory->id }}" data-target="#del_wl_item" data-toggle="modal" class="btn btn-outline-danger mr-2 eliminar">
												<i class="fas fa-times mr-2"></i>Eliminar
											</button>
											<button
												type="button"
												class="btn btn-primary addCartBtn"
												data-id="{{ $producto->inventory->id }}"
												data-producto="{{ $producto->inventory->product_name }}"
												data-precio="{{ $producto->inventory->product->retail_total_price }}"
												data-type="al-menor"
												data-cantidad="1">

												<i class="fas fa-shopping-cart mr-2"></i><span class="text">Comprar</span>
											</button>
										</div>
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

				<h4 class="text-center font-weight-light my-5">¿Estás seguro de querer sacar el producto de la lista de deseos?</h4>
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