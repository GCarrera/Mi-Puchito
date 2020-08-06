@extends('layouts.customer')

@section('content')


<div id="page_loader" class="loading">
	<div>
		<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status">
		</div>
		<p>Espere un momento...</p>
	</div>
</div>

<img src="/img/banner1.png" style="height: 470px; margin-top: 4%;" class="d-block w-100" alt="...">

<div class="container-fluid my-5 animated wrapper">

	<div class="card mb-4 shadow-sm">
		<div class="card-body d-flex justify-content-between">
			<h5>{{ $cat }}</h5>
			<p>{{ count($data) }} productos encontrados</p>
		</div>
	</div>

	<div class="row">
		
		@foreach ($data as $producto)

		<div class="col-lg-3 col-12">
			<div class="card shadow-sm mb-4 w-100">

				<div class="card-body">
					
					<img style="height: 200px; object-fit: contain" src='/storage/{{ $producto->image }}' class="card-img-top">
						
					<h5 class="card-title text-center font-weight-bold">{{ $producto->inventory->product_name }}</h5>
					<p class="font-weight-light text-center">{{ $producto->inventory->description }}</p>
							
							
					<div class="text-center">
						<span class="font-weight-light">Precio: {{ number_format($producto->retail_total_price - $producto->retail_iva_amount, 2, ',', '.') }} Bs</span>
						<br>
						<span class="font-weight-light">I.V.A: {{ number_format($producto->retail_iva_amount, 2, ',', '.') }} Bs</span><br>
						<span class="lead font-weight-bold"><small>Total: </small>{{ number_format($producto->retail_total_price, 2, ',', '.') }} Bs</span>
					</div>
					<div>
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
					</div>	
					</div>
				</div>

		</div>

		@endforeach

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

	})

</script>
@endpush