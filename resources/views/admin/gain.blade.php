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
					<h4>Ganancias</h4>
				</div>
				<div class="card-body">
					<!--RANGO DE FECHAS-->
					<div class="text-right my-3">
						<form action="/piso-ventas-ganancias" method="get">
							<input type="text" name="fechas" id="fechas">
							<button type="submit" class="btn btn-primary">Filtrar</button>
						</form>
					</div>


					<div class="table-responsive">
						<table class="table text-center table-sm table-hover table-bordered" id="table_gain">
							<thead>
								<tr>
									<th class="d-none">#</th>
									<th class="negrita">FECHA</th>
									<th class="negrita">PISO DE VENTA</th>
									<th class="negrita">CIERRE (BsS)</th>
									<th class="negrita">GANANCIA (BsS)</th>
								</tr>
							</thead>
							<tbody>
								@forelse($cajas as $caja)
									<tr>
										<td class="align-middle d-none">{{ $caja->id }}</td>
										<td class="align-middle">{{ Carbon::createFromFormat('Y-m-d H:i:s', $caja->created_at)->format('d-m-Y') }}</td>
										<td class="align-middle">{{ $caja->pisos_de_ventas->nombre }}</td>
										<td class="align-middle">{{ number_format($caja->monto, 2, ',', '.') }}</td>
										<td class="align-middle">{{ number_format($caja->ganancia, 2, ',', '.') }}</td>
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
$(document).ready( function () {
	$('#table_gain').DataTable({
		"searching": false,
		"order": [[ 0, "desc" ]],
		"language": {
			"search": "Buscar:",
			"scrollX": true,
			"emptyTable": "No se consigio nada",
			"info": "",
			"infoEmpty": "",
			"infoFiltered": "",
			"lengthMenu": "Ver _MENU_ Filas",
			"loadingRecords": "Cargando...",
			"processing": "Procesando...",
			"zeroRecords": "No se consigio nada",
			"paginate": {
				"first":      "Primero",
				"last":       "Ultimo",
				"next":       "Siguiente",
				"previous":   "Anterior"
			 }
			}
		});
	} );

	$(() => {
		$('#loading').fadeOut()
	})

</script>
@endpush
