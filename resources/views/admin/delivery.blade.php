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
					<!--RANGO DE FECHAS-->
					<div class="text-right my-3">
						<form action="/admin/delivery" method="get">
							<input type="text" name="fechas" id="fechas">
							<button type="submit" class="btn btn-primary">Filtrar</button>
						</form>
					</div>


					<div class="table-responsive">
						<table class="table text-center table-sm table-hover table-bordered">
							<thead>
								<tr>
									<th class="negrita">ID FACTURA</th>
									<th class="negrita">MONTO (BsS)</th>
									<th class="negrita">CLIENTE</th>
									<th class="negrita">INTERVALO</th>
									<th class="negrita">ESTADO</th>
									<th class="negrita">TIPO DE PAGO</th>
								</tr>
							</thead>
							<tbody>
								@forelse($ventas as $venta)
									<tr>
										{{--<td><a target="_blank" href="{{route('factura.pdf.descarga', ['id' => $venta->id])}}">FC-000{{$venta->id}}</a></td>--}}
										<td class="align-middle"><span role="button" class="text-primary" data-toggle="tooltip" data-title="Detalles" onclick='showInfo({{ $venta->id }})'>FC-000{{$venta->id}}</span></td>
										<td class="align-middle">
											<span class="negrita text-success">{{number_format($venta->amount * $venta->dolar->price, 2, ',', '.')}}</span>
											<br>
											<span class="negrita small text-success">{{number_format($venta->amount, 2, ',', '.')}}$</span>
										</td>
										<td class="align-middle"><a href="{{route('usuarios.show', ['id' => $venta->user->id])}}">{{$venta->user->people->name}} {{$venta->user->people->lastname}}</a></td>
										@if($venta->dispatched != null)
										<td class="align-middle">{{\Carbon\Carbon::createFromTimeStamp(strtotime($venta->dispatched))->diffForHumans()}}</td>
										@else
										<td class="align-middle"><b class="small negrita text-info">En Espera</b></td>
										@endif
										@if($venta->dispatched != null)
											@if ($venta->confirmacion != 'Entregado' && $venta->confirmacion != 'denegado')
												<td class="align-middle small negrita text-info text-capitalize" role="button" data-toggle="tooltip" data-placement="top" title="Finalizar Venta" onclick='showEstado({{ $venta->id }})'>{{$venta->confirmacion}}</td>
											@elseif ($venta->confirmacion == 'denegado')
												<td class="align-middle small negrita text-danger text-capitalize">{{$venta->confirmacion}}</td>
											@else
												<td class="align-middle small negrita text-success text-capitalize" id="dispatched-{{$venta->id}}">{{$venta->confirmacion}}</td>
											@endif
										@else
											<td class="align-middle">

												<button class="btn btn-secondary" data-toggle="modal" data-target="#checkModal-{{$venta->id}}"><i class="fas fa-clock"></i></button>
											</td>
										{{--<td class="align-middle" class="small font-weight-bold" id="dispatched-{{$venta->id}}">Sin confirmar</td>--}}
										@endif
										<td class="align-middle">
											@if($venta->dispatched != null)
											<!--
											<a class="btn btn-danger" target="_blank" href="{{route('factura.pdf.descarga', ['id' => $venta->id])}}"><i class="fas fa-file-alt" style="color: #ffffff"></i></a>
										-->
											@endif
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

											<input type="text" id="stimated_time_{{$venta->id}}" class="form-control" name="stimated_time" placeholder="Ejm: 30" pattern="^[0-9]{2}:[0-9]{2}$" required>
											<i class="font-weight-bold">Minutos</i>
									      	</div>
									    	<div class="modal-footer">
									        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

									        	<button type="button" class="btn btn-danger" id="btn-confir-pedido" onclick="hideModalCheck({{$venta->id}}, 'denegado')">Denegado</button>
									        	<button type="button" class="btn btn-primary" id="btn-confir-pedido" onclick="hideModalCheck({{$venta->id}}, 'aprobado')">Aprobado</button>
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
													<a href="{{ url('storage/app/public/'.$venta->attached_file) }}">
													<img class="img-fluid img-thumbnail shadow" src="{{ url('storage/app/public/'.$venta->attached_file) }}" alt="captura del pago" style="height: 250px; width: 100%;" id="foto">
													</a>
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

						<div class="float-right">
							<p >{{$ventas->render()}}</p>
						</div>
					</div>

					<!-- Modal -->
					<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="almacen" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="almacen"></h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>

								<div class="modal_loader py-5" id="modal_loader">
									<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status"></div>
								</div>

								<div class="modal-body">

									<div class="mt-3">
											<span class="float-left"><span class="negrita" id="fecha-create">Esta factura fue emitida:</span></span>
											<span class="float-right"><span class="negrita">Cliente:<span id="user-name"></span></span></span><br>
									</div>

							<!--TABLA DE PRODUCTOS-->

							<div class="table-responsive">
								<table class="table table-sm table-striped table-bordered mt-3">
										<thead class="bg-info text-white small text-center">
												<tr>
														<th class="negrita">Producto</th>
														<th class="negrita">Cantidad</th>
														<th class="negrita">Precio unitario</th>
														<!--<th class="negrita">iva unitario</th>-->
														<th class="negrita">Subtotal</th>
												</tr>
										</thead>
										<tbody id="table-products" class="small text-center">

										</tbody>
								</table>
							</div>
							<div class="mt-3">
								<span class="float-left"><span class="small" id="dir-show"></span></span>
								<span class="float-right text-right"><span class="negrita"><span id="total-show"></span></span></span><br>
								{{--<span class="text-right"><span class="negrita">Total: </span><span id="total-show"></span></span>--}}
							</div>

								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
									<a type="button" id="factura-pdf" class="btn btn-danger" target="_blank"><i class="fas fa-file-pdf mr-2"></i>PDF</a>
								</div>
							</div>
						</div>
					</div>

					<!--MODAL DE ESTADO-->
					<div class="modal fade" tabindex="-1" id="estadoModal">
						<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Finalizar pedido</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
								<div class="modal-body" >
									<p>¿Esta seguro que desea finalizar el pedido?</p>
								</div>
								<div class="modal-footer">
									<form id="estadoForm" method="post">
										@csrf
										@method('put')
										<input type="hidden" id="id_venta_estado" class="form-control" name="id_venta" required>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
										<button type="submit" class="btn btn-primary">Finalizar</button>
									</form>
								</div>

							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>


