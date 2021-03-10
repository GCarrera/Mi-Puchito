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

	<button class="btn btn-primary btn-lg rounded-circle" onclick="showMarcar()" style="position: fixed; bottom: 30px; right: 30px">
		<i class="fas fa-plus"></i>
	</button>

	<div class="row mb-5">
		<div class="col">
			<div class="card shadow-sm">
				<div class="card-header">
					<h4>Costos</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">

						<table class="table table-sm table-hover table-bordered text-center" id="costos-table">
							<thead>
								<tr>
									<th class="small">PRODUCTO</th>
									<th class="small">COSTO</th>
									<th class="small">PRECIO AL MAYOR</th>
									<th class="small">PRECIO AL MENOR</th>
									<th class="text-center small">ACCIONES</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($productos as $pro)
									<tr>
										<td class="small">{{ $pro->inventory->product_name }}</td>
										<td class="small">{{number_format($pro->cost*$dolar->price, 2, ',', '.')  }}</td>

										<td>
											<span class="badge badge-pill badge-secondary" data-toggle="tooltip" data-html="true" data-title="Margen de Ganancia: <span class='badge badge-primary'>{{ $pro->wholesale_margin_gain }}%</span><br>Precio en $: <span class='badge badge-primary'>{{number_format($pro->wholesale_total_individual_price, 3, ',', '.')}}</span>">
												{{ number_format($pro->wholesale_total_individual_price*$dolar->price, 3, ',', '.') }} BsS
											</span>
										</td>

										<td>
											<span class="badge badge-pill badge-secondary" data-toggle="tooltip" data-html="true" data-title="Margen de Ganancia: <span class='badge badge-primary'>{{ $pro->retail_margin_gain }}%</span><br>Precio en $: <span class='badge badge-primary'>{{number_format($pro->retail_total_price, 3, ',', '.')}}</span>">
												{{ number_format($pro->retail_total_price*$dolar->price, 3, ',', '.') }} BsS
											</span>
										</td>
										<td class="text-center">
											<button class="btn btn-primary btn-md" onclick='showInfo({{ $pro->id }})'>
												<i class="fas fa-info-circle" data-toggle="tooltip" data-title="Detalles"></i>
											</button>
											<button class="btn btn-success btn-md" onclick='showEdit({{ $pro->id }})'>
												<i class="fas fa-edit" data-toggle="tooltip" data-title="Editar"></i>
											</button>
										</td>
									</tr>

								@empty
									<tr>
										<td class="text-center" colspan="7">No hay productos marcados</td>
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
<!-- Modal marcar precio producto -->
<div class="modal fade" id="marcarPrecio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Marcado de precios</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/products" method="post" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">

					<input type="hidden" class="retail_total_price" name="retail_total_price">
					<input type="hidden" class="wholesale_total_individual_price" name="wholesale_total_individual_price">
					<input type="hidden" class="wholesale_total_packet_price" name="wholesale_total_packet_price">
					<input type="hidden" class="wholesale_packet_price" name="wholesale_packet_price">

					<input type="hidden" class="retail_iva_amount" name="retail_iva_amount" value="0.00">
					<input type="hidden" class="wholesale_iva_amount" name="wholesale_iva_amount" value="0.00">

					<input type="hidden" class="producto_cantidad_total">
					<input type="hidden" class="producto_cantidad_mayor">
					<input type="hidden" id="qty_per_unit_val">

					<p class="small mb-3"><i class="fas fa-info-circle mr-2"></i>Todos los campos son requeridos</p>

					<div class="form-row mb-4 padre1">
						<div class="col-12 col-md-6 mb-2 primercol">
							<label for="product">Productos sin marcar</label><br>
							<select name="product" id="product" class="selectpicker border form-control product" data-live-search="true" data-width="100%" required>
								<option disabled selected>Selecciona un producto</option>
								@foreach ($inventario as $p)
									<option value="{{ $p->id }}">{{ $p->product_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-12 col-md-3 mb-2">
							<label for="cost">Costo total del producto</label>
							<input type="text" pattern="^[0-9]+([.][0-9]+)?$" onkeyup='calcularPrecioModalPrecioMarcar(this)' class="form-control costo" name="cost" id="costMarcar" required>
						</div>
						<div class="col-12 col-md-3">
							<label for="iva_percent" class="d-none">Tipo de I.V.A</label><br>
							<select name="iva_percent" id="iva_percent" class="selectp	icker border form-control iva d-none" data-width="100%" required>
								<option value="24">24%</option>
								<option value="20">20%</option>
								<option value="16">16%</option>
								<option value="8">8%</option>
								<option selected value="0">0%</option>
							</select>
						</div>
					</div>

					<div class="form-row mb-4">
						<div class="col-md-6 col-12">
							<div class="row mb-4">
								<div class="col-12 col-md-6 mb-2">
									<label for="wholesale_margin_gain">Ganancia al mayor (%)</label>
									<input type="number" onkeyup='calcularPrecioModalMayorMarcar(this)' onchange='calcularPrecioModalMayorMarcar(this)' maxlength="2" class="form-control ganancia_al_mayor" id="wholesale_margin_gain_marcar" name="wholesale_margin_gain" required>
								</div>
								<div class="col-12 col-md-6">
									<label for="retail_margin_gain">Ganancia al menor (%)</label>
									<input type="number" onkeyup='calcularPrecioModalMenorMarcar(this)' onchange='calcularPrecioModalMenorMarcar(this)' maxlength="2" class="form-control ganancia_al_menor" id="retail_margin_gain_marcar" name="retail_margin_gain" required>
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-12">

									<button class="d-none calcular" type="button"><i class="fas fa-calculator mr-2"></i></button>

								</div>
							</div>
							<div class="row mb-4">
								<div class="col-6">
									<div class="text-right">
										<h5 class="mb-4">Precio al mayor</h5>
										TOT: <span class="precio font-weight-light total_amount_wholesale_price" id="totalMayorMarcar">0</span> Bs</span><br>
									</div>
								</div>

								<div class="col-6">
									<div>
										<h5 class="mb-4">Precio al menor</h5>
										TOT: <span class="precio font-weight-light total_amount_retail_price" id="totalMenorMarcar">0</span> Bs<br>
									</div>
								</div>

							</div>
						</div>
						<div class="col-md-6 col-12 text-center">
							<label>Imágen del producto</label><br>
							<div class="file-input-wrapper">
								<img class="img-fluid img-thumbnail shadow" style="height: 200px; display: none" id="foto">
								<p id="image_name" class="mt-3 mb-1"></p>
								<p id="image_weigth" class="mb-3"></p>

								<p id="imgerror" class="text-danger" style="display: none;"></p>
								<button id="clearbtn" type="button" class="btn btn-primary" style="display: none"><i class="fas fa-trash mr-2"></i>Limpiar</button>
								<label for="fileinput" class="btn btn-primary"><i class="fas fa-folder-open mr-2"></i>Buscar</label>
								<input id="fileinput" id="fileinput" name="fileinput" type="file" accept="image/*">
							</div>

							<div>
								<p>¿Esta en oferta el producto?</p>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="oferta" id="oferta1" value="1">
									<label class="form-check-label" for="oferta1">
									Si
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="oferta" id="oferta2" value="0" checked>
									<label class="form-check-label" for="oferta2">
									No
									</label>
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
					<button type="submit" id="sendform" class="btn btn-primary"><i class="fas fa-shopping-cart mr-2"></i>A vender</button>
				</div>
			</form>
		</div>
	</div>
</div>

{{-- Modal ver info de producto marcdo --}}
<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="almacen" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="almacen"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal_loader py-5" id="modal_loader">
				<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status"></div>
			</div>

			<div class="modal-body">

				<h5 class="mb-3">Imagen del producto</h5>
				<div class="row mb-4">
					<div class="col col-md-4">
						<img id="imageproduct" class="img-thumbnail img-fluid shadow-sm" alt="Cargando" style="width: 200px;">
					</div>
					<div class="col-md-8">
						<h5 class="mb-3">Inventario</h5>
						<div class="row mb-1">
							<p class="col-6 font-weight-bold"><i class="fas fa-box-open mr-2"></i>Producto:</p>
							<p class="col-6 font-weight-light" id="nombre_producto">...</p>
						</div>

						<div class="row mb-1">
							<p class="col-6 font-weight-bold"><i class="fas fa-clipboard-list mr-2"></i>Categoria:</p>
							<p class="col-6 font-weight-light" id="categoria_producto">...</p>
						</div>

						<div class="row mb-1">
							<p class="col-6 font-weight-bold"><i class="fas fa-boxes mr-2"></i>Cantidad por empaque:</p>
							<p class="col-6 font-weight-light" id="cantidadEmpaque">...</p>
						</div>

						<div class="row mb-1">
							<p class="col-6 font-weight-bold"><i class="fas fa-clock mr-2"></i>Ultima Modificación:</p>
							<p class="col-6 font-weight-light" id="ultimaActualizacion">...</p>
						</div>

						<div class="row mb-1">

							<label class="col-12 font-weight-light" id="oferta"></label>
						</div>
					</div>
				</div>

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
			</div>
		</div>
	</div>
</div>


<!-- Modal EDITAR precio producto -->
<div class="modal fade" id="editarPrecio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Editar precio del producto</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal_loader py-5" id="modal_loader_edit">
				<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status"></div>
			</div>

			<form id="editarForm" method="post" enctype="multipart/form-data">
				@csrf
				@method('put')
				<div class="modal-body">

					<input type="hidden" class="retail_total_price" name="retail_total_price">
					<input type="hidden" class="wholesale_total_individual_price" name="wholesale_total_individual_price">
					<input type="hidden" class="wholesale_total_packet_price" name="wholesale_total_packet_price">
					<input type="hidden" class="wholesale_packet_price" name="wholesale_packet_price">

					<input type="hidden" class="retail_iva_amount" name="retail_iva_amount" value="0.00">
					<input type="hidden" class="wholesale_iva_amount" name="wholesale_iva_amount" value="0.00">

					<input type="hidden" class="qty_per_unit_val">
					<input type="hidden" class="producto_cantidad_mayor">

					<p class="small mb-3"><i class="fas fa-info-circle mr-2"></i>Todos los campos son requeridos</p>

					<div class="form-row mb-4">
						<div class="col-12 col-md-6 mb-2">
							<label for="product">Productos sin marcar</label><br>
							<select name="product" id="product_edit" class="custom-select border form-control product">
								<option disabled selected>Selecciona un producto</option>
							</select>
						</div>
						<div class="col-12 col-md-3 mb-2">
							<label for="cost_edit">Costo</label>
							<input type="text" pattern="^[0-9]+([.][0-9]+)?$" class="form-control costo" name="cost" onkeyup='calcularPrecioModalPrecio(this)' id="cost_edit" required>
						</div>
						<div class="col-12 col-md-3">
							<label for="iva_percent" class="d-none">Tipo de I.V.A</label><br>
							<select name="iva_percent" onchange="calcularPrecio()" id="iva_percent_edit" class="border custom-select form-control iva d-none" required>
								<option value="24">24%</option>
								<option value="20">20%</option>
								<option value="16">16%</option>
								<option value="8">8%</option>
								<option selected value="0">0%</option>
							</select>
						</div>
					</div>

					<div class="form-row mb-4">
						<div class="col-md-6 col-12">
							<div class="row mb-4">
								<div class="col-12 col-md-4 mb-2">
									<label for="wholesale_margin_gain">Ganancia al mayor (%)</label>
									<input type="number" class="form-control ganancia_al_mayor" id="wholesale_margin_gain_edit" onkeyup='calcularPrecioModalMayor(this)' onchange='calcularPrecioModalMayor(this)' name="wholesale_margin_gain" required>
								</div>
								<div class="col-12 col-md-4">
									<label for="retail_margin_gain">Ganancia al menor (%)</label>
									<input type="number" class="form-control ganancia_al_menor" id="retail_margin_gain_edit" onkeyup='calcularPrecioModalMenor(this)' onchange='calcularPrecioModalMenor(this)' name="retail_margin_gain" required>
								</div>

							</div>
							<div class="row mb-4">
								<div class="col-12">
									<button class="d-none calcular" type="button"><i class="fas fa-calculator mr-2"></i>Calcular nuevos precios</button>
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-6">
									<div>
										<h5 class="mb-4">Precio al mayor</h5>
										TOT: <span class="precio font-weight-light total_amount_wholesale_price" id="totalMayor">0,00</span> Bs</span><br>
									</div>
								</div>
								<div class="col-6">
									<div>
										<h5 class="mb-4">Precio al menor</h5>
										TOT: <span class="precio font-weight-light total_amount_retail_price" id="totalMenor">0,00</span> Bs<br>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6 col-12 text-center">
							<label>Imágen del producto</label><br>
							<div class="file-input-wrapper">
								<img class="img-fluid img-thumbnail shadow" style="height: 200px;" id="fotoeditold">
								<img class="img-fluid img-thumbnail shadow" style="height: 200px; display: none" id="fotoedit">
								<p id="image_nameedit" class="mt-3 mb-1"></p>
								<p id="image_weigthedit" class="mb-3"></p>

								<p id="imgerroredit" class="text-danger" style="display: none;"></p>
								<button id="clearbtnedit" type="button" class="btn btn-primary" style="display: none"><i class="fas fa-trash mr-2"></i>Limpiar</button>
								<label for="fileinputedit" class="btn btn-primary"><i class="fas fa-folder-open mr-2"></i>Buscar</label>
								<input id="fileinputedit" name="fileinputedit" type="file" accept="image/*" class="d-none">
							</div>
							<div class="">
								<p>¿Esta en oferta el producto?</p>
								<div class="form-check form-check-inline">
									<input class="form-check-input ofertaEdit" type="radio" name="oferta" id="ofertaEdit1" value="1">
									<label class="form-check-label" for="oferta1">
									Si
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input ofertaEdit" type="radio" name="oferta" id="ofertaEdit2" value="0">
									<label class="form-check-label" for="oferta2">
									No
									</label>
								</div>
							</div>
						</div>

					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
					<button type="submit" id="sendformedit" class="btn btn-primary"><i class="fas fa-edit mr-2"></i>Editar</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>

	function showEdit(id) {
		$('#editarPrecio').modal('show');

		$('#editarForm').attr('action', `/products/${id}`)

		$.get({
			url : `/products/${id}`,
			beforeSend(){
				$('#modal_loader_edit').show()
			}
		})
		.done((data) => {
			console.log("data del producto:");
			console.log(data);

			$('#product_edit').val(data.id)
			$('#product_edit').html(`<option selected>${data.inventory.product_name}</option>`)

			$('#cost_edit').val(data.cost)

			$('#wholesale_margin_gain_edit').val(data.wholesale_margin_gain)

			$('#retail_margin_gain_edit').val(data.retail_margin_gain)
			//ESTABLECEMOS EL CHECK DE LAS OFERTAS
			if (data.oferta == 1) {

				$('#ofertaEdit2').removeAttr('checked');
				$('#ofertaEdit1').attr('checked', true);

			}else{

				$('#ofertaEdit1').removeAttr('checked', true);
				$('#ofertaEdit2').attr('checked', true);
				$('#fotoeditold').attr('src', '../storage/app/public/'+data.image);

			}

			var costo = data.cost;
			var gMayor = data.wholesale_margin_gain;
			result_porcentaje  = (parseFloat(costo)*gMayor)/100;
			result_porcentaje = result_porcentaje.toFixed(3);
			precio_mayor_total = parseFloat(costo)+parseFloat(result_porcentaje);
			$('.wholesale_total_individual_price').val(precio_mayor_total.toFixed(3));
			var total2 = parseFloat(precio_mayor_total).toFixed(3) * data.inventory.qty_per_unit;
			$('.wholesale_total_packet_price').val(total2.toFixed(3));
			$('.qty_per_unit_val').val(data.inventory.qty_per_unit);
			$('.wholesale_packet_price').val(total2.toFixed(3));
			$('#totalMayor').text(new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 3}).format(precio_mayor_total));

			var gMenor = data.retail_margin_gain;
			result_porcentaje  = (parseFloat(costo)*gMenor)/100;
			result_porcentaje = result_porcentaje.toFixed(3);
			precio_menor_total = parseFloat(costo)+parseFloat(result_porcentaje);
			$('.retail_total_price').val(precio_menor_total.toFixed(3));

			$('#totalMenor').text(new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 3}).format(precio_menor_total));

			$('#modal_loader_edit').fadeOut();

		})
		.fail((err)=> {
			console.log(err)
			toastr.error('Ha ocurrido un error.')
		})
	}

	function showInfo(id) {
		$('#detailModal').modal('show');

		$.get({
			url : `/products/${id}`,
			beforeSend(){
				$('#modal_loader').show()
			}
		})
		.done((data) => {

			console.log(data);

			$('#imageproduct').attr('src', `/storage/app/public/${data.image}`)
			$('#nombre_producto').text(`${data.inventory.product_name}`)
			$('#almacen').text(`Almacen: ${data.inventory.warehouse.name}`)
			$('#categoria_producto').text(`${data.inventory.category.name}`)
			$('#cantidadEmpaque').text(`${data.inventory.quantity} ${data.inventory.unit_type} de ${data.inventory.qty_per_unit} productos`)
			$('#ultimaActualizacion').text(`${data.updated_at}`)
			if (data.oferta == 1) {
				$('#oferta').text(`El producto se encuentra en oferta`)
				$('#oferta').addClass('text-success')
				$('#oferta').removeClass('text-danger')
			}else{
				$('#oferta').text(`EL producto no esta en oferta`)
				$('#oferta').addClass('text-danger')
				$('#oferta').removeClass('text-success')
			}

			$('#modal_loader').fadeOut()
		})
		.fail((err)=> {
			console.log(err)
			toastr.error('Ha ocurrido un error.')
		})

	}

	function showMarcar() {
		$('#marcarPrecio').modal('show');
	}

