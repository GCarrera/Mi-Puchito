@extends('layouts.admin')

@section('content')

<div class="loading" id="loading">
	<div>
		<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status">
		</div>
		<p>Espere un momento...</p>
	</div>
</div>

<div class="container-fluid animated wrapper" style="margin-top: 90px">

	<button class="btn btn-primary btn-lg rounded-circle" data-target="#tarifaModal" data-toggle="modal" style="position: fixed; bottom: 30px; right: 30px">
		<i class="fas fa-plus"></i>
	</button>


	{{-- <div class="row mb-5">
		<div class="col mb-3">
			<div class="card bg-primary text-white shadow-sm">
				<div class="card-body d-flex justify-content-between">
					<h5>{{ $productosCount }} Productos</h5>
					<i class="fas fa-chart-line fa-2x"></i>
				</div>
			</div>
		</div>
		<div class="col mb-3">
			<div class="card bg-primary text-white shadow-sm">
				<div class="card-body d-flex justify-content-between">
					<h5>{{ $empresasCount }} Empresas</h5>
					<i class="fas fa-building fa-2x"></i>
				</div>
			</div>
		</div>
		<div class="col mb-3">
			<div class="card bg-primary text-white shadow-sm">
				<div class="card-body d-flex justify-content-between">
					<h5>{{ $categoriasCount	 }} Categorias</h5>
					<i class="fas fa-clipboard-list fa-2x"></i>
				</div>
			</div>
		</div>
		<div class="col mb-3">
			<div class="card bg-primary text-white shadow-sm">
				<div class="card-body d-flex justify-content-between">
					<h5>{{ $salesCount }} Ventas</h5>
					<i class="fas fa-cash-register fa-2x"></i>
				</div>
			</div>
		</div>
	</div> --}}

	<div class="row mb-5">
		<div class="col">
			<div class="card shadow-sm">
				<div class="card-header d-flex justify-content-between mb-3">
					<h4>Delivery</h4>
				</div>
				<div class="card-body">
					<div class="table responsive">
						<table class="table table-sm table-hover table-bordered text-center">
							<thead>
								<tr>
									<th>CIUDAD</th>
									<th>SECTOR</th>
									<th>TIEMPO ESTIMADO</th>
									<th>TARIFA (Bs)</th>
									<th>ACCIONES</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($rates as $rate)
									<tr>
										<td>{{ $rate->sector->city->city }}</td>
										<td>{{ $rate->sector->sector }}</td>
										<td>{{ $rate->stimated_time }}</td>
										<td>{{ number_format($rate->rate, 0, ',', '.') }}</td>
										<td>
											<button data-toggle="modal" data-target="#tarifaModalEditar" data-id="{{ $rate->id }}" class="btn btn-warning tarifaModalEditar mr-2"><i class="fas fa-edit"></i></button>
											<button data-toggle="modal" data-id="{{ $rate->id }}" data-target="#tarifaModalBorrar" class="btn btn-danger tarifaModalBorrar"><i class="fas fa-trash"></i></button>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



{{-- Modal ver info de producto marcdo --}}
<!-- Modal -->
<div class="modal fade" id="tarifaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tarifas por sectores</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/travel_rates" method="post">
				@csrf

				<div class="modal-body">

					<div class="row mb-3">
						<div class="col-12 mb-3 col-lg-6">
							<label for="estado">Selecciona un estado</label>
							<select data-live-search="true" id="estado" class="form-control border selectpicker" required>
								<option disabled selected>Selecciona un estado</option>
								@foreach ($estados as $estado)
									<option value="{{ $estado->id }}">{{ $estado->state }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-12 col-lg-6 mb-3">
							<label for="ciudad">Selecciona una ciudad</label>
							<select disabled id="city" class="border rounded-lg custom-select" required>
								<option disabled selected>Selecciona una ciudad</option>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="col-12 col-lg-6 mb-3">
							<label for="sector">Selecciona un Sector</label>
							<select disabled name="sector" id="sector" class="rounded-lg border custom-select" required>
								<option disabled selected>Selecciona un sector</option>
							</select>
						</div>
						<div class="col-12 col-lg-6 mb-3">
							<label for="tarifa">Tarifa (Bs)</label>
							<input type="text" id="tarifa" class="form-control" name="tarifa" required>
						</div>
					</div>

					<div class="row">
						<div class="col-12 col-lg-6 mb-3">
							<label for="stimated_time">Tiempo estimado de llegada (min)</label>
							<input type="text" id="stimated_time" class="form-control" name="stimated_time" placeholder="Ejm: 30:00" pattern="^[0-9]{2}:[0-9]{2}$" required>
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



<div class="modal fade" id="tarifaModalEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tarifas por sectores</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="editForm" method="post">
				@method('put')
				@csrf

				<input type="hidden" name="sector" id="sectoredithd">

				<div class="modal-body">

					<div class="row">
						<div class="col-12 col-lg-6 mb-3">
							<label for="sector">Selecciona un Sector</label>
							<input type="text" class="form-control" disabled  id="sectoredit" required>
						</div>
						<div class="col-12 col-lg-6">
							<label for="tarifa">Tarifa (Bs)</label>
							<input type="text" id="tarifaedit" class="form-control" placeholder="Sin separadores decimales" name="tarifa" required>
						</div>

						<div class="col-12 col-lg-6">
							<label for="stimated_timeedit">Tiempo estimado de llegada</label>
							<input type="text" id="stimated_timeedit" class="form-control" name="stimated_timeedit" placeholder="Ejm: 30:00" pattern="^[0-9]{2}:[0-9]{2}$" required>
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

<div class="modal fade" id="tarifaModalBorrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Eliminar tarifa seleccionada</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="travelFormDel" method="post">
				@csrf
				@method('delete')
				<div class="modal-body">

					<h5 class="text-center my-5">¿Estás seguro de querer borrarla?</h5>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
					<button type="submit" class="btn btn-primary"><i class="fas fa-check mr-2"></i>Sí, lo estoy</button>
				</div>
			</form>
		</div>
	</div>
</div>


@endsection

@push('scripts')
<script>
	$(() => {

		$('#loading').fadeOut()

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

		$('#estado').change((ev) => {
			let estado = $(ev.target).val()

			$.get(`/traer_ciudad/${estado}`, (res) => {

				$('#city').removeAttr('disabled')

				$('#city').html('<option disabled selected>Selecciona la ciudad</option>')
				for (let city of res) {
					$('#city').append(`<option value="${city.id}">${city.city}</option>`)
					console.log(`<option value="${city.id}">${city.city}</option>`)
				}
			})
			.catch((err) => {
				toastr.error('Algo ha salido mal.')
				console.error(err)
			})
		})

		$('#city').change((ev) => {
			let ciudad = $(ev.target).val()

			$.get(`/traer_sectores/${ciudad}`, (res) => {

				$('#sector').removeAttr('disabled')

				$('#sector').html('<option disabled selected>Selecciona el sector</option>')
				for (let sector of res) {
					$('#sector').append(`<option value="${sector.id}">${sector.sector}</option>`)
					console.log(`<option value="${sector.id}">${sector.sector}</option>`)
				}
			})
			.catch((err) => {
				toastr.error('Algo ha salido mal.')
				console.error(err)
			})
		})

		$('.tarifaModalEditar').click(function() {
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

		$('.tarifaModalBorrar').click(function() {
			let id = $(this).data('id')

			$('#travelFormDel').attr('action', `/travel_rates/${id}`)

		})

	})
</script>
@endpush