@endsection

@push('scripts')
<script>
	function showInfo(id) {
		$('#detailModal').modal('show');

		$.get({
			url : `/admin/delivery-data/${id}`,
			beforeSend(){
				$('#modal_loader').show()
			}
		})
		.done((data) => {

			console.log(data);
			var venta = data;

			$('#table-products').empty();

			$('#almacen').text(`Factura: 000${venta.id}`);
			$('#fecha-create').text(`${venta.created_at}`);
			//$('#user-name').text(`${venta.user.people.name}`);
			$('#user-name').empty();
			$('#user-name').append(venta.user.people.name);
			$('#user-dni').text(`${venta.user.people.dni}`);

			$.each( venta.details, function( key, value ) {

				//console.log(value.inventory);

					var subtotal = new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 2}).format((value.sub_total / value.quantity)*venta.dolar.price);
					var total = new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(value.amount*venta.dolar.price);

					$('#table-products').append('<tr><td class="aling-middle">'+value.product.inventory.product_name+'</td><td class="aling-middle">'+value.quantity+'</td><td class="aling-middle">'+subtotal+'</td><td class="aling-middle">'+total+'</td></tr>');

			});

			var totalShow = new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(venta.amount*venta.dolar.price);
			var totalDolar = new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(venta.amount);
			$('#total-show').empty();
			$('#total-show').append('<span class="negrita">Total: </span><span>'+totalShow+'BsS</span><br><span class="negrita small text-success">'+totalDolar+'$</span>');

			if (venta.rate.address_user_delivery != null) {
				if (venta.rate.address_user_delivery.travel_rate_id != null) {
					$('#dir-show').empty();
					$('#dir-show').append(venta.dir.sector.sector+' '+venta.rate.address_user_delivery.details);
					$('#user-name').append(' <br> Telf:'+venta.rate.address_user_delivery.phone_contact);
				} else {
					$('#dir-show').empty();
					$('#dir-show').append(venta.rate.address_user_delivery.details);
					$('#user-name').append('<br>Telf:'+venta.rate.address_user_delivery.phone_contact);
				}
			} else {
				$('#dir-show').empty();
				$('#dir-show').append(venta.sector.sector+' '+venta.dir.details);
				$('#user-name').append('<br>Telf:'+venta.dir.phone_contact);
			}

			var url = '/get-pedido-descarga/'+venta.id;
			$('#factura-pdf').attr('href', url);

			$('#modal_loader').fadeOut();
		})
		.fail((err)=> {
			console.log(err)
			toastr.error('Ha ocurrido un error.')
		})

	}

	function showEstado(id) {
		$('#estadoModal').modal('show');

		$('#estadoForm').attr('action', `/admin/finalizar-pedido-delivery/${id}`);

		$('#id_venta_estado').val(id);

	}

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
	function hideModalCheck(id, confirmacion){
		console.log($('#stimated_time_'+id).val())

		$.ajax({type: 'PUT', url: `/admin/confirmar-pedido-delivery/${id}`, data: {stimated_time: $('#stimated_time_'+id).val(), confirmacion: confirmacion}})
			.done((res) => {
				console.log(res)
				if (res == "confirmado") {

					toastr.error('esta venta ya ha sido confirmada')
				}else if(res == "el campo tiempo estimado es obligatorio"){
					toastr.error(res)
				}else{
					window.location.href = '/admin/delivery';
					$('#dispatched-'+id).text(res);
				}
			})
			.catch((err) => {
				toastr.error('Ha ocurrido un error')
				console.error(err)
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