function calcularPrecioModalPrecio(e) {
	console.log('calcularPrecioModalPrecio');
	var costo = e.value;
	var qty_per_unit = $('.qty_per_unit_val').val();

	if ($("#wholesale_margin_gain_edit").val() != null) {
		var gMayor = $("#wholesale_margin_gain_edit").val();
		result_porcentaje  = (parseFloat(costo)*gMayor)/100;
		result_porcentaje = result_porcentaje.toFixed(3);
		precio_mayor_total = parseFloat(costo)+parseFloat(result_porcentaje);
		$('.wholesale_total_individual_price').val(precio_mayor_total.toFixed(3));
		var total2 = parseFloat(precio_mayor_total).toFixed(3) * qty_per_unit;
		$('.wholesale_total_packet_price').val(total2.toFixed(3));
		$('.wholesale_packet_price').val(total2.toFixed(3));
		$('#totalMayor').text(new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 3}).format(precio_mayor_total));
	}
	if ($("#retail_margin_gain_edit").val() != null) {
		var gMenor = $("#retail_margin_gain_edit").val();
		result_porcentaje  = (parseFloat(costo)*gMenor)/100;
		result_porcentaje = result_porcentaje.toFixed(3);
		precio_menor_total = parseFloat(costo)+parseFloat(result_porcentaje);
		$('.retail_total_price').val(precio_menor_total.toFixed(3));
		$('#totalMenor').text(new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 3}).format(precio_menor_total));
	}
}

