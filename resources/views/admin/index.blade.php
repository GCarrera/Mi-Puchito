@extends('layouts.admin')

@section('content')

<div class="loading" id="loading">
	<div>
		<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status">
		</div>
		<p>Espere un momento...</p>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalDetails" tabindex="-1" role="dialog" aria-labelledby="modalDetailsLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title" id="modalDetailsLabel">Detalles de la venta</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body">

		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		</div>
	  </div>
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
									<th>DELIVERY</th>
									<th>INFORMACION</th>
								</tr>
							</thead>
							<tbody>
								@forelse($ventas as $venta)
									<tr>
										<td>{{$venta->code}}</td>
										<td>{{$venta->amount}}</td>
										<td>{{$venta->user->people->name}}</td>
										<td>{{strtoupper($venta->delivery)}}</td>
										<td>
											<button class="btn btn-md btn-primary" onclick="detailSale({{$venta->id}})">
												<i class="fas fa-info-circle"></i>
											</button>
										</td>
									</tr>
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
	
	function detailSale(id) {
		console.log(id)
		$(".modal-body").html("")
		let sales = ventas.find(el=>el.id == id)
		if (sales.details) {
			for (let i = 0; i < Object.keys(sales.details).length; i++) {
				let product_name = sales.details[i].inventory ? sales.details[i].inventory.product_name : ''
				let quanty = sales.details[i].quantity ? sales.details[i].quantity : ''
				console.log(product_name, quanty)
				$(".modal-body").append("<b>Producto: </b>"+product_name+" ")
				$(".modal-body").append("<b>Cantidad:</b>"+quanty+"<br>")
			}
		}
		$('#modalDetails').modal('show')
	}

	$(() => {
		$('#loading').fadeOut()
	})
</script>
@endpush