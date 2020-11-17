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
										<td><a href="{{route('factura.pdf.descarga', ['id' => $venta->id])}}">FC-000{{$venta->id}}</a></td>
										<td>{{$venta->amount}} <br> <span class="small font-weight-bold text-success">{{number_format($venta->amount / $venta->dolar->price, 2, ',', '.')}}$</span></td>
										<td><a href="{{route('usuarios.show', ['id' => $venta->user->id])}}">C-00{{$venta->user->id}}</a></td>
										@if($venta->dispatched != null)
										<td>{{\Carbon\Carbon::createFromTimeStamp(strtotime($venta->dispatched))->diffForHumans()}}</td>
										@else
										<td><b class="small font-weight-bold">Sin confirmado</b></td>
										@endif
										@if($venta->dispatched != null)
										
											<td id="dispatched-{{$venta->id}}" class="small font-weight-bold">{{$venta->confirmacion}}</td>	
										@else
										<td id="dispatched-{{$venta->id}}" class="small font-weight-bold">Sin confirmar</td>
										@endif

										<td>
											@if($venta->confirmacion == null)
											<button class="btn btn-success" data-toggle="modal" data-target="#checkModal-{{$venta->id}}"><i class="fas fa-check"></i></button>
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
													<a href="{{ url('storage/'.$venta->attached_file) }}">
													<img class="img-fluid img-thumbnail shadow" src="{{ url('storage/'.$venta->attached_file) }}" alt="captura del pago" style="height: 250px; width: 100%;" id="foto">
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