function calcularPrecioModalPrecioMarcar(e) {
	console.log('calcularPrecioModalPrecioMarcar');
	var costo = e.value;
	var qty_per_unit = $('#qty_per_unit_val_marcar').val();

	if ($("#wholesale_margin_gain_marcar").val() != null) {
		var gMayor = $("#wholesale_margin_gain_marcar").val();
		result_porcentaje  = (parseFloat(costo)*gMayor)/100;
		result_porcentaje = result_porcentaje.toFixed(3);
		precio_mayor_total = parseFloat(costo)+parseFloat(result_porcentaje);
		$('.wholesale_total_individual_price').val(precio_mayor_total.toFixed(3));
		var total2 = parseFloat(precio_mayor_total).toFixed(3) * qty_per_unit;
		$('.wholesale_total_packet_price').val(total2.toFixed(3));
		$('.wholesale_packet_price').val(total2.toFixed(3));
		$('#totalMayorMarcar').text(new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 3}).format(precio_mayor_total));
	}
	if ($("#retail_margin_gain_edit").val() != null) {
		var gMenor = $("#retail_margin_gain_edit").val();
		result_porcentaje  = (parseFloat(costo)*gMenor)/100;
		result_porcentaje = result_porcentaje.toFixed(3);
		precio_menor_total = parseFloat(costo)+parseFloat(result_porcentaje);
		$('.retail_total_price').val(precio_menor_total.toFixed(3));
		$('#totalMenorMarcar').text(new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 3}).format(precio_menor_total));
	}
}

