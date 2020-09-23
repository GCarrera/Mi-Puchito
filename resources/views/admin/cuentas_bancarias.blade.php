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

	<button class="btn btn-primary btn-lg rounded-circle" data-target="#cuentas-bancarias-registrar" data-toggle="modal" style="position: fixed; bottom: 30px; right: 30px">
		<i class="fas fa-plus"></i>
	</button>


	{{-- <div class="row mb-5">
		<div class="col">
			<div class="card bg-primary text-white shadow-sm">
				<div class="card-body d-flex justify-content-between">
					<h5>{{ $productosCount }} Productos</h5>
					<i class="fas fa-chart-line fa-2x"></i>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card bg-primary text-white shadow-sm">
				<div class="card-body d-flex justify-content-between">
					<h5>{{ $empresasCount }} Empresas</h5>
					<i class="fas fa-building fa-2x"></i>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card bg-primary text-white shadow-sm">
				<div class="card-body d-flex justify-content-between">
					<h5>{{ $categoriasCount	 }} Categorias</h5>
					<i class="fas fa-clipboard-list fa-2x"></i>
				</div>
			</div>
		</div>
		<div class="col">
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
				<div class="card-body">

					<div class="table-responsive">

						<table class="table table-sm table-hover table-bordered text-center">
							<thead>
								<tr>
									<th>BANCO</th>
									<th>CODIGO</th>
									<th>NUMERO DE CUENTA</th>
									<th>CEDULA</th>
									<th>NOMBRE EMPRESA</th>
									<th>CORREO</th>
									<th>TELEFONO</th>
									<th colspan="2" class="text-center">ACCIONES</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($cb as $pro)
									<tr>
										<td>{{ $pro->bank }}</td>
										<td>{{ $pro->code }}</td>
										<td>{{ $pro->account_number }}</td>
										<td>{{ $pro->dni }}</td>
										<td>{{ $pro->name_enterprise }}</td>
										<td>{{ $pro->email_enterprise }}</td>
										<td>{{ $pro->phone_enterprise }}</td>
										<td class="text-center">
											<button class="btn btn-warning editar" data-toggle="modal" data-target="#cuentas-bancarias-edit" data-id="{{ $pro->id }}">
												<i class="fas fa-edit" data-toggle="tooltip" data-title="Editar"></i>
											</button>
										</td>
										<td class="text-center">
											<button class="btn btn-danger btn-md borrar" data-toggle="modal" data-target="#cuentas-bancarias-borrar" data-id="{{ $pro->id }}">
												<i class="fas fa-edit" data-toggle="tooltip" data-title="Borrar"></i>
											</button>
										</td>
									</tr>
								@empty
									<tr>
										<td class="text-center" colspan="7">No hay datos registrados.</td>
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




<!-- MODALES -->

<!-- Modal  registrar easdasd -->
<div class="modal fade" id="cuentas-bancarias-registrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Nueva Cuenta Bancaria</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/cuentas-bancarias" method="post">
				@csrf
				<div class="modal-body">

					<div class="row mb-3">
						<div class="col-12 mb-3 col-md-4">
							<label for="bank">Banco</label>
							<input type="text" name="bank" required id="bank" class="form-control">
						</div>
						<div class="col-12 mb-3 col-md-2">
							<label for="code">Código</label>
							<input type="text" name="code" maxlength="4" pattern="[0-9]{4}" required id="code" placeholder="0000" class="form-control">
						</div>
						<div class="col-12 mb-3 col-md-6">
							<label for="account_number">Número de cuenta</label>
							<input type="text" name="account_number" maxlength="23" pattern="[0-9]{4}-[0-9]{4}-[0-9]{2}-[0-9]{10}" placeholder="0000-0000-00-0000000000" required id="account_number" class="form-control">
						</div>
					</div>

					<div class="row">
						<div class="col-12 mb-3 col-md-4">
							<label for="dni">Cédula</label>
							<input type="text" name="dni" required id="dni" class="form-control" pattern="[0-9]{7,8,9}">
						</div>
						<div class="col-12 mb-3 col-md-4">
							<label for="name">Nombre empresa</label>
							<input type="text" name="name" required id="name" class="form-control">
						</div>
						<div class="col-12 mb-3 col-md-4">
							<label for="phone">Teléfono</label>
							<input type="text" name="phone" required id="phone" pattern="[0-9]{4}-[0-9]{3}-[0-9]{4}" placeholder="0000-000-0000" class="form-control">
						</div>
					</div>

					<div class="row">
						<div class="col-12 mb-3 col-md-4">
							<label for="email">Correo Electrónico</label>
							<input type="email" name="email" id="email" class="form-control">
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

<!-- Modal editar  -->
<div class="modal fade" id="cuentas-bancarias-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Cuentas Bancarias</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="editform" method="post">
				@method('put')
				@csrf

				<div class="modal-body">

					<div class="row mb-3">
						<div class="col-12 mb-3 col-md-4">
							<label for="bank_edit">Banco</label>
							<input type="text" name="bank" required id="bank_edit" class="form-control">
						</div>
						<div class="col-12 mb-3 col-md-2">
							<label for="code_edit">Código</label>
							<input type="text" name="code" maxlength="4" pattern="[0-9]{4}" required id="code_edit" placeholder="0000" class="form-control">
						</div>
						<div class="col-12 mb-3 col-md-6">
							<label for="account_number_edit">Número de cuenta</label>
							<input type="text" name="account_number" maxlength="23" pattern="[0-9]{4}-[0-9]{4}-[0-9]{2}-[0-9]{10}" placeholder="0000-0000-00-0000000000" required id="account_number_edit" class="form-control">
						</div>
					</div>

					<div class="row">
						<div class="col-12 mb-3 col-md-4">
							<label for="dni_edit">Cédula</label>
							<input type="text" name="dni" required id="dni_edit" class="form-control" pattern="[0-9]{7,8,9}">
						</div>
						<div class="col-12 mb-3 col-md-4">
							<label for="name_edit">Nombre empresa</label>
							<input type="text" name="name" required id="name_edit" class="form-control">
						</div>
						<div class="col-12 mb-3 col-md-4">
							<label for="phone_edit">Teléfono</label>
							<input type="text" name="phone" required id="phone_edit" pattern="[0-9]{4}-[0-9]{3}-[0-9]{4}" placeholder="0000-000-0000" class="form-control">
						</div>
					</div>

					<div class="row">
						<div class="col-12 mb-3 col-md-4">
							<label for="email_edit">Correo Electrónico</label>
							<input type="email" name="email" id="email_edit" class="form-control">
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
					<button type="submit" id="sendform" class="btn btn-primary"><i class="fas fa-edit mr-2"></i>Editar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal eliminar  -->
<div class="modal fade" id="cuentas-bancarias-borrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="delform" method="post">
				@method('delete')
				@csrf

				<div class="modal-body">

					<h4 class="text-center my-5">¿Está seguro de querer borrar la cuenta bancaria?</h4>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
					<button type="submit" class="btn btn-primary"><i class="fas fa-trash mr-2"></i>Borrar</button>
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

		$('.editar').click(function(){
			let id = $(this).data('id')

			$('#editform').attr('action', `/admin/cuentas-bancarias/${id}`)

			$.get(`/admin/cuentas-bancarias/${id}`, (res) => {
				console.log(res)

				$('#bank_edit').val(res.bank)
				$('#code_edit').val(res.code)
				$('#account_number_edit').val(res.account_number)
				$('#dni_edit').val(res.dni)
				$('#name_edit').val(res.name_enterprise)
				$('#phone_edit').val(res.phone_enterprise)
				$('#email_edit').val(res.email_enterprise)
			})
			.catch((err) => {
				console.error(err)
				toastr.error('Ha ocurrido algo.')
			})
		})

		$('.borrar').click(function(){
			let id = $(this).data('id')

			$('#delform').attr('action', `/admin/cuentas-bancarias/${id}`)
		})

	})
</script>
@endpush