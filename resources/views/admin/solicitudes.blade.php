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
					<h4>Solicitudes</h4>
				</div>
				<div class="card-body">

					<div class="table-responsive">
						<table class="table text-center table-sm table-hover table-bordered" id="table_gain">
							<thead>
								<tr>
									<th class="negrita">NOMBRE</th>
									<th class="negrita">TELEFONO</th>
									<th class="negrita">PISO DE VENTA</th>
									<th class="negrita">PRODUCTO</th>
									<th class="negrita">ACCIONES</th>
								</tr>
							</thead>
							<tbody>
								@forelse($solicitudes as $solicitud)
									<tr>
										<td class="align-middle">{{ $solicitud->nombre }}</td>
										<td class="align-middle">{{ $solicitud->telefono }}</td>
										@switch($solicitud->piso_venta_id)
										    @case(1)
														<td class="align-middle">Abasto I</td>
										        @break

										    @case(2)
										        <td class="align-middle">Mi Puchito C.A</td>
										        @break

												@case(3)
										        <td class="align-middle">Abasto III</td>
										        @break

												@case(4)
										        <td class="align-middle">Abasto II</td>
										        @break

												@case(5)
										        <td class="align-middle">Piso Pruebas</td>
										        @break

										@endswitch
										<td class="align-middle">{{ $solicitud->producto }}</td>
										<td class="align-middle">
											<i class="fas fa-check-square text-primary" data-toggle="tooltip" data-placement="top" title="Finalizar Solicitud" role="button" onclick="showFinish({{ $solicitud->id }})"></i>
										</td>
									</tr>
								@empty

									<tr>
										<td colspan="6">No hay datos para mostrar.</td>
									</tr>

								@endforelse
							</tbody>
						</table>

						<!-- Modal Finalizar Solicitud -->
						<div class="modal fade" id="finishModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <h5 class="modal-title">Finalizar Solicitud</h5>
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						          <span aria-hidden="true">&times;</span>
						        </button>
						      </div>
						      <form method="post" id="finishForm">
										@csrf
										<div class="modal-body">
							        ¿Seguro que desea finalizar esta solicitud?
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
							        <button type="submit" class="btn btn-primary">Finalizar</button>
							      </div>
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
$(document).ready( function () {
	$('#table_gain').DataTable({
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

	function showFinish(id) {
		$('#finishForm').attr('action', `/solicitud/${id}`)
		$('#finishModal').modal('show');
	}

	function finishSol(id) {
		console.log("Finalizar solicitud "+id);
	}

</script>
@endpush