function calcularPrecioModalMayor(e) {
	console.log('calcularPrecioModalMayor');
	var gMayor = e.value;
	var qty_per_unit = $('.qty_per_unit_val').val();
	if ($("#cost_edit").val() != null) {
		var costo = $("#cost_edit").val();
		result_porcentaje = (parseFloat(costo)*gMayor)/100;
		result_porcentaje = result_porcentaje.toFixed(3);
		precio_menor_total = parseFloat(costo)+parseFloat(result_porcentaje);
		$('.wholesale_total_individual_price').val(precio_menor_total.toFixed(3));
		var total2 = parseFloat(precio_menor_total).toFixed(3) * qty_per_unit;
		$('.wholesale_total_packet_price').val(total2.toFixed(3));
		$('.wholesale_packet_price').val(total2.toFixed(3));
		$('#totalMayor').text(new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 3}).format(precio_menor_total));
	}
}

function calcularPrecioModalMayorMarcar(e) {
	console.log('calcularPrecioModalMayorMarcar');
	var gMayor = e.value;
	var qty_per_unit = $('#qty_per_unit_val').val();
	if ($("#costMarcar").val() != null) {
		var costo = $("#costMarcar").val();
		result_porcentaje = (parseFloat(costo)*gMayor)/100;
		result_porcentaje = result_porcentaje.toFixed(3);
		precio_menor_total = parseFloat(costo)+parseFloat(result_porcentaje);
		$('.wholesale_total_individual_price').val(precio_menor_total.toFixed(3));
		var total2 = parseFloat(precio_menor_total).toFixed(3) * qty_per_unit;
		$('.wholesale_total_packet_price').val(total2.toFixed(3));
		$('.wholesale_packet_price').val(total2.toFixed(3));
		$('#totalMayorMarcar').text(new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 3}).format(precio_menor_total));
	}
}

