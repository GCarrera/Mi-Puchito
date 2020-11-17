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

	<button class="btn btn-primary btn-lg rounded-circle btn-open-modal" data-target="#marcarPrecio" data-toggle="modal" style="position: fixed; bottom: 30px; right: 30px">
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
				<div class="card-header">
					<h4>Costos</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive">

						<table class="table table-sm table-hover table-bordered text-center">
							<thead>
								<tr>
									<th>PRODUCTO</th>
									<th>COSTO</th>
									<!-- <th>I.V.A (%)</th> -->				
									<th>PRECIO AL MAYOR (Bs)</th>
									<th>PRECIO AL MENOR (Bs)</th>
									<th class="text-center">ACCIONES</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($productos as $pro)
									<tr>
										<td class="small">{{ $pro->inventory->product_name }}</td>
										<td>{{number_format($pro->cost, 2, ',', '.')  }}</td>
									
										<td>{{ number_format($pro->wholesale_total_individual_price, 2, ',', '.') }}-{{ $pro->wholesale_margin_gain }}%-{{number_format($pro->wholesale_total_individual_price / $dolar->price, 2, ',', '.')}}$</td>
									
										<td>{{ number_format($pro->retail_total_price, 2, ',', '.') }}-{{ $pro->retail_margin_gain }}%-{{number_format($pro->retail_total_price / $dolar->price, 2, ',', '.')}}$</td>
										<td class="text-center">
											<button class="btn btn-primary btn-md btninfo" data-toggle="modal" data-target="#detailModal" data-id="{{ $pro->id }}">
												<i class="fas fa-info-circle" data-toggle="tooltip" data-title="Detalles"></i>
											</button>
											<button class="btn btn-warning btn-md btnedit" data-toggle="modal" data-target="#editarPrecio" data-id="{{ $pro->id }}">
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

						<div class="float-right">
							<p >{{$productos->render()}}</p>
						</div>
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

					<input type="hidden" class="retail_iva_amount" name="retail_iva_amount">
					<input type="hidden" class="wholesale_iva_amount" name="wholesale_iva_amount">

					<input type="hidden" class="producto_cantidad_total">
					<input type="hidden" class="producto_cantidad_mayor">

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
							<input type="text" onkeypress="soloNumeros(event)" class="form-control costo" name="cost" id="cost" onkeyup="calcularPrecio()" required>
						</div>
						<div class="col-12 col-md-3">
							<label for="iva_percent">Tipo de I.V.A</label><br>
							<select name="iva_percent" id="iva_percent" class="selectp	icker border form-control iva" data-width="100%" required>
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
									<input type="text" onkeypress="soloNumeros(event)" onkeyup="calcularPrecio()" maxlength="2" class="form-control ganancia_al_mayor" id="wholesale_margin_gain" name="wholesale_margin_gain" required>
								</div>
								<div class="col-12 col-md-6">
									<label for="retail_margin_gain">Ganancia al menor (%)</label>
									<input type="text" onkeypress="soloNumeros(event)" onkeyup="calcularPrecio()" maxlength="2" class="form-control ganancia_al_menor" id="retail_margin_gain" name="retail_margin_gain" required>
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-12">
									
									<button class="d-none calcular" type="button"><i class="fas fa-calculator mr-2"></i></button>
								
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-6">
									<div>
										<h5 class="mb-4">Precio al menor</h5>
										PVP: <span class="precio font-weight-light total_retail_price">0</span> Bs<br>
										IVA: <span class="precio font-weight-light iva_retail_price">0</span> Bs<br>
										TOT: <span class="precio font-weight-light total_amount_retail_price">0</span> Bs<br>
									</div>
								</div>
								<div class="col-6">
									<div class="text-right">
										<h5 class="mb-4">Precio al mayor</h5>
										PVP: <span class="precio font-weight-light total_wholesale_price">0</span> Bs<br>
										IVA: <span class="precio font-weight-light iva_wholesale_price">0</span> Bs<br>
										TOT: <span class="precio font-weight-light total_amount_wholesale_price">0</span> Bs</span><br>
										<!-- TOT2: <span class="precio font-weight-light" id="total2_amount_wholesale_price">0,00</span> Bs</span><br> -->
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
								<input id="fileinput" id="fileinput" name="fileinput" type="file" accept="image/*" required>
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
									<input class="form-check-input" type="radio" name="oferta" id="oferta2" value="0">
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
							
							<label class="col-12 font-weight-light" id="oferta"></label>
						</div>
					</div>
				</div>

				<!-- <h5 class="mb-3">Precios</h5>
				<div class="row mb-1">
					<p class="col-6 font-weight-bold"><i class="fas fa-chart-line mr-2"></i>I.V.A:</p>
					<p class="col-6 font-weight-light" id="iva">...</p>
				</div>

				<div class="row mb-1">
					<p class="col-6 font-weight-bold"><i class="fas fa-chart-bar mr-2"></i>Ganancia al menor:</p>
					<p class="col-6 font-weight-light" id="ganancia_al_menor">...</p>
				</div>

				<div class="row mb-1">
					<p class="col-6 font-weight-bold"><i class="fas fa-cash-register mr-2"></i>Precio al menor:</p>
					<p class="col-6 font-weight-light" id="precio_al_menor">...</p>
				</div>

				<div class="row mb-1">
					<p class="col-6 font-weight-bold"><i class="fas fa-chart-bar mr-2"></i>Ganancia al mayor:</p>
					<p class="col-6 font-weight-light" id="ganancia_al_mayor">...</p>
				</div>

				<div class="row mb-1">
					<p class="col-6 font-weight-bold"><i class="fas fa-cash-register mr-2"></i>Precio al mayor:</p>
					<p class="col-6 font-weight-light" id="precio_al_mayor">...</p>
				</div> -->

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
			</div>
		</div>
	</div>
