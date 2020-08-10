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
										<td>{{$venta->dispatched}}</td>
										<td>
											
											<button class="btn btn-success"><i class="fas fa-check"></i></button>
											<a class="btn btn-danger" target="_blank" href="{{route('factura.pdf', ['id' => $venta->id])}}"><i class="fas fa-file-alt" style="color: #ffffff"></i></a>
											<button class="btn btn-md btn-primary" onclick="openModal({{$venta->id}})">
												<i class="fas fa-receipt"></i>
											</button>
										</td>
									</tr>

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
	
	function openModal(id) {

		$('#modalDetails-'+id).modal('show')
	}

	$(() => {
		$('#loading').fadeOut()
	})
</script>
@endpush