function calcularPrecioModalMenor(e) {
	console.log('calcularPrecioModalMenor');
	var gMenor = e.value;
	var qty_per_unit = $('.qty_per_unit_val').val();
	if ($("#cost_edit").val() != null) {
		var costo = $("#cost_edit").val();
		var gMayor = $("#wholesale_margin_gain_edit").val()
		result_porcentaje  = (parseFloat(costo)*gMenor)/100;
		result_porcentaje = result_porcentaje.toFixed(3);
		precio_menor_total = parseFloat(costo)+parseFloat(result_porcentaje);
		result_porcentaje_mayor = (parseFloat(costo)*gMayor)/100;
		result_porcentaje_mayor = result_porcentaje_mayor.toFixed(3);
		precio_mayor_total = parseFloat(costo)+parseFloat(result_porcentaje_mayor);
		$('.wholesale_total_individual_price').val(precio_mayor_total.toFixed(3));
		$('.retail_total_price').val(precio_menor_total.toFixed(3));
		var total2 = parseFloat(precio_mayor_total).toFixed(3) * qty_per_unit;
		$('.wholesale_total_packet_price').val(total2.toFixed(3));
		$('.wholesale_packet_price').val(total2.toFixed(3));
		$('#totalMenor').text(new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 3}).format(precio_menor_total));
	}
}

