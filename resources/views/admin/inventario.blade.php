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

	<div class="row mb-5">
		<div class="col">
			<div class="card shadow-sm">
				<div class="card-header d-flex justify-content-between mb-3">
					<h3>Inventario</h3>
					<p class="lead">
						<span class="font-weight-normal">Almacen:</span> <span>{{ $almacen }}</span>
					</p>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						
						<table class="table table-sm table-hover table-bordered">
							<thead>
								<tr>
									<th>PRODUCTO</th>
									<th>CANTIDAD</th>
									<th>EMPRESA</th>
									<th>CATEGORIA</th>
									<th>FECHA</th>
									<th class="text-center">ACCIONES</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($inventario as $producto)
									<tr>
										<td>{{ $producto->product_name }}</td>
										<td id="total-productos-{{$producto->id}}">{{ $producto->total_qty_prod }}</td>
										<td>{{ $producto->enterprise->name }}</td>
										<td>{{ $producto->category->name }}</td>
										<td>{{ $producto->created_at }}</td>
										<td class="text-center">
											<button class="btn btn-warning btn-md editProduct" data-target="#editPRoduct" data-toggle="modal" data-id="{{ $producto->id }}">
												<i class="fas fa-edit"></i>
											</button>
											<button class="btn btn-primary" data-toggle="modal" data-target="#modal-sumar-{{$producto->id}}">
												<i class="fas fa-plus"></i>
											</button>
											<button class="btn btn-danger" data-toggle="modal" data-target="#modal-restar-{{$producto->id}}">
												<i class="fas fa-minus"></i>
											</button>
										</td>
									</tr>


									<!-- MODAL PARA SUMAR PRODUCTOS -->
									<div class="modal fade" id="modal-sumar-{{$producto->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									  	<div class="modal-dialog">
									    	<div class="modal-content">
									      		<div class="modal-header">
									        		<h5 class="modal-title" id="staticBackdropLabel">Sumar productos</h5>
									        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									          		<span aria-hidden="true">&times;</span>
									        		</button>
									      		</div>
									      		<div class="modal-body">
									      			<label for="cantidad-sumar-{{$producto->id}}">Cantidad al menor a sumar:</label>
									        		<div class="row">
									        			<div class="col-6">

											        		<input type="text" name="cantidad_sumar" id="cantidad-sumar-{{$producto->id}}" class="form-control" placeholder="60">
											        		
									        			</div>
									        			<div class="col-6">
									        				<button class="btn btn-primary" onclick="sumar({{$producto->id}})">Agregar</button>
									        			</div>
									        		</div>
									      		</div>

									    	</div>
									  	</div>
									</div>


									<!-- MODAL PARA RESTAR PRODUCTOS -->
									<div class="modal fade" id="modal-restar-{{$producto->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									  	<div class="modal-dialog">
									    	<div class="modal-content">
									      		<div class="modal-header">
									        		<h5 class="modal-title" id="staticBackdropLabel">Restar productos</h5>
									        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									          		<span aria-hidden="true">&times;</span>
									        		</button>
									      		</div>
									      		<div class="modal-body">
									      			<label for="cantidad-sumar-{{$producto->id}}">Cantidad al menor a restar:</label>
									        		<div class="row">
									        			<div class="col-6">

											        		<input type="text" name="cantidad_restar" id="cantidad-restar-{{$producto->id}}" class="form-control" placeholder="60">
											        		
									        			</div>
									        			<div class="col-6">
									        				<button class="btn btn-primary" onclick="restar({{$producto->id}})">Restar</button>
									        			</div>
									        		</div>
									      		</div>

									    	</div>
									  	</div>
									</div>
								@empty
									<tr class="text-center">
										<td colspan="6">No hay datos registrados.</td>
									</tr>
								@endforelse
							</tbody>
							
						</table>
						<div class="float-right">
							<p >{{$inventario->render()}}</p>
						</div>
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
							<label for="tipo_unidad_menor">Unidad al menor</label>
							<input type="text" class="form-control" name="tipo_unidad_menor" id="tipo-unidad-menor" required>
							<small class="text-muted text-help">Tipo de unidad en la que se vendera al menor</small>
						</div>
						<div class="col-md-4 col-12 mb-3">
							<label for="tipo_unidad">Tipo de unidad</label><br>
							<select name="tipo_unidad" id="tipo_unidad" class="selectpicker border form-control" data-width="100%">
								<option disabled>Selecciona</option>
								<option value="bulto">Bulto</option>
								<option selected value="caja">Caja</option>
								<option value="saco">Saco</option>
								<option value="granel">Granel</option>
							</select>
							<small class="text-muted text-help">Tipo de unidad comprada al mayor</small>
						</div>
						
						<div class="col-md-4 col-12">
							<label for="cant_prod">Cantidad por unidad</label>
							<input type="number" min="0" class="form-control" name="cant_prod" id="cant_prod" required>
							<small class="text-muted text-help">Cantidad de productos por tipo de unidad</small>
						</div>
						{{-- <div class="col-md-3 col-12">
							<label for="whole_sale_quantity">Cantidad de venta al mayor</label>
							<input type="number" class="form-control" name="whole_sale_quantity" id="whole_sale_quantity" required>
						</div> --}}
					</div>

					<!--<div class="form-row mb-4">
						<div class="col-md-4 col-12">
							<label for="cant_prod_um">Cantidad por unidad de medida</label>
							<input type="number" class="form-control" name="cant_prod_um" id="cant_prod_um" required>
						</div>
						<div class="col-md-4 col-12">
							<label for="unidad_medida">Unidad de medida</label><br>
							<select name="unidad_medida" id="unidad_medida" class="selectpicker border form-control" data-width="100%">
								<option disabled selected>Selecciona</option>
								<option value="KG">Kilos</option>
								<option value="L">Litros</option>
							</select>
						</div>

					</div>-->

					<div class="form-row mb-4">
						<div class="col-8">
							<label for="description">Descripción del producto</label>
							<textarea name="description" id="description" rows="3" class="form-control"></textarea>
						</div>
						<div class="col-4 text-right">
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
						<div class="col-md-4 col-12 mb-3">
							<label for="cantidad">Cantidad</label>
							<input type="number" min="0" class="form-control" name="cantidad" id="cantidad_edit" required>
							<small class="text-muted text-help">Cantidad comprada al mayor</small>
						</div>
						<div class="col-md-4 col-12 mb-3">
							<label for="tipo_unidad">Tipo de unidad</label><br>
							<select name="tipo_unidad" id="tipo_unidad_edit" class="selectpicker border form-control" data-width="100%">
								<option disabled selected>Selecciona</option>
								<option value="bulto">Bulto</option>
								<option value="caja">Caja</option>
								<option value="saco">Saco</option>
							</select>
							<small class="text-muted text-help">Tipo de unidad comprada al mayor</small>
						</div>
						<div class="col-md-4 col-12">
							<label for="cant_prod">Cantidad por unidad</label>
							<input type="number" min="0" class="form-control" name="cant_prod" id="cant_prod_edit" required>
							<small class="text-muted text-help">Cantidad de productos por tipo de unidad</small>
						</div>
					</div>

					<div class="form-row mb-4">
						<div class="col-12">
							<label for="description">Descripción del producto</label>
							<textarea name="description" id="description_edit" rows="3" class="form-control"></textarea>
						</div>
						<!--<div class="col-4 text-right">
							<h5>Cantidad Total</h5>
							<p class="lead cantidad_producto">-</p>
						</div>-->
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
		})

		$('.editProduct').click(function(e){

			let id = $(this).data('id')

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

				$('#cant_prod_edit').val(response.qty_per_unit)

				$('#description_edit').val(response.description)

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
		})

	})
		//MODAL PARA SUMAR CANTIDADES DE PRODUCTOS
		function sumar(id){
			console.log($('#cantidad-sumar-'+id).val())
			
			$.ajax({type: 'PUT', url: `/sumar-inventory/${id}`, data: {cantidad: $('#cantidad-sumar-'+id).val()}})
				.done((res) => {
					console.log(res)
				
					$('#total-productos-'+id).text(res);
					
				})
				.catch((err) => {
					toastr.error('Ha ocurrido un error')
					console.error(err)
				})
			

			$('#modal-sumar-'+id).modal('hide');
		}

		//MODAL PARA RESTAR CANTIDADES DE PRODUCTOS
		function restar(id){
			console.log($('#cantidad-sumar-'+id).val())
			
			$.ajax({type: 'PUT', url: `/restar-inventory/${id}`, data: {cantidad: $('#cantidad-restar-'+id).val()}})
				.done((res) => {
					console.log(res)
				
					$('#total-productos-'+id).text(res);
					
				})
				.catch((err) => {
					toastr.error('Ha ocurrido un error')
					console.error(err)
				})
			

			$('#modal-restar-'+id).modal('hide');
		}

</script>
@endpush