@extends('layouts.admin')

@section('content')

<div class="loading" id="loading">
	<div>
		<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status">
		</div>
		<p>Espere un momento...</p>
	</div>
</div>

<div class="container-fluid mb-5 wrapper" style="margin-top: 90px">


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
					<h5>{{ $categoriasCount }} Categorias</h5>
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

	<div class="row">
		<div class="col-md-6 col-12 mb-4">
			<div class="card shadow-sm">
				<div class="card-header d-flex justify-content-between">
					<p class="lead">Empresas</p>
					<button class="btn btn-primary btn-md" data-toggle="modal" data-target="#addEnterprise">
						<i class="fas fa-plus mr-2"></i>Añadir
					</button>
				</div>
				<div class="card-body">
					<table class="table table-sm table-hover table-bordered">
						<thead>
							<tr>
								<th style="min-width: 200px">EMPRESA</th>
								<th>FECHA</th>
								<th class="text-center" colspan="2">ACCIONES</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($empresas as $emp)
								<tr>
									<td>{{ $emp->name }}</td>
									<td>{{ $emp->created_at }}</td>
									<td class="text-center">
										<button class="btn btn-warning btn-md modalEditar" data-type="empresa" data-id="{{ $emp->id }}" data-toggle="modal" data-target="#modalEditar">
											<i class="fas fa-edit"></i>
										</button>
									</td>
									<td>
										<button class="btn btn-danger btn-md modalEliminar" data-type="empresa" data-id="{{ $emp->id}}" data-toggle="modal" data-target="#modalEliminar">
											<i class="fas fa-trash"></i>
										</button>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="3" class="text-center">No hay empresas registradas.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-6 col-12">
			<div class="card shadow-sm">
				<div class="card-header d-flex justify-content-between">
					<p class="lead">Categorias</p>
					<button class="btn btn-primary btn-md" data-toggle="modal" data-target="#addCategory">
						<i class="fas fa-plus mr-2"></i>Añadir
					</button>
				</div>
				<div class="card-body">
					<table class="table table-sm table-hover table-bordered">
						<thead>
							<tr>
								<th style="min-width: 200px">CATEGORIA</th>
								<th>FECHA</th>
								<th class="text-center" colspan="2">ACCIONES</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($categorias as $cat)
								<tr>
									<td>{{ $cat->name }}</td>
									<td>{{ $cat->created_at }}</td>
									<td class="text-center">
										<button class="btn btn-warning btn-md modalEditar" data-type="categoria" data-toggle="modal" data-target="#modalEditar" data-id="{{ $cat->id }}">
											<i class="fas fa-edit"></i>
										</button>
									</td>
									<td>
										<button class="btn btn-danger btn-md modalEliminar" data-type="categoria" data-toggle="modal" data-target="#modalEliminar" data-id="{{ $cat->id }}">
											<i class="fas fa-trash"></i>
										</button>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="3" class="text-center">No hay categorias registradas.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- MODALES -->
<!-- Modal añadir categoria -->
<div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Añadir nueva categoria</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/registrar_categoria" method="post">
				@csrf
				<div class="modal-body">

					<div class="form-row">
						<div class="col-12">
							<label for="categ_name">Nombre de la categoria</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1">
										<i class="fas fa-clipboard-list"></i>
									</span>
								</div>
								<input type="text" class="form-control" name="name" id="categ_name" required>
							</div>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Guardar</button>
				</div>
			</form>
		</div>
	</div>
</div>

{{-- MOdal añador empresa --}}
<div class="modal fade" id="addEnterprise" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Añadir empresa nueva</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/registrar_empresa" method="post">
				@csrf
				<div class="modal-body">

					<div class="form-row">
						<div class="col-12">
							<label for="enterprise_name">Nombre de la empresa</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="basic-addon1">
										<i class="fas fa-building"></i>
									</span>
								</div>
								<input type="text" class="form-control" name="name" id="enterprise_name" required>
							</div>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Guardar</button>
				</div>
			</form>
		</div>
	</div>
</div>

{{-- Modal Editar --}}
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Editar la <span class="text_type"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" id="editarForm">
				@csrf
				<input type="hidden" id="editarHidden">

				<div class="modal-body" id="editarContent"></div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">
						<i class="fas fa-edit mr-2"></i>Editar
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

{{-- Modal eliminarcion --}}
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="eliminarForm" method="post">
				@csrf

				<input type="hidden" id="hidden">
				<div class="modal-body">

					<p class="lead text-center my-4">¿Está seguro de querer borrar la <span id="text_type"></span>?</p>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">
						<i class="fas fa-trash mr-2"></i>Eliminar
					</button>
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

		// MOdales dinamicos
		$('.modalEliminar').click(function(){

			if ($(this).data('type') == 'categoria') {

				$('#eliminarForm').attr('action', '/eliminar_categoria')

				let id = $(this).data('id')
				$('#hidden').attr({
					name: 'categoriaid',
					value: id
				})

				$('#text_type').text($(this).data('type'))
			}
			else {
				$('#eliminarForm').attr('action', '/eliminar_empresa')

				let id = $(this).data('id')
				$('#hidden').attr({
					name: 'empresaid',
					value: id
				})

				$('#text_type').text($(this).data('type'))
			}
		})

		$('.modalEditar').click(function(){
			let token = $('meta[name=csrf-token]')[0].content

			if ($(this).data('type') == 'empresa') {
				let id = $(this).data('id')

				$('#editarHidden').attr({
					name: 'empresaid',
					value: id
				})

				$('.text_type').text('empresa')
				$('#editarForm').attr('action', '/editar_empresa')

				$.post(`/traer_empresa`,{ '_token': token, id }, (response) => {
					$('#editarContent').html('')
					$('#editarContent').append(`
						<label>Nombre de la empresa</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-building"></i>
								</span>
							</div>
							<input type="text" class="form-control" name="name" required value="${response.name}">
						</div>
					`)
				})
			}
			else {
				let id = $(this).data('id')

				$('#editarHidden').attr({
					name: 'categoriaid',
					value: id
				})

				$('.text_type').text('categoria')
				$('#editarForm').attr('action', '/editar_categoria')

				$.post(`/traer_categoria`,{ '_token': token, id }, (response) => {
					$('#editarContent').html('')
					$('#editarContent').append(`
						<label>Nombre de la categoria</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="fas fa-clipboard-list"></i>
								</span>
							</div>
							<input type="text" class="form-control" name="name" required value="${response.name}">
						</div>
					`)
				})
			}
		})
	})
</script>
@endpush