function calcularPrecioModalMenorMarcar(e) {
	console.log('calcularPrecioModalMenorMarcar');
	var gMenor = e.value;
	var qty_per_unit = $('.qty_per_unit_val').val();
	if ($("#costMarcar").val() != null) {
		var costo = $("#costMarcar").val();
		var gMayor = $("#wholesale_margin_gain_marcar").val()
		result_porcentaje  = (parseFloat(costo)*gMenor)/100;
		result_porcentaje = result_porcentaje.toFixed(3);
		precio_menor_total = parseFloat(costo)+parseFloat(result_porcentaje);
		result_porcentaje_mayor = (parseFloat(costo)*gMayor)/100;
		result_porcentaje_mayor = result_porcentaje_mayor.toFixed(3);
		precio_mayor_total = parseFloat(costo)+parseFloat(result_porcentaje_mayor);
		$('.wholesale_total_individual_price').val(precio_mayor_total.toFixed(3));
		$('.retail_total_price').val(precio_menor_total.toFixed(3));
		var total2 = parseFloat(precio_mayor_total).toFixed(3) * qty_per_unit;
		$('.wholesale_total_packet_price').val(total2.toFixed(3));
		$('.wholesale_packet_price').val(total2.toFixed(3));
		$('#totalMenorMarcar').text(new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 3}).format(precio_menor_total));
	}
}

