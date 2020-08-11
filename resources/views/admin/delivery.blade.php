@extends('layouts.admin')

@section('content')

<div class="loading" id="loading">
	<div>
		<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status">
		</div>
		<p>Espere un momento...</p>
	</div>
</div>

<div class="container-fluid wrapper" style="margin-top: 90px">	
	<div class="row">
		<div class="col">
			<div class="card shadow-sm">
				<div class="card-header d-flex justify-content-between">
					<h4>Ventas del día</h4>
					<p class="lead">{{ucfirst(Carbon::now()->isoFormat('dddd, LL'))}}</p>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table text-center table-sm table-hover table-bordered">
							<thead>
								<tr>
									<th>ID FACTURA</th>
									<th>MONTO (Bs)</th>
									<th>CLIENTE</th>
									<th>Tiempo transcurrido</th>
									<th>Confirmado</th>
									<th>Opciones</th>
								</tr>
							</thead>
							<tbody>
								@forelse($ventas as $venta)
									<tr>
										<td>{{$venta->code}}</td>
										<td>{{$venta->amount}}</td>
										<td>{{$venta->user->id}}</td>
										<td></td>
										<td id="dispatched-{{$venta->id}}">{{$venta->dispatched}}</td>
										<td>
											
											<button class="btn btn-success" data-toggle="modal" data-target="#checkModal-{{$venta->id}}"><i class="fas fa-check"></i></button>
											<a class="btn btn-danger" target="_blank" href="{{route('factura.pdf', ['id' => $venta->id])}}"><i class="fas fa-file-alt" style="color: #ffffff"></i></a>
											<button class="btn btn-md btn-primary" onclick="openModal({{$venta->id}})">
												<i class="fas fa-coins"></i>
											</button>
										</td>
									</tr>

									<!--MODAL DE CONFIRMACION-->

									<div class="modal fade" tabindex="-1" id="checkModal-{{$venta->id}}">
										<div class="modal-dialog">
									    	<div class="modal-content">
									    		<div class="modal-header">
									        	<h5 class="modal-title">Confirmacion de pedido</h5>
									        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									        	<span aria-hidden="true">&times;</span>
									        	</button>
									      	</div>
									    	<div class="modal-body" >
									        <p>¿Esta seguro que desea confirmar el pedido?</p>
									        <label for="stimated_time">Tiempo estimado de llegada (min)</label>
							
											<input type="text" id="stimated_time_{{$venta->id}}" class="form-control" name="stimated_time" placeholder="Ejm: 30:00" pattern="^[0-9]{2}:[0-9]{2}$" required>
									      	</div>
									    	<div class="modal-footer">
									        	<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
									        	<button type="submit" class="btn btn-primary" id="btn-confir-pedido" onclick="hideModalCheck({{$venta->id}})">Si</button>
									      	</div>
									
									    </div>
									  </div>
									</div>

									<!-- Modal -->
									<div class="modal fade" id="modalDetails-{{$venta->id}}" tabindex="-1" role="dialog" aria-labelledby="modalDetailsLabel" aria-hidden="true">
										<div class="modal-dialog" role="document">
										  <div class="modal-content">
											<div class="modal-header">
											  <h5 class="modal-title" id="modalDetailsLabel">Informacion de pago</h5>
											  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											  </button>
											</div>
											<div class="modal-body">
												@if($venta->attached_file)
												<div id="captura-{{$venta->id}}">
													<h6 class="font-weight-bold">Captura</h6>
													
													<img class="img-fluid img-thumbnail shadow" src="{{ url('storage/'.$venta->attached_file) }}" alt="captura del pago" style="height: 250px; width: 100%;" id="foto">
													
												</div>
												@endif
												@if($venta->payment_reference_code)
												<div id="referencia-{{$venta->id}}">
													<h6 class="font-weight-bold">Referencia</h6>
													<p>{{$venta->payment_reference_code}}</p>
												</div>
												@endif
												@if(!$venta->attached_file && !$venta->payment_reference_code)
												<div id="dolares-{{$venta->id}}">
													<h6 class="font-weight-bold">Pagara en dolares en efectivo</h6>
												</div>
												@endif
											</div>
											<div class="modal-footer">
											  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
											</div>
										  </div>
										</div>
									</div>
								@empty

									<tr>
										<td colspan="6">No hay datos para mostrar.</td>
									</tr>

								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


@endsection

@push('scripts')
<script>
	var ventas = @json($ventas);

	$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	
	function openModal(id) {

		$('#modalDetails-'+id).modal('show')
	}

	$(() => {
		$('#loading').fadeOut()
	})
	//MODAL DE CONFIRMACION Y CONSULTA AJAX DE CONFIRMACION
	function hideModalCheck(id){
		console.log($('#stimated_time_'+id).val())

		$.ajax({type: 'PUT', url: `/admin/confirmar-pedido-delivery/${id}`, data: {stimated_time: $('#stimated_time_'+id).val()}})
			.done((res) => {
				console.log(res)
				$('#dispatched-'+id).text(res);

			})
			.catch((err) => {
				toastr.error('Ha ocurrido un error')
				console.error(err.response)
			})


		$('#checkModal-'+id).modal('hide');
	}
	
	/*
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
	*/
</script>
@endpush