@extends('layouts.customer')

@section('content')


<div class="loading" id="page_loader">
	<div>
		<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status">
		</div>
		<p>Espere un momento...</p>
	</div>
</div>


<div style="margin-top: 90px"></div>

<div class="container-fluid wrapper my-5">

	<div class="row">
		<div class="col-lg-3 col-12 mb-3">
			<div class="card">
				<div class="card-body p-0">
					<div class="list-group shadow-sm" id="list-tab" role="tablist">
						<a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">
							<i class="fas fa-user-cog icon-width"></i>Perfil
						</a>
						<a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">
							<i class="fas fa-road icon-width"></i>Dirección de entregas
						</a>
						<a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list" href="#list-messages" role="tab" aria-controls="messages">
							<i class="fas fa-cubes icon-width"></i>Pedidos
						</a>
						{{-- <a class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">
							<i class="fas fa-star icon-width"></i>Valoraciones
						</a> --}}
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-9 col-12 mb-3">

			<div class="card mb-4 shadow-sm">
				<div class="card-body row p-0 text-center">
					<div class="col-md-3 col-6 p-3">
						<h4>{{ $pedidosCount }}</h4>Pedidos
					</div>
					<div class="col-md-3 col-6 border-left p-3">
						<h4>{{ $wishlistCount }}</h4>Lista de deseos
					</div>
					<div class="col-md-3 col-6 border-left p-3">
						<h4>12</h4>Awaiting Deliveredvery
					</div>
					<div class="col-md-3 col-6 border-left p-3">
						<h4>29</h4>Delivered items
					</div>
				</div>
			</div>

			<div class="card mb-4 shadow-sm">
				<div class="card-body">
					<div class="tab-content" id="nav-tabContent">

						<div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">

							<div class="row mb-4">
								<div class="col d-flex justify-content-between">
									<h4 class="font-weight-light">Información personal</h4>
									<button class="btn btn-primary">
										<i class="fas fa-edit mr-2"></i> Editar
									</button>
								</div>
							</div>
							<hr>

							<div class="row mb-4">
								<div class="col-12">
									Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi maiores voluptates esse, excepturi, alias quod tempore, est nostrum ea a sint voluptas in corporis, qui voluptatum culpa dolorum minus unde.
								</div>
							</div>

							<div class="row mb-2">
								<div class="col-lg-4 col-12 mb-3">
									<label for="dni">Cédula</label>
									<input type="number" name="dni" value="{{ $user->people->dni }}" id="dni" class="form-control" required>
								</div>
								<div class="col-lg-4 col-12 mb-3">
									<label for="name">Nombre</label>
									<input type="text" name="name" value="{{ $user->people->name }}" id="name" class="form-control" required>
								</div>
								<div class="col-lg-4 col-12 mb-3">
									<label for="apellido">Apellido</label>
									<input type="text" name="apellido" value="{{ $user->people->lastname }}" id="apellido" class="form-control" required>
								</div>
							</div>

							<div class="row mb-2">
								<div class="col-lg-6 col-12 mb-3">
									<label for="email">Correo electrónico</label>
									<input type="email" name="email" value="{{ $user->email }}" id="email" class="form-control" required>
								</div>
								<div class="col-lg-6 col-12 mb-3">
									<label for="phone">Teléfono</label>
									<input type="text" name="phone" id="phone" value="{{ $user->people->phone_number }}" class="form-control" required>
								</div>
							</div>

							<div class="row mb-4">
								<div class="col-lg-6 col-12 mb-3">
									<label for="password">Contraseña</label>
									<input type="password" name="password" id="password" class="form-control">
								</div>
								<div class="col-lg-6 col-12 mb-3">
									<label for="password-repeat">Repita Contraseña</label>
									<input type="password" name="password-repeat" id="password-repeat" class="form-control">
								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
							<div class="d-flex justify-content-between mb-4 align-items-center">
								<h4 class="font-weight-light">Direcciones de entrega</h4>
								<button class="btn btn-primary" data-toggle="modal" data-target="#nuevaDireccion">
									<i class="fas fa-plus mr-2"></i>Nueva
								</button>
							</div>
							<div class="table-responsive">
								<table class="table text-center table-sm table-hover table-bordered">
									<thead>
										<tr>
											<th>NUM</th>
											<th>SECTOR</th>
											<th>DETALLES</th>
											<th>TARIFA (Bs)</th>
											<th colspan="2">ACCIONES</th>
										</tr>
									</thead>
									<tbody>
										@forelse($rates as $rate)
											<tr>
												<td>{{ $loop->iteration }}</td>
												<td>{{ $rate->travel_rate->sector->sector }}</td>
												<td>{{ $rate->details }}</td>
												<td>{{ number_format($rate->travel_rate->rate, 0, ',', '.') }}</td>
												<td>
													<button data-id="{{ $rate->id }}" class="btn btn-warning address_edit"><i class="fas fa-edit"></i></button>
												</td>
												<td>
													<button data-id="{{ $rate->id }}" class="btn btn-danger address_delete"><i class="fas fa-trash"></i></button>
												</td>
											</tr>
										@empty
											<tr>
												<td colspan="5" class="text-center">No hay datos registrados.</td>
											</tr>
										@endforelse
									</tbody>
								</table>
							</div>
						</div>

						<div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">

							
							<div class="row mb-4">
								<div class="col d-flex justify-content-between">
									<h4 class="font-weight-light">Compras realizadas</h4>
								</div>
							</div>

							<div class="table-responsive">
								<table class="table text-center table-bordered table-sm table-hover">
									<thead>
										<th>ID COMPRA</th>
										<th>PRODUCTOS</th>
										<th>MONTO (Bs)</th>
										<th>DELIVERY</th>
										<th>ACCIONES</th>
									</thead>
									<tbody>
										@forelse($pedidos as $compra)
											<tr>
												<td>{{ $compra->code }}</td>
												<td>{{ count($compra->details) }}</td>
												<td>{{ $compra->amount }}</td>
												<td>{{ ucfirst($compra->delivery) }}</td>
												<td>
													<button data-toggle="modal" data-id="{{ $compra->id }}" data-target="#detalles" class="btn btn-primary detalle"><i class="fas fa-info"></i></button>
												</td>
											</tr>
										@empty
											<tr>
												<td colspan="5">No hay información para mostrar.</td>
											</tr>
										@endforelse
									</tbody>
								</table>
							</div>

						</div>

						{{-- <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">

						</div> --}}
					</div>
				</div>
			</div>
		</div>
	</div>

