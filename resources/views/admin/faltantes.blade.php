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

	<div class="row mb-5">
		<div class="col">
			<div class="card shadow-sm">
				<div class="card-header d-flex justify-content-between mb-3">
					<h3>Faltantes</h3>
					<p class="lead">
						<span class="font-weight-normal">Almacen:</span> <span>{{ $almacen }}</span>
					</p>
				</div>
				<div class="card-body">
					<div class="table-responsive">

						<table class="table table-sm table-hover table-bordered text-center" id="inventario-table">
							<thead>
								<tr>
									<th>CODIGO</th>
									<th>PRODUCTO</th>
									<th>CANTIDAD</th>
									<th>UBICACIÓN</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($inventario as $producto)
									<tr>
										<td class="small">{{ $producto['codigo'] }}</td>
										<td class="small">{{ $producto['product_name'] }}</td>
										<td class="small" id="total-productos">{{ $producto['total_qty_prod'] }}</td>
										<td class="small">{{ $producto['ubicacion'] }}</td>
									</tr>

								@empty
									<tr class="text-center">
										<td colspan="6">No hay datos registrados.</td>
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
		$('#inventario-table').DataTable({
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

		toastr.options = {
			"closeButton": true,
			"progressBar": true,
			"positionClass": "toast-bottom-left",
		}

		// Mostrar notificaciones
		@if (session('success'))
			toastr.success("<?php echo session('success') ?>")
		@endif

		@if ($errors->any())
			@foreach ($errors->all() as $error)
				toastr.error("{{ $error }}")
            @endforeach
		@endif()

	})

</script>
@endpush