$(document).ready( function () {
	$('#costos-table').DataTable({
		"language": {
			"search": "Buscar:",
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

	function calcularPrecio() {
		$('.calcular').trigger('click')
	}

	$(() => {

		$('#fileinputedit').change((e) => {

			var input = e.target;
			// imagen de preview
			let file   = e.target.files[0];
			let reader = new FileReader();

			let filesize = file.size / 1024

			// validaciones del archivo
			if (filesize > 15000) {
				$('#imgerroredit').text('La imagen excede los 15000kb permitidos.')
				$('#imgerroredit').show()
				return
			}

			let allowed_ext = ['png', 'jpeg', 'gif']
			let ext_length = allowed_ext.length

			for (let i = 0; i < ext_length; i++){

				if (!file.type.includes(allowed_ext[i])) {
					if (ext_length - 1 == i) {
						$('#imgerroredit').text('Este formato no está permitido.')
						$('#imgerroredit').show()

						return
					}
				}
				else {
					break
				}
			}

			reader.onload = function(){
				$('#imgerroredit').hide();
				$('#fotoeditold').hide();
				$('#fotoedit').show();
				$('#clearbtnedit').show();
		    var dataURL = reader.result;
		    var output = document.getElementById('fotoedit');
		    output.src = dataURL;
				$('#image_nameedit').text(file.name)
				$('#image_weigthedit').text(`${ filesize.toFixed(2) } kb`)
		  };
		  reader.readAsDataURL(input.files[0]);

			$('#clearbtnedit').click(() => {
				$('#imgerroredit').text('')
				$('#fileinputedit').val('')
				$('#fotoedit').hide()
				$('#image_nameedit').text('')
				$('#image_weigthedit').text('')
				$('#clearbtnedit').hide()
			})

			reader.readAsDataURL(file);
		});

		$('#fileinput').change((e) => {

			var input = e.target;
			// imagen de preview
			let file   = e.target.files[0];
			let reader = new FileReader();

			let filesize = file.size / 1024

			// validaciones del archivo
			if (filesize > 15000) {
				$('#imgerror').text('La imagen excede los 15000kb permitidos.')
				$('#imgerror').show()
				return
			}

			let allowed_ext = ['png', 'jpeg', 'gif']
			let ext_length = allowed_ext.length

			for (let i = 0; i < ext_length; i++){

				if (!file.type.includes(allowed_ext[i])) {
					if (ext_length - 1 == i) {
						$('#imgerror').text('Este formato no está permitido.')
						$('#imgerror').show()

						return
					}
				}
				else {
					break
				}
			}

			reader.onload = function(){
				$('#imgerror').hide();
				$('#foto').show();
				$('#clearbtn').show();
		    var dataURL = reader.result;
		    var output = document.getElementById('foto');
		    output.src = dataURL;
				$('#image_name').text(file.name)
				$('#image_weigth').text(`${ filesize.toFixed(2) } kb`)
		  };
		  reader.readAsDataURL(input.files[0]);

			$('#clearbtn').click(() => {
				$('#imgerror').text('')
				$('#fileinput').val('')
				$('#foto').hide()
				$('#image_name').text('')
				$('#image_weigth').text('')
				$('#clearbtn').hide()
			})

			reader.readAsDataURL(file);
		});

		let form = {
			inventoryid: null,
			costo: '0.00',
			iva: '0',
			ganancia_al_mayor: '0.00',
			ganancia_al_menor: '0.00'
		}

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

		//-------------- data binding -------------------

		$('.product').change((e) => {
			form.inventoryid = e.target.value
		})
		$('.costo').keyup((e) => {
			form.costo = e.target.value
		})
		$('.ganancia_al_mayor').on('change keyup', (e) => {
			form.ganancia_al_mayor = e.target.value
		})
		$('.ganancia_al_menor').on('change keyup', (e) => {
			form.ganancia_al_menor = e.target.value
		})

		// ---------------- end data binding -------------

	})
</script>
@endpush
