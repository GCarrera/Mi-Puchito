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
							<form action="editar_perfil" method="GET">
								<div class="row mb-4">
									<div class="col d-flex justify-content-between">
										<h4 class="font-weight-light">Información personal</h4>
										<h5></h5>
									</div>
								</div>
								<hr>
								<div class="row mb-2">
									<div class="col-lg-4 col-12 mb-3">
										<label for="dni">Cédula</label>
										<input type="hidden" name="id" value="{{ $user->id }}" readonly>
										<input type="number" readonly name="dni" value="{{ $user->people->dni }}" id="dni" class="form-control" required>
									</div>
									<div class="col-lg-8 col-12 mb-3">
										<label for="name">Nombre Completo</label>
										<input type="text" name="name" value="{{ $user->people->name }}" id="name" class="form-control" required>
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
										<input type="password" autocomplete="off" name="password" id="password" class="form-control">
									</div>
									<div class="col-lg-6 col-12 mb-3">
										<label for="password_confirmation">Confirmación de Contraseña</label>
										<input type="password" autocomplete="off" name="password_confirmation" id="password_confirmation" class="form-control">
									</div>
									<div class="col-lg-12 col-12 text-center">
										<button type="submit" class="btn btn-primary">
											<i class="fas fa-edit mr-2"></i> Guardar
										</button>
									</div>
								</div>
							</form>
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
											<th>N°</th>
											<th>Direccion</th>
											<th >ACCIONES</th>
										</tr>
									</thead>
									<tbody>
										@forelse($rates as $rate)
											<tr>
												<td>{{ $loop->iteration }}</td>
												<td>{{ $rate->travel_rate_id ? $rate->travel_rate->sector->sector : "" }} 
												{{ $rate->details }}
												{{ $rate->travel_rate_id ?$rate->travel_rate->rate : "" }}</td>
												<td>
													<button data-id="{{ $rate->id }}" class="btn btn-warning address_edit"><i class="fas fa-edit"></i></button>
											
													<button data-id="{{ $rate->id }}" class="btn btn-danger address_delete"><i class="fas fa-trash"></i></button>
												</td>
											</tr>
										@empty
											<tr>
												<td colspan="3" class="text-center">No hay datos registrados.</td>
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
										<th>CONFIRMADO</th>
										<th>Estimado</th>
										<th>ACCIONES</th>
									</thead>
									<tbody>
										@forelse($pedidos as $compra)
											<tr>
												<td>{{ $compra->code }}</td>
												<td>{{ count($compra->details) }}</td>
												<td>{{ $compra->amount }}</td>
												<td>{{ ucfirst($compra->delivery) }}</td>
												@if($compra->dispatched)
												<td>{{$compra->dispatched}}</td>
												@else
												<td>No</td>
												@endif
												<td>
													<!--
													<button data-toggle="modal" data-id="{{ $compra->id }}" data-target="#detalles" class="btn btn-primary detalle"><i class="fas fa-info"></i></button>
													-->
													<a class="btn btn-danger" target="_blank" href="{{route('factura.pdf', ['id' => $compra->id])}}"><i class="fas fa-file-alt" style="color: #ffffff"></i></a>

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

				<div class="row">
						{{-- <div class="col-12">
							<label for="state_id">Estado</label><br>
							<select required name="state_id" data-live-search="true" class="selectpicker mb-2 border form-control" data-width="100%" id="state_id"></select>
							@foreach ($collection as $item)
								
							@endforeach
						</div> --}}
						
					<div class="col-12">
						<label for="forma_delivery">Seleccione una forma de colocar la direccion para el delivery</label><br>
						<select name="forma_delivery" class="selectpicker mb-2 border form-control" id="forma-delivery">
							<option value="" id="select-option-forma">Seleccione una forma</option>
							<option value="1">Escribir la direccion</option>
							<option value="2">Buscar mi direccion</option>
						</select>
					</div>

					<div id="direc-descrip-caja" class="w-100">
						<div class="col-12">
							<textarea name="direc_descrip_area" id="direc-descrip-area" cols="30" rows="5" class="form-control" maxlength="255" ></textarea>
							</div>
					</div>

					<div id="select-multiples" class="w-100">
						<div class="col-12">
							<label for="city_id">Ciudad</label><br>
							<select required name="city_id" data-live-search="true" class="selectpicker mb-2 border form-control" data-width="100%" id="city_id">
								<option value="0" selected>Seleccione Ciudad</option>
							@foreach ($cities as $city)
								<option value="{{$city->id}}">{{$city->city}}</option>
							@endforeach
							</select>
						</div>
						<div class="col-12">
							<label for="sector_id">Sector</label><br>
							<select required name="sector_id" data-live-search="true" class="selectpicker mb-2 border form-control" data-width="100%" id="sector_id">
								<option value="0">Seleccione Sector</option>
							</select>
						</div>
						<div class="col-12">
							<label for="detalles">Dirección exacta</label><br>
							<input type="text" class="form-control" name="detalles" id="detalles" placeholder="Calle, Número de Casa, Algún punto de referencia.">
						</div>
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
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		@if (session('pedidos'))
			$('#list-messages-list').trigger('click')
		@endif()
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

		@if ($errors->any())
			@foreach ($errors->all() as $error)
				toastr.error("{{ $error }}")
            @endforeach
		@endif()

		@if (session('success'))
			toastr.success("{{ session('success') }}")
		@endif()
	})

	//MODAL DE DIRECCION

	$('#select-multiples').hide();
	$('#direc-descrip-caja').hide();

	$('#forma-delivery').on('change', (e) => {

			$('#select-multiples').hide();

			$('#direc-descrip-caja').hide();

			$('#caja-direc-anteriores').hide();

			if ($('#forma-delivery').val() == 2) {

				$('#select-multiples').show();
				$('#caja-direc-anteriores').hide();
			}else if($('#forma-delivery').val() == 1){

				$('#direc-descrip-caja').show();
				$('#caja-direc-anteriores').hide();
			}else if($('#forma-delivery').val() == ""){

				$('#caja-direc-anteriores').show();
			}
			console.log($('#forma-delivery').val());
		})

	//CONSULTA DE LOS SECTORES DE LA CIUDAD SELECCIONADA
	$('#city_id').on('change', function() { 
			var city_id = $('#city_id').val(); 
			$.ajax({
				url: "sector/"+city_id,
				type: "GET",
				dataType: "json",
				error: function(error){
				//console.log(error);
				}, 
				success: function(respuesta){
					console.log(respuesta)
					$("#sector_id").html('<option value="" selected="true"> Seleccione una opción </option>');
					respuesta.forEach(element => {
						console.log(element)
						$('#sector_id').append('<option value='+element.id+'> '+element.sector+' </option>')
					});
					$('.selectpicker').selectpicker('refresh');
				}
			});

		})

</script>
@endpush