</div>


<!-- Modal -->
<div class="modal fade" id="nuevaDireccion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Nueva dirección para la entrega</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/direcciones" method="post">
				@csrf

				<div class="modal-body">

					<div class="row mb-3">
						<div class="col-12 mb-3 col-lg-4">
							<label for="travelRate">Selecciona un sector</label>
							<select data-live-search="true" id="travelRate" name="travel_id" class="form-control border selectpicker" required>
								<option disabled selected>Selecciona un sector</option>
								@foreach ($sectors as $sector)
									<option data-subtext="{{ $sector->sector->city->city }}" value="{{ $sector->id }}">{{ $sector->sector->sector }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-12 col-lg-4 mb-3">
							<label>Tiempo estimado (min)</label>
							<input type="text" readonly class="form-control" id="stimated_time">
						</div>
						<div class="col-12 col-lg-4">
							<label>Tarifa (Bs)</label>
							<input type="text" readonly class="form-control" id="rate">
						</div>
					</div>

					<div class="row">
						<div class="col">
							<label for="details">Detalles de la dirección</label>
							<textarea name="details" id="details" rows="2" class="form-control" placeholder="Al frente del edificio López en la casa de rejas blancas #12 ..." required></textarea>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
					<button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Guardar</button>
				</div>
			</form>
		</div>
	</div>
</div>

{{-- INFORMACION DE LA CO;PRA --}}
<div class="modal fade" id="detalles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Información de la compra</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal_loader py-5" id="modal_loader">
				<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status"></div>
			</div>

			<div class="modal-body">
				
				<div class="row mb-2">
					<div class="col col-sm-4">
						<span class="font-weight-bold">Factura</span>
					</div>
					<div class="col col-sm-8">
						<span id="factura_id"></span>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col col-sm-4">
						<span class="font-weight-bold">Cliente</span>
					</div>
					<div class="col col-sm-8">
						<span id="cliente"></span>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col col-sm-4">
						<span class="font-weight-bold">Monto</span>
					</div>
					<div class="col col-sm-8">
						<span id="monto"></span>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col col-sm-4">
						<span class="font-weight-bold">Productos</span>
					</div>
					<div class="col col-sm-8">
						<span id="productos"></span>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col col-sm-4">
						<span class="font-weight-bold">Fecha</span>
					</div>
					<div class="col col-sm-8">
						<span id="fecha"></span>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
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

		// Mostrar notificaciones
		@if (session('success'))
			toastr.success("<?php echo session('success') ?>")
		@endif

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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

		// saber si hay elementos en la lista de deseos
		$.get('/get_wishlist', (res) => {
			if (res > 0) {
				$('#wishlist_counter').removeClass('d-none')
				$('#wishlist_counter').text(res)
			}
		})
		.catch((err) => {
			tostr.error('Ha ocurrido un error.')
			console.error(err)
		})


		$('#travelRate').change((ev) => {
			let id = $(ev.target).val()

			$.get(`/travel_rates/${id}`, (res) => {

				$('#stimated_time').val(res.rate.stimated_time)
				$('#rate').val(new Intl.NumberFormat().format(res.rate.rate))

			})
			.catch((err) => {
				toastr.error('Algo ha salido mal.')
				console.error(err)
			})
		})

		$('.travelRateModalEditar').click(function() {
			let id = $(this).data('id')
			$('#editForm').attr('action', `/travel_rates/${id}`)

			$.get(`/travel_rates/${id}`, (res) => {
				$('#sectoredithd').val(res.sector.id)
				$('#sectoredit').val(res.sector.sector)
				$('#tarifaedit').val(res.rate.rate)
				$('#stimated_timeedit').val(res.rate.stimated_time)
			})
			.catch((err) => {
				toastr.error('Ha ocurrido un error.')
				console.error(err)
			})
		})

		$('.travelRateModalBorrar').click(function() {
			let id = $(this).data('id')

			$('#travelFormDel').attr('action', `/travel_rates/${id}`)

		})


		$('.detalle').click(function() {
			
			let data = $(this).data()
			
			$.post({
				url: '/get_sale',
				data,
				beforeSend(){
					$('#modal_loader').show()
				}
			})
			.done((res) => {
				console.log(res)

				$('#factura_id').text(res.code)
				$('#cliente').text(`${res.user.people.name} ${res.user.people.lastname}`)
				$('#monto').text(`${res.amount} Bs`)
				
				$('#productos').text('')
				for(let producto of res.details){
					$('#productos').append(`
						<p>${producto.product}</p>
					`)
				}
				
				$('#fecha').text(res.created_at)

				$('#modal_loader').fadeOut()
			})
			.fail((err) => {
				console.error(err)
				toastr.error('Algo ha fallado.')
			})
		})
	})

</script>
@endpush