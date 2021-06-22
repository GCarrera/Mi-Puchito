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

	<button class="btn btn-primary btn-lg rounded-circle" data-toggle="modal" data-target="#addProduct" style="position: fixed; bottom: 30px; right: 30px">
		<i class="fas fa-plus"></i>
	</button>

	<div class="row mb-5">
		<div class="col">
			<div class="card shadow-sm">
				<div class="card-header d-flex justify-content-between mb-3">
					<h3>Inventarios</h3>
					<p class="lead">
						<span class="font-weight-normal">Almacen:</span> <span>{{ $almacen }}</span>
					</p>
				</div>
				<div class="card-body">
					<div class="table-responsive-xl">

						<example-component v-bind:inventario="{{$inventario}}" />

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- MODALES -->
<!-- Modal añadir poducto -->
<div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Añadir producto al inventario</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/inventory" method="post">
				@csrf

				<input type="hidden" name="cantidad_producto_hd" id="cantidad_producto_hd">

				<div class="modal-body">


					<div class="form-row mb-4">
						<div class="col-md-6 col-12 mb-3">
							<label for="product_name">Nombre del producto</label>
							<input type="text" class="form-control ProductNameAutoComplete" name="product_name" id="product_name" maxlength="255" required autocomplete="off">
						</div>
						<div class="col-md-3 col-12 mb-3">
							<label for="enterprise">Empresa fabricante</label><br>
							<select name="enterprise" id="enterprise" class="selectpicker border form-control" data-live-search="true" data-width="100%">
								<option disabled selected>Selecciona</option>
								@foreach ($empresas as $e)
									<option value="{{ $e->id }}">{{ $e->name }}</option>
								@endforeach
							</select>
							<small ><a href="/admin/empresa_categorias">Ir a empresas y categorias</a></small>
						</div>
						<div class="col-md-3 col-12">
							<label for="category">Categoria</label><br>
							<select name="category" id="category" class="selectpicker border form-control" data-live-search="true" data-width="100%">
								<option disabled selected>Selecciona</option>
								@foreach ($categorias as $c)
									<option value="{{ $c->id }}">{{ $c->name }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="form-row mb-4">
						<div class="col-md-2 col-12 mb-3">
							<label for="cantidad">Cantidad</label>
							<input type="number" min="0" class="form-control" name="cantidad" id="cantidad" required>
							<small class="text-muted text-help">Cantidad comprada al mayor</small>
						</div>
						<div class="col-md-2 col-12 mb-3">
							<label for="tipo_unidad_menor">Presentación</label>
							<select class="selectpicker border form-control" name="tipo_unidad_menor" id="tipo-unidad-menor" data-width="100%" required>
								<option disabled>Selecciona</option>
								<option value="Kg">Kg</option>
								<option value="Unidad">Unidad</option>
								<option value="Lata">Lata</option>
								<option value="Empaque">Empaque</option>
								<option value="Tasa">Tasa</option>
								<option value="Granel">Granel</option>
								<option value="Bolsa">Bolsa</option>
								<option value="Sobre">Sobre</option>
								<option value="Papeleta">Papeleta</option>
								<option value="Caja">Caja</option>
								<option value="Otros">Otros</option>
							</select>
						</div>
						<div class="col-md-4 col-12 mb-3">
							<label for="tipo_unidad">Tipo de unidad</label><br>
							<select name="tipo_unidad" id="tipo_unidad" class="selectpicker border form-control" data-width="100%">
								<option selected disabled>Selecciona</option>
								<option value="bulto">Bulto</option>
								<option value="caja">Caja</option>
								<option value="saco">Saco</option>
								<option value="granel">Granel</option>
								<option value="cesta">Cesta</option>
								<option value="bolsa">Bolsa</option>
							</select>
							<small class="text-muted text-help">Tipo de unidad comprada al mayor</small>
						</div>

						<div class="col-md-4 col-12">
							<label for="cant_prod">Cantidad por unidad</label>
							<input type="text" pattern="^[0-9]+([.][0-9]+)?$" class="form-control" name="cant_prod" id="cant_prod" required>
						</div>
					</div>

					<div class="form-row mb-4">
						<div class="col-3">
							<label for="stock_min">Stock Minimo</label>
							<input type="text" pattern="^[0-9]+([.][0-9]+)?$" class="form-control" name="stock_min" id="stock_min" required>
						</div>
						<div class="col-6">
							<label for="description">Descripción del producto</label>
							<textarea name="description" id="description" rows="3" class="form-control" required></textarea>
						</div>
						<div class="col-3 text-right">
							<h5>Cantidad Total</h5>
							<p class="lead cantidad_producto">-</p>
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

<!-- Modal EDITAsR poducto -->
<div class="modal fade" id="editPRoduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Editar producto seleccionado</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_edit" method="post">
				@method('put')
				@csrf

				<input type="hidden" name="cantidad_producto_hd" id="cantidad_producto_hd_edit">

				<div class="modal_loader py-5" id="modal_loader">
					<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status"></div>
				</div>

				<div class="modal-body">

					<div class="form-row mb-4">
						<div class="col-md-6 col-12 mb-3">
							<label for="product_name">Nombre del producto</label>
							<input type="text" class="form-control ProductNameAutoComplete" name="product_name" id="product_name_edit" required autocomplete="off">
						</div>
						<div class="col-md-3 col-12 mb-3">
							<label for="enterprise">Empresa fabricante</label><br>
							<select name="enterprise" id="enterprise_edit" class="selectpicker border form-control" data-live-search="true" data-width="100%">
								<option disabled selected>Selecciona</option>
								@foreach ($empresas as $e)
									<option value="{{ $e->id }}">{{ $e->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-3 col-12">
							<label for="category">Categoria</label><br>
							<select name="category" id="category_edit" class="selectpicker border form-control" data-live-search="true" data-width="100%">
								<option disabled selected>Selecciona</option>
								@foreach ($categorias as $c)
									<option value="{{ $c->id }}">{{ $c->name }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="form-row mb-4">
						<div class="col-md-3 col-12 mb-3">
							<label for="cantidad">Cantidad</label>
							<input type="number" min="0" class="form-control" name="cantidad" id="cantidad_edit" required>
							<small class="text-muted text-help">Cantidad comprada al mayor</small>
						</div>
						<div class="col-md-3 col-12 mb-3">
							<label for="tipo_unidad">Tipo de unidad</label><br>
							<select name="tipo_unidad" id="tipo_unidad_edit" class="selectpicker border form-control" data-width="100%">
								<option disabled selected>Selecciona</option>
								<option value="bulto">Bulto</option>
								<option value="caja">Caja</option>
								<option value="saco">Saco</option>
								<option value="granel">Granel</option>
								<option value="cesta">Cesta</option>
								<option value="bolsa">Bolsa</option>
							</select>
							<small class="text-muted text-help">Tipo de unidad comprada al mayor</small>
						</div>
						<div class="col-md-3 col-12">
							<label for="cant_prod">Cantidad por unidad</label>
							<input type="text" pattern="^[0-9]+([.][0-9]+)?$" class="form-control" name="cant_prod" id="cant_prod_edit" required>
						</div>
						<div class="col-md-3 col-12">
							<label for="presentacion_edit">Presentación</label><br>
							<select name="presentacion" id="presentacion_edit" class="selectpicker border form-control" data-width="100%">
								<option disabled selected>Selecciona</option>
								<option value="Kg">Kg</option>
								<option value="Unidad">Unidad</option>
								<option value="Lata">Lata</option>
								<option value="Empaque">Empaque</option>
								<option value="Tasa">Tasa</option>
								<option value="Granel">Granel</option>
								<option value="Bolsa">Bolsa</option>
								<option value="Sobre">Sobre</option>
								<option value="Papeleta">Papeleta</option>
								<option value="Caja">Caja</option>
								<option value="Otros">Otros</option>
							</select>
						</div>
					</div>

					<div class="form-row mb-4">
						<div class="col-4">
							<label for="stock_min_edit">Stock Minimo</label>
							<input type="text" pattern="^[0-9]+([.][0-9]+)?$" class="form-control" name="stock_min" id="stock_min_edit" required>
						</div>
						<div class="col-8">
							<label for="description_edit">Descripción del producto</label>
							<textarea name="description" id="description_edit" rows="3" class="form-control"></textarea>
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

@endsection

@push('scripts')
<script>
function showEdit(id) {
	$('#editPRoduct').modal('show');

	$('#form_edit').attr('action', `/inventory/${id}`)

	$.get({
		url: `/inventory/${id}`,
		beforeSend(){
			$('#modal_loader').show()
		}
	})
	.done((response) => {
		console.log(response)

		$('#product_name_edit').val(response.product_name)

		$('#enterprise_edit').val(response.enterprise_id)
		$('#enterprise_edit').change()

		$('#category_edit').val(response.category_id)
		$('#category_edit').change()

		$('#cantidad_edit').val(response.quantity)

		$('#tipo_unidad_edit').val(response.unit_type)
		$('#tipo_unidad_edit').change()

		$('#presentacion_edit').val(response.unit_type_menor)
		$('#presentacion_edit').change()

		$('#cant_prod_edit').val(response.qty_per_unit)

		$('#description_edit').val(response.description)
		$('#stock_min_edit').val(response.stock_min)

		let cantidad  = $('#cantidad').val() || $('#cantidad_edit').val()
		let cant_prod = $('#cant_prod').val() || $('#cant_prod_edit').val()

		let productos_totales = cantidad * cant_prod

		$('#cantidad_producto_hd_edit').val(productos_totales)

		$('#modal_loader').fadeOut()
	})
	.fail((err) => {
		console.error(err)
		toastr.error('Algo a ocurrido.')
	})
};

	$(() => {

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

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

		$('.ProductNameAutoComplete').autoComplete({
			minLength: 2,
			resolverSettings: {
				url: '/traer_productos',
			}
		});

		$('#cantidad, #cant_prod, #cantidad_edit, #cant_prod_edit').on('keyup change', function() {
			// logica para calcular los totales


			let cantidad  = $('#cantidad').val() || $('#cantidad_edit').val()
			let cant_prod = $('#cant_prod').val() || $('#cant_prod_edit').val()

			let productos_totales = cantidad * cant_prod

			$('.cantidad_producto').text(`${productos_totales} productos`)
			$('#cantidad_producto_hd').val(productos_totales)
			$('#cantidad_producto_hd_edit').val(productos_totales)
		});

	})

</script>
@endpush