</div>


<!-- Modal EDITAR precio producto -->
<div class="modal fade" id="editarPrecio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Editar precio del producto</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="editarForm" method="post" enctype="multipart/form-data">
				@csrf
				@method('put')
				<div class="modal-body">

					<input type="hidden" class="retail_total_price" name="retail_total_price">
					<input type="hidden" class="wholesale_total_individual_price" name="wholesale_total_individual_price">
					<input type="hidden" class="wholesale_total_packet_price" name="wholesale_total_packet_price">
					<input type="hidden" class="wholesale_packet_price" name="wholesale_packet_price">

					<input type="hidden" class="retail_iva_amount" name="retail_iva_amount">
					<input type="hidden" class="wholesale_iva_amount" name="wholesale_iva_amount">

					<input type="hidden" class="producto_cantidad_total">
					<input type="hidden" class="producto_cantidad_mayor">

					<p class="small mb-3"><i class="fas fa-info-circle mr-2"></i>Todos los campos son requeridos</p>

					<div class="form-row mb-4">
						<div class="col-12 col-md-6 mb-2">
							<label for="product">Productos sin marcar</label><br>
							<select name="product" id="product_edit" class="custom-select border form-control product">
								<option disabled selected>Selecciona un producto</option>
								<option value="{{ $p->id }}">{{ $p->product_name }}</option>
							</select>
						</div>
						<div class="col-12 col-md-3 mb-2">
							<label for="cost">Costo</label>
							<input type="text" pattern="^[0-9]+([,][0-9]+)?$" class="form-control costo" name="cost" id="cost_edit" required>
						</div>
						<div class="col-12 col-md-3">
							<label for="iva_percent">Tipo de I.V.A</label><br>
							<select name="iva_percent" onchange="calcularPrecio()" id="iva_percent_edit" class="border custom-select form-control iva" required>
								<option value="24">24%</option>
								<option value="20">20%</option>
								<option value="16">16%</option>
								<option value="8">8%</option>
								<option selected value="0">0%</option>
							</select>
						</div>
					</div>

					<div class="form-row mb-4">
						<div class="col-12">
							<div class="row mb-4">
								<div class="col-12 col-md-4 mb-2">
									<label for="wholesale_margin_gain">Ganancia al mayor (%)</label>
									<input type="number" class="form-control ganancia_al_mayor" id="wholesale_margin_gain_edit" name="wholesale_margin_gain" required>
								</div>
								<div class="col-12 col-md-4">
									<label for="retail_margin_gain">Ganancia al menor (%)</label>
									<input type="number" class="form-control ganancia_al_menor" id="retail_margin_gain_edit" name="retail_margin_gain" required>
								</div>

								<div class="col-md-4">
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
							<div class="row mb-4">
								<div class="col-12">
									<button class="d-none calcular" type="button"><i class="fas fa-calculator mr-2"></i>Calcular nuevos precios</button>
								</div>
							</div>
							<div class="row">
								<div class="col-6">
									<div>
										<h5 class="mb-4">Precio al menor</h5>
										PVP: <span class="precio font-weight-light total_retail_price">0,00</span> Bs<br>
										IVA: <span class="precio font-weight-light iva_retail_price">0,00</span> Bs<br>
										TOT: <span class="precio font-weight-light total_amount_retail_price">0,00</span> Bs<br>
									</div>
								</div>
								<div class="col-6">
									<div class="text-right">
										<h5 class="mb-4">Precio al mayor</h5>
										PVP: <span class="precio font-weight-light total_wholesale_price">0,00</span> Bs<br>
										IVA: <span class="precio font-weight-light iva_wholesale_price">0,00</span> Bs<br>
										TOT: <span class="precio font-weight-light total_amount_wholesale_price">0,00</span> Bs</span><br>
										<!-- TOT2: <span class="precio font-weight-light" id="total2_amount_wholesale_price">0,00</span> Bs</span><br> -->
									</div>
								</div>
							</div>
						</div>
						<!--<div class="col-md-6 col-12 text-center">
							<label>Imágen del producto</label><br>
							<div class="file-input-wrapper">
								<img class="img-fluid img-thumbnail shadow" style="height: 200px; display: none" id="foto">
								<p id="image_name" class="mt-3 mb-1"></p>
								<p id="image_weigth" class="mb-3"></p>

								<button id="clearbtn" type="button" class="btn btn-primary" style="display: none"><i class="fas fa-trash mr-2"></i>Limpiar</button>
								<label for="fileinput" class="btn btn-primary"><i class="fas fa-folder-open mr-2"></i>Buscar</label>
								<input id="fileinput" id="fileinput" name="fileinput" type="file" accept="image/*" required>
							</div>
						</div>-->
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

	function calcularPrecio() {
		$('.calcular').trigger('click')
	}

	$(() => {
		
		let form = {
			inventoryid: null,
			costo: '0.00',
			iva: '0',
			ganancia_al_mayor: '0.00',
			ganancia_al_menor: '0.00'
		}

		$('.btn-open-modal').on('click', function() {
			form.costo = '0.00'
			form.ganancia_al_mayor = '0.00'
			form.ganancia_al_menor = '0.00'
			$('#cost').val('')
			$('#wholesale_margin_gain').val('')
			$('#retail_margin_gain').val('')

			$('.calcular').trigger('click')
			$('#marcarPrecio').modal('show')
		})

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
		
		$('#fileinput').change((e) => {
			// $('#sendBtn').removeClass('d-none')

			// imagen de preview
			let file   = e.target.files[0];
			let reader = new FileReader();

			let filesize = file.size / 1024

			// validaciones del archivo
			if (filesize > 150) {
				$('#imgerror').text('La imagen excede los 150kb permitidos.')
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

			reader.onload = (ev) => {
				$('#imgerror').hide()
					
				$('#foto').show();
				$('#clearbtn').show();

				$('#foto').attr('data-src', ev.target.result);
				$('#image_name').text(file.name)
				$('#image_weigth').text(`${ filesize.toFixed(2) } kb`)
			}

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



		function calcular_precio(precio, margen_ganancia){
			// let precio_articulo   = costo / cantidad
			let result_porcentaje  = (precio / 100) * margen_ganancia

			return Number(precio + result_porcentaje)
		}

		function calcular_iva(iva, precio){
			let result_porcentaje = (precio / 100) * iva

			return Number(result_porcentaje)
		}

		

		$('.btninfo').click(function() {
			let id = $(this).data('id')

			$.get({
				url : `/products/${id}`,
				beforeSend(){
					$('#modal_loader').show()
				}
			})
			.done((data) => {

				$('#imageproduct').attr('src', `/storage/${data.image}`)
				$('#nombre_producto').text(`${data.inventory.product_name}`)
				// $('#descripcion_producto').text(`${data.inventory.description}`)
				$('#almacen').text(`Almacen: ${data.inventory.warehouse.name}`)
				$('#categoria_producto').text(`${data.inventory.category.name}`)
				// $('#empresa').text(`${data.inventory.enterprise.name}`)
				$('#cantidadEmpaque').text(`${data.inventory.quantity} ${data.inventory.unit_type} de ${data.inventory.qty_per_unit} productos`)
				if (data.oferta == 1) {
					$('#oferta').text(`El producto se encuentra en oferta`)
					$('#oferta').addClass('text-success')
					$('#oferta').removeClass('text-danger')
				}else{
					$('#oferta').text(`EL producto no esta en oferta`)
					$('#oferta').addClass('text-danger')
					$('#oferta').removeClass('text-success')
				}
				
				// $('#cantidad').text(`${data.inventory.total_qty_prod} productos`)
				// $('#iva').text(`${data.iva_percent}%`)
				// $('#ganancia_al_menor').text(`${data.retail_margin_gain}%`)
				// $('#precio_al_menor').text(`${new Intl.NumberFormat().format(data.retail_total_price)} Bs`)
				// $('#ganancia_al_mayor').text(`${data.wholesale_margin_gain}%`)
				// $('#precio_al_mayor').text(`${new Intl.NumberFormat().format(data.wholesale_total_individual_price)} Bs`)

				$('#modal_loader').fadeOut()
			})
			.fail((err)=> {
				console.log(err)
				toastr.error('Ha ocurrido un error.')
			})
		})


		//-------------- data binding -------------------


		$('.btnedit').click(function() {
			
			let id = $(this).data('id')

			$('#editarForm').attr('action', `/products/${id}`)

			$.get({
				url : `/products/${id}`,
				beforeSend(){
					$('#modal_loader').show()
				}
			})
			.done((data) => {

				$('#product_edit').val(data.id)
				$('#product_edit').html(`<option selected>${data.inventory.product_name}</option>`)
				form.inventoryid = data.id

				$('#cost_edit').val(data.cost)
				form.costo = data.cost

				$('#iva_percent_edit').val(data.iva_percent)
				$('#iva_percent_edit').change()
				form.iva = data.iva_percent

				$('#wholesale_margin_gain_edit').val(data.wholesale_margin_gain)
				form.ganancia_al_mayor = data.wholesale_margin_gain

				$('#retail_margin_gain_edit').val(data.retail_margin_gain)
				form.ganancia_al_menor = data.retail_margin_gain
				//ESTABLECEMOS EL CHECK DE LAS OFERTAS
				if (data.oferta == 1) {

					$('#ofertaEdit2').removeAttr('checked')
					$('#ofertaEdit1').attr('checked', true);
					
				}else{

					$('#ofertaEdit1').removeAttr('checked', true);
					$('#ofertaEdit2').attr('checked', true);
		;
				}

				$('#modal_loader').fadeOut()
			})
			.fail((err)=> {
				console.log(err)
				toastr.error('Ha ocurrido un error.')
			})
		})


		$('.product').change((e) => {
			form.inventoryid = e.target.value
		})
		$('.costo').keyup((e) => {
			form.costo = e.target.value
		})
		$('.iva').change((e) => {
			form.iva = e.target.value
		})
		$('.ganancia_al_mayor').on('change keyup', (e) => {
			form.ganancia_al_mayor = e.target.value
		})
		$('.ganancia_al_menor').on('change keyup', (e) => {
			form.ganancia_al_menor = e.target.value
		})


		// ---------------- end data binding -------------

		$('.calcular').click(function() {

			// console.log(form)

			let id = form.inventoryid

			$.get({
				url: `/inventory/${id}`,
				beforeSend(){
					$('#calcular').attr('disabled', true)
					$('#calcular').html('')
					$('#calcular').append('<div class="spinner-grow" role="status"></div>')
				}
			})
			.done((response) => {

				$('#calcular').removeAttr('disabled')
				$('#calcular').html('<i class="fas fa-calculator mr-2"></i> Calcular')

				// Calcular precio total al menor y margen de ganancia
				let costo      = form.costo
				let sin_puntos = costo.replace(/\./g, '')
				let precio     = + sin_puntos.replace(/,/g, '.')
				let iva_percent = + form.iva
				let retail_margin_gain    = + form.ganancia_al_menor
				let wholesale_margin_gain = + form.ganancia_al_mayor

				// precio al menor
				let precio_menor_total = calcular_precio(precio, retail_margin_gain)

				// precio al mayor
				let precio_mayor_total = calcular_precio(precio, wholesale_margin_gain)

				//calcular iva
				let price_iva_menor = calcular_iva(iva_percent, precio_menor_total)
				let price_iva_mayor = calcular_iva(iva_percent, precio_mayor_total)

				$('.total_retail_price').text(new Intl.NumberFormat().format(precio_menor_total))
				$('.iva_retail_price').text(new Intl.NumberFormat().format(price_iva_menor))

				let totalmenor = parseFloat(precio_menor_total) + parseFloat(price_iva_menor)
				$('.total_amount_retail_price').text(new Intl.NumberFormat().format(totalmenor))

				// $('#retail_total_price').val(precio_menor.toFixed(2))
				$('.retail_iva_amount').val(price_iva_menor.toFixed(2))
				$('.retail_total_price').val(totalmenor.toFixed(2))



				$('.total_wholesale_price').text(new Intl.NumberFormat().format(precio_mayor_total.toFixed(2)))
				$('.iva_wholesale_price').text(new Intl.NumberFormat().format(price_iva_mayor.toFixed(2)))

				let totalmayor = parseFloat(precio_mayor_total) + parseFloat(price_iva_mayor)
				$('.total_amount_wholesale_price').text(new Intl.NumberFormat().format(totalmayor.toFixed(2)))

				$('.wholesale_iva_amount').val(price_iva_mayor.toFixed(2))
				$('.wholesale_total_individual_price').val(totalmayor.toFixed(2))

				// precio total con iva
				let total2 = totalmayor * response.qty_per_unit
				// $('#total2_amount_wholesale_price').text(new Intl.NumberFormat().format(total2.toFixed(2)))
				$('.wholesale_total_packet_price').val(total2.toFixed(2))
				
				// precio total mayir  sin iva
				let total3 = precio_mayor_total * response.qty_per_unit
				$('.wholesale_packet_price').val(total3.toFixed(2))
			})
			.fail((err) => {
				console.error(err)
				toastr.error('Algo a pasado.')
			})

		})

	})
</script>
@endpush