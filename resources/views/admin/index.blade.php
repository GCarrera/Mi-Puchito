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
						<form action="/admin" method="get">
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
									<th class="negrita">ESTADO</th>
									<th class="negrita">OPCIONES</th>
								</tr>
							</thead>
							<tbody>
								@forelse($ventas as $venta)

									<tr>
										<td class="align-middle"><span role="button" class="text-primary" data-toggle="tooltip" data-title="Detalles" onclick='showInfo({{ $venta->id }})'>FC-000{{$venta->id}}</span></td>
										<td class="align-middle">
											<span class="negrita text-success">{{number_format($venta->amount * $venta->dolar->price, 2, ',', '.')}}</span>
											<br>
											<span class="negrita small text-success">{{number_format($venta->amount, 2, ',', '.')}}$</span>
										</td>
										<td class="align-middle"><a href="{{route('usuarios.show', ['id' => $venta->user->id])}}">{{$venta->user->people->name}} {{ $venta->user->people->lastname }}</a></td>
										@if($venta->dispatched != null)
											<td class="align-middle" id="dispatched-{{$venta->id}}" class="small font-weight-bold">
												<span class="text-capitalize">{{$venta->confirmacion}}</span>
												<br>
												<span class="text-capitalize small">{{\Carbon\Carbon::createFromTimeStamp(strtotime($venta->dispatched))->diffForHumans()}}</span>
											</td>
										@else
										<td class="align-middle" id="dispatched-{{$venta->id}}" class="small font-weight-bold">En Espera</td>
										@endif

										<td class="align-middle">
											@if($venta->confirmacion == null)
											<button class="btn btn-success" data-toggle="modal" data-target="#checkModal-{{$venta->id}}"><i class="fas fa-check"></i></button>
											<!--
											<a class="btn btn-danger" target="_blank" href="{{route('factura.pdf.descarga', ['id' => $venta->id])}}"><i class="fas fa-file-alt" style="color: #ffffff"></i></a>
											-->
											@endif

											@if($venta->confirmacion == 'aprobado')

											<button class="btn btn-info" onclick="openModalFinalizarVenta({{$venta->id}})">
												<i class="fas fa-calendar-check"></i>
											</button>

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
									    	<div class="modal-body">
									        <p>¿Esta seguro que desea confirmar el pedido?</p>
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
										<td class="align-middle" colspan="6">No hay datos para mostrar.</td>
									</tr>

								@endforelse
							</tbody>

						</table>

						<div class="float-right">
							<p >{{$ventas->render()}}</p>
						</div>
					</div>

					<!-- Modal Finalizar Venta -->
					<div class="modal fade" id="modalFinalizarVenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title">Finalizar Venta</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
								<form id="form_finalizar" method="post">
									@method('put')
									@csrf
					      <div class="modal-body">
					        ¿Esta seguro que desea finalizar esta venta?
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					        <button type="submit" class="btn btn-primary">Finalizar</button>
					      </div>
							</form>
					    </div>
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
											<span class="float-left"><span class="font-weight-bold" id="fecha-create">Esta factura fue emitida:</span></span>
											<span class="float-right"><span class="font-weight-bold">Cliente:<span id="user-name"></span></span></span><br>
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
							<div class="text-right" id="total-show">

							</div>

								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
									<a type="button" id="factura-pdf" class="btn btn-danger" target="_blank"><i class="fas fa-file-pdf mr-2"></i>PDF</a>
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
		url : `/admin/delivery-data-simple/${id}`,
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
		$('#user-name').text(`${venta.user.people.name}`);
		$('#user-dni').text(`${venta.user.people.dni}`);

		$.each( venta.details, function( key, value ) {

			//console.log(value.inventory);

				var subtotal = new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 2}).format((value.sub_total / value.quantity)*venta.dolar.price);
				var total = new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(value.amount*venta.dolar.price);

				$('#table-products').append('<tr><td class"align-middle">'+value.product.inventory.product_name+'</td><td class"align-middle">'+value.quantity+'</td><td class"align-middle">'+subtotal+'</td><td class"align-middle">'+total+'</td></tr>');

		});

		var totalShow = new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(venta.amount*venta.dolar.price);
		var totalDolar = new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(venta.amount);
		//$('#total-show').text(totalShow);
		$('#total-show').empty();
		$('#total-show').append('<span class="negrita">Total: </span><span>'+totalShow+'BsS</span><br><span class="negrita small text-success">'+totalDolar+'$</span>');
		var url = '/get-pedido-descarga/'+venta.id;
		$('#factura-pdf').attr('href', url);

		$('#modal_loader').fadeOut();
	})
	.fail((err)=> {
		console.log(err)
		toastr.error('Ha ocurrido un error.')
	})

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

	function openModalFinalizarVenta(id) {

		$('#form_finalizar').attr('action', `/admin/finalizar-venta/${id}`)

		$('#modalFinalizarVenta').modal('show');

	}

	$(() => {
		$('#loading').fadeOut()
	})

	function hideModalCheck(id, confirmacion){
		//console.log(id)

		$.ajax({url: `/admin/confirmar-pedido/${id}`, type: 'put', data: {confirmacion: confirmacion} })
			.done((res) => {
				console.log(res)
				if (res == "confirmado") {

					toastr.error('esta venta ya ha sido confirmada')
				}else{

				$('#dispatched-'+id).text(res);

				}
			})
			.catch((err) => {
				toastr.error('Ha ocurrido un error')
				console.error(err)
			})


		$('#checkModal-'+id).modal('hide');
	}




</script>
@endpush
