{{-- OBJETIVOS DEL DIA DE MAÑANA

* IR AL PERFIL DEL CLIENTE Y MOSTRAR LA VENTA CON TODA LA INFO
*
* MOSTARAR LA VENTA Y STATUS EN EL ADMIN
* CAMBIAR STATUS CUANDO EL REPARTIDOR LLEGUE

* HACER UNA FACTURA QUE SE DESCARGUE SOLA CUANDO NO SELECCIONE EL DELIVERY
* HACER QUE LA FACTURA TENGA UN STATUS PARA SABR SI FUE RETIRADA O NO
* MANEJO DE RLES Y PERSMISOS



El objetivo de la semana es completar el flujo entero de la compra. El cual es:

1) user ve los productos en el ecommerce. (LISTO)

2) user agrega los productos que quiere comprar al mayor o al menor. (LISTO)

3) El sistema hace los cálculos matemáticos pertinentes para mostrar el precio y demás.(LISTO)

4) El usuario una vez dispuesto a cancelar por los productos debe llenar 2 formularios: 

4.1) Uno donde indica la dirección a donde se le enviarán los productos (en caso de que así lo quiera).(LISTO)

4.2) Otro donde pueda introducir la constancia de que el pago se efectuó. (LISTO)

5) Una vez hecho el pago, será redirigido a su perfil donde en la parte de pedidos muestre toda la información de la compra efectuada dándole la opción de descargar la factura, la cual es requerida para retirar/recibir los productos comprados. (INCOMPLETO)

6) Se restan los productos comprados en el almacén. (FALTA)

7)  El admin debe darse cuenta de la compra efectuada con toda su información. (FALTA)


********************** DONE ******************************
* LISTA DE DESEOS
* MOSTRAR SELECCION DE LA ENTREGA EN ALGUN LADO ANTES DE LA COMPRA
* MONTO TOTAL JUNTO CON LA TARIFA DEL DELIVERY
* AL DARLE A PAGAR MOSTRAR DIALOGO DE TIPO DE PAGO Y UN BOTON PARA PAGAR Y LISTO
* HACER LOS CAMBIOS PERTINENES A LA DB
* ADMINISTRAR LAS DIRECCIONES DE ENTREGA
	- IR A LA CONFIGURACION DEL PERFIL
	- CREARLAS, ELIMINARLAS, MODIFUCARLAS
* MOSTRAR LAS DIRECCIONES EN EL MODAL Y OPCION PARA AÑADIR NUEVAS --}}


@extends('layouts.customer')

@section('content')

<div id="loading" class="loading">
	<div>
		<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status">
		</div>
		<p>Espere un momento...</p>
	</div>
</div>

<div style="margin-top: 90px"></div>


<div class="container-fluid wrapper my-5">
	<div class="row d-lg-none d-md-none">
		Carrito de Compra
	</div>
	<div class="row">
		<div class="col-lg-9 col-12">
			<div class="card shadow-sm mb-3">
				<div class="card-body">

					<ul class="list-group">
						@forelse ($cart as $c)
							@php
							$subtotal = ($c->price - $c->attributes->retail_iva_amount) * $c->quantity;
							$iva = $c->attributes->retail_iva_amount * $c->quantity;

							$totalSinIva += $subtotal;
							@endphp

							<li class="list-group-item itempadre">
								<div class="row filapadre">

									<div class="d-none" data-id="{{ $c->id }}"></div>
									<div class="d-none sale_type">{{ $c->attributes->sale_type }}</div>

									<div class="col-md-4 col-sm-6 col-12">
										
										@if( $c->attributes->sale_type == 'al-mayor' )
											<p class="text-muted small">PRODUCTO AL MAYOR</p>
										@else
											<p class="text-muted small">PRODUCTO AL MENOR</p>
										@endif

										<div class="d-flex justify-content-start">
											<img src="/storage/{{ $c->attributes->image }}" style="height: 70px;" class="mr-2">
											<p class="small">
												<span class="font-weight-bold">{{ $c->name }}</span><br>

												@if( $c->attributes->sale_type == 'al-mayor' )
													<span>1 {{ $c->model->inventory->unit_type }} de {{ $c->model->inventory->qty_per_unit }} productos</span>
												@else
													<span>{{ $c->model->inventory->description }}</span>
												@endif
											</p>
										</div>
									</div>

									<div class="col-md-2 col-sm-6 col-12 padrecantidad">
										<p class="text-muted small">CANTIDAD</p>
										<div class="input-group mb-3 padre">
											<div class="input-group-prepend">
												<button class="input-group-text btn btn-primary" onclick="substract('{{$c->id}}')"><i class="fas fa-angle-down"></i></button>
											</div>
											<input type="text" onkeypress="soloNumeros(event)" class="form-control sinflechas-{{$c->id}} rounded-0" value="{{ $c->quantity }}" min="1">
											<div class="input-group-append">
												<button class="input-group-text btn btn-primary" onclick="add('{{$c->id}}')"><i class="fas fa-angle-up"></i></button>
											</div>
										</div>
									</div>
									
									<div class="col-md-3 col-sm-6 col-12 padreprecio">
										<p class="text-muted small">PRECIO Bs</p>
										<p class="small">
											<span class="font-weight-bold precio-{{$c->id}}">{{ number_format($subtotal, 2, ',', '.') }}</span>
											<br>
											<span class="iva_product iva_product-{{$c->id}}">Iva: {{ number_format($iva, 2, ',', '.') }}</span>
										</p>

										@if( $c->attributes->sale_type == 'al-mayor' )

											<input type="hidden" class="preciosiniva" value="{{ $c->attributes->wholesale_packet_price }}">
											
											<span class="text-muted small">
												<span class="preciopvp">{{ number_format($c->attributes->wholesale_total_packet_price, 2, ',', '.') }}</span> c/u
											</span>
											<br>
											{{--<span class="text-muted small">
												<span>IVA {{ $c->attributes->iva }}%: <span class="iva">{{  number_format($c->attributes->wholesale_iva_amount, 2, ',', '.') }}</span> Bs</span>
											</span>--}}
											<br>
										@else
									
											<input type="hidden" class="preciosiniva" value="{{ $c->attributes->cost }}">

											<span class="text-muted small">
												<span class="preciopvp">{{ number_format($c->price, 2, ',', '.') }}</span> c/u
											</span>
											<br>
											{{-- <span class="text-muted small">
												<span>IVA {{ $c->attributes->iva }}%: <span class="iva">{{ number_format($c->attributes->retail_iva_amount, 2, ',', '.') }}</span> Bs</span>
											</span> --}}
											<br>

										@endif

									</div>

									
									<div class="col-md-2 col-sm-6 col-12">
										<p class="text-muted small">CANT. AL MAYOR</p>
										<span class="font-weight-normal precio">{{ $c->attributes->wholesale_quantity }}</span><br>
									</div>
									{{-- <div class="col-md-2 col-sm-6 col-12">
										<p class="text-muted small">CALIFICAR</p>
										<input name="input-2" value="2.4" class="star-rating kv-ltr-theme-fas-star rating-loading" data-size="xs">
									</div> --}}

									<div class="col-md-1 col-sm-6 col-12 d-flex justify-content-end">
										<div class="mt-4">
											<button class="btn btn-outline-danger eliminar" onclick="delete_item({{$c->id}})">
												<i class="fas fa-times"></i>
											</button>
										</div>
									</div>
								</div>
							</li>

						@empty

							<h4 class="text-center my-5">
								<i class="fas fa-2x fa-shopping-cart mb-4"></i><br>
								No hay productos en el carrito.
							</h4>

						@endforelse

					</ul>


				</div>
				<div class="card-footer d-flex justify-content-between">
					@if(count($cart) > 0)
						<button type="button" class="btn btn-danger" id="limpiar_carrito" data-toggle="modal" data-target="#clear_cart">
							<i class="fas fa-times mr-2"></i>Limpiar carrito
						</button>
					@endif
					<a href="/home" class="btn btn-primary">
						<i class="fas fa-cart-plus mr-2"></i>Agregar más productos
					</a>
				</div>
			</div>
		</div>

		<div class="col-lg-3 col-12">
			{{-- <div class="card shadow-sm mb-4">
				<div class="card-body">

					<label for="codigo">¿Tienes un cupón de descuento?</label>
					<div class="input-group mb-3">
						<input type="text" id="codigo" class="form-control rounded-0 rounded-left" placeholder="Código">
						<div class="input-group-prepend">
							<button class="btn btn-primary rounded-right">Aplicar</button>
						</div>
					</div>

				</div>
			</div> --}}

			<div class="card shadow-sm">
				<div class="card-body">
					<div class="row mb-1">
						<div class="col">
							<span>Subtotal:</span>
						</div>
						<div class="col text-right">
							<span><span id="totalSinIva">{{ number_format($totalSinIva, 2, ',', '.') }} </span> Bs.</span>
						</div>
					</div>
					<div class="row mb-1">
						<div class="col">
							<span>IVA Total:</span>
						</div>
						<div class="col text-right">
							<span><span id="ivatotal">{{ number_format($ivatotal, 2, ',', '.') }}</span> Bs</span>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col">
							<span class="font-weight-bold">Total:</span>
						</div>
						<div class="col text-right">
							<span class="font-weight-bold"><span id="montoTotal">{{ number_format($total, 2, ',', '.') }}</span> Bs</span>
						</div>
					</div>					
					<!--<div class="row mb-1">
						<div class="col">
							<span>Descuento:</span>
						</div>
						<div class="col text-right">
							<span class="text-danger">- 45.000 Bs</span>
						</div>
					</div>
					<div class="row mb-1 delivery_cost d-none">
						<div class="col">
							<span>Delivery:</span>
						</div>
						<div class="col text-right">
							<span>+<span class="precio no" id="delivery_cost">0</span> Bs</span>
						</div>
					</div>-->

					<hr>

					<form action="/sale" method="post" enctype="multipart/form-data">
						@csrf

						<input type="hidden" name="monto" id="monto">
						<input type="hidden" name="user_address_delivery" id="user_address_delivery">
						<input type="hidden" name="numero_referencia" id="numero_referencia">
						<!-- <input type="file" name="capture_payment" class="custom-file-input" accept="image/*" id="customFile" style="height: 1px; width: 1px"> -->

						<div class="row mb-4">
							<div class="col">
								<label for="delivery" class="text-success">¿Desea el servicio delivery?</label><br>
								<i>Solo se hará delivery en el Municipio Sucre.</i>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" onchange="changeRadio(this.value)" class="form-check-input" name="delivery" value="si">Si
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" onchange="changeRadio(this.value)" class="form-check-input" name="delivery" value="no">No
									</label>
								</div>
								<i id="address" class="d-none">Aragua, Cagua, Centro de Cagua, Calle Sabana Larga entre Rondon y Mariño Local 1 N° 104-10-19 Cerca de las Terrazas.</i>
								{{-- <select id="delivery" name="delivery" class="form-control border selectpicker">
									<option selected disabled>Selecciona</option>
									<option value="si">Si</option>
									<option value="no">No</option>
								</select> --}}
								<p class="mt-2 mb-0 text-right d-none" id="delivery_btn">
									<a href="#modalDelivery" data-toggle="modal">Editar</a>
								</p>
							</div>
						</div>

						<div class="row d-none detallesescondidos">
							<div class="col-12">
								<div class="row">
									<div class="col-5">
										<p class="font-weight-bold">Sector:</p>
									</div>
									<div class="col">
										<p class="text-right" id="sector_info">...</p>
									</div>
								</div>
								<div class="row">
									<div class="col-5">
										<p class="font-weight-bold">Tiempo estimado:</p>
									</div>
									<div class="col">
										<p class="text-right" id="stimatedtime_info">...</p>
									</div>
								</div>
								<div class="row">
									<div class="col-5">
										<p class="font-weight-bold">Detalles:</p>
									</div>
									<div class="col">
										<p class="text-right" id="detalles_info">...</p>
									</div>
								</div>
								<div class="row">
									<div class="col-5">
										<p class="font-weight-bold">Tarifa:</p>
									</div>
									<div class="col">
										<p class="text-right"><span id="tarifa_info">...</span> Bs</p>
									</div>
								</div>
							</div>
						</div>

						<hr>

						<div class="row mb-4">
							<div class="col">
								<label for="pay_method">Selecciona el método de pago</label>
								<select id="pay_method" name="pay_method" class="form-control border selectpicker">
									<option selected disabled>Selecciona</option>
									<option value="transferencia">Transferencia Bancaria</option>
									<option value="pagomovil">Pago Móvil</option>
									<option value="dolares">Dolares (efectivo)</option>
								</select>
							</div>
						</div>

						<div class="row d-none detallesescondidos2">
							<div class="col-12">
								<div class="row">
									<div class="col-5">
										<p class="font-weight-bold">N° Operación:</p>
									</div>
									<div class="col">
										<p id="nooperacion" class="text-right">123456789</p>
									</div>
								</div>
							</div>
						</div>

						<hr>

						<div class="row">
							<div class="col">
								<button disabled type="submit" class="btn btn-primary btn-block" id="enviarPago">
									<i class="fas fa-cash-register mr-2"></i>Enviar Pago
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>



{{-- MODAL PAR LIMPIAR EL CARRITO --}}
<div class="modal fade" id="clear_cart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Limpiar carrito de compras</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<h4 class="text-center font-weight-light my-5">¿Estás seguro de querer eliminar los productos?</h4>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
				<button type="button" id="limpiar" data-dismiss="modal" class="btn btn-primary"><i class="fas fa-check mr-2"></i>Sí, estoy seguro</button>
			</div>
		</div>
	</div>
</div>


{{-- MODAL PARA LA DIRECCION DEL DELIVERY --}}
<div class="modal fade" id="modalDelivery" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Información para la entrega</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12">
						<label for="direccion">Selecciona una dirección para la entrega</label><br>
						<select required name="address" data-live-search="true" class="selectpicker mb-2 border form-control" data-width="100%" id="direccion">
							<option selected disabled>Direcciones registradas</option>
							{{-- <option data-subtext="Registrar una nueva dirección para la entrega" value="nueva">Nueva dirección</option> --}}

							@forelse ($user_address as $address)
								<option data-subtext="{{ number_format($address->travel_rate->rate, 0, ',', '.') }} Bs" value="{{ $address->id }}">{{ $address->travel_rate->sector->sector }}</option>
							@empty
								<option disabled>No tienes direcciones registradas</option>
							@endforelse
						</select>
						@if($user_address)

							<a href="/perfil/"><i class="fas fa-plus mr-1"></i>Registrar una dirección</a>
						@endif
					</div>
				</div>

				<div class="row" id="">
					
					<div class=" mx-auto py-5 sr-only" id="direccion_loader">
						<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status"></div>
					</div>

					<div class="col-12 d-none" id="direccion_content">
						<div class="row">
							<div class="col-4">
								<p class="font-weight-bold">Tiempo estimado:</p>
							</div>
							<div class="col">
								<p id="stimatedtime">...</p>
							</div>
						</div>
						<div class="row">
							<div class="col-4">
								<p class="font-weight-bold">Tarifa:</p>
							</div>
							<div class="col">
								<p><span id="tarifa">0</span> Bs</p>
							</div>
						</div>
						<div class="row">
							<div class="col-4">
								<p class="font-weight-bold">Detalles:</p>
							</div>
							<div class="col">
								<p id="detalles">...</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
				<button type="button" id="listo" class="btn btn-primary"><i class="fas fa-check mr-2"></i>Listo</button>
			</div>
		</div>
	</div>
</div>


{{-- MODAL PARa SELECCONAR EL TIPO DE PAGO --}}
<div class="modal fade" id="pay_method_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Rellena todos los campos</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<h6 class="font-weight-light">Información del pago</h6>
				<div class="row">
					<div class="col-5">
						<b>Adjuntar Comprobante</b><br>
						<div class="file-input-wrapper">
							<img class="img-fluid img-thumbnail shadow" style="height: 200px; display: none" id="foto">
							<p id="image_name" class="mt-3 mb-1"></p>
							<p id="image_weigth" class="mb-3"></p>

							<p id="imgerror" class="text-danger" style="display: none;"></p>
							<button id="clearbtn" type="button" class="btn btn-primary" style="display: none"><i class="fas fa-trash mr-2"></i>Limpiar</button>
							<label for="fileinput" class="btn btn-primary"><i class="fas fa-folder-open mr-2"></i>Buscar</label>
							<input id="fileinput" id="fileinput" name="fileinput" type="file" accept="image/*,application/pdf" required>
						</div>
					</div>
					<div class="col-2">
						<b>Ó</b>
					</div>
					<div class="col-5">
						<label for="referencia" class="font-weight-bold">Número de referencia</label>
						<input type="number" id="referencia" class="form-control" name="referencia">
					</div>
					<!-- <div class="col-12 text-center col-md-7">
						<label for="customFile">Captura de la operación</label>
						<label id="btn_search" class="btn btn-primary" for="customFile"><i class="fas fa-folder-open mr-2"></i>Buscar</label>
					</div> -->
				</div>

				<hr>
	
				<h6 class="font-weight-light">Información bancaria</h6>
					@forelse($cb as $c)
						<div class="card-body">
							<blockquote class="blockquote mb-0">
								<footer class="blockquote-footer">
									<b>Nombre: </b> <i id="name-{{$loop->iteration}}">{{ $c->name_enterprise }}</i> / 
									<b>Cedula: </b><i id="dni-{{$loop->iteration}}">{{ $c->dni }}</i> <i class="far fa-copy text-primary" data-toggle="tooltip" title="Copiar" onclick="copiarAlPortapapeles('dni-{{$loop->iteration}}')"></i> / 
									<b>Telf: </b><i id="phone-{{$loop->iteration}}">{{ str_replace("-", "", $c->phone_enterprise) }}</i> <i class="far fa-copy text-primary" data-toggle="tooltip" title="Copiar" onclick="copiarAlPortapapeles('phone-{{$loop->iteration}}')"></i> / 
									<b>Código: </b> <i id="code-{{$loop->iteration}}">{{ $c->code }}</i> <i class="far fa-copy text-primary" data-toggle="tooltip" title="Copiar" onclick="copiarAlPortapapeles('code-{{$loop->iteration}}')"></i> / 
									<b>Cuenta: </b> <i id="account-{{$loop->iteration}}">{{ str_replace("-", "", $c->account_number) }} </i> <i class="far fa-copy text-primary" data-toggle="tooltip" title="Copiar" onclick="copiarAlPortapapeles('account-{{$loop->iteration}}')"></i>
									<cite title="Source Title"></cite>
								</footer>
							</blockquote>
						</div>
					@empty
						<div class="card-body">
							<blockquote class="blockquote mb-0">
								<footer class="blockquote-footer">
									<b>No hay información disponible</b>
								</footer>
							</blockquote>
						</div>
					@endforelse
				{{--<h6 class="mb-3 font-weight-light">Información del cliente</h6>
				<div class="row">
					<div class="col-6 col-md-4">
						<small class="font-weight-bold">Cédula</small>
						<p>{{ $user->people->dni }}</p>
					</div>
					<div class="col-6 col-md-4">
						<small class="font-weight-bold">Nombre</small>
						<p>{{ $user->people->name }}</p>
					</div>
					<div class="col-6 col-md-4">
						<small class="font-weight-bold">Apellido</small>
						<p>{{ $user->people->lastname }}</p>
					</div>
				</div>--}}

				<!-- <div class="row">
					<div class="col">
						<img class="img-fluid rounded-lg d-none img-thumbnail" id="capture_preview">
					</div>
				</div> -->

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
				<button type="button" id="listo2" data-dismiss="modal" class="btn btn-primary"><i class="fas fa-check mr-2"></i>Listo</button>
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
	var myCart = @json($cart);

	function changeRadio(val) {
		if (val == 'si') {
			$('#address').addClass('d-none').removeClass('d-block')
			$('#modalDelivery').modal('show')
		} else {
			$('#address').addClass('d-block').removeClass('d-none')
		}
	}

	function substract(id) {
		if (myCart[id].quantity == 1) {
			return
		}
		myCart[id].quantity = parseInt(myCart[id].quantity) - 1;
		$('.sinflechas-'+id).val(myCart[id].quantity)
		subtotal_item(id)
		subtotal()
	}

	function delete_item(id) {
		// console.log(id, myCart[id])
		delete myCart[id]
		console.log(myCart)
		subtotal()
	}

	function add(id) {
		myCart[id].quantity = parseInt(myCart[id].quantity) + 1
		if (myCart[id].quantity >= parseInt(myCart[id].attributes.wholesale_quantity)) {

		}
		$('.sinflechas-'+id).val(myCart[id].quantity)
		subtotal_item(id)
		subtotal()
	}

	function subtotal_item(id) {
		// console.log(typeof myCart[id].price, typeof parseInt(myCart[id].attributes.retail_iva_amount), typeof myCart[id].quantity)
		myCart[id].subtotal = (myCart[id].price - parseInt(myCart[id].attributes.retail_iva_amount)) * myCart[id].quantity
		myCart[id].iva = myCart[id].attributes.retail_iva_amount * myCart[id].quantity
		$('.precio-'+id).text(new Intl.NumberFormat('de-DE').format(myCart[id].subtotal)+',00')
		$('.iva_product-'+id).text("Iva: " + new Intl.NumberFormat('de-DE').format(myCart[id].iva)+',00')
		return myCart[id].subtotal
	}

	function subtotal() {
		let subtotal = 0
		let iva = 0
		let total = 0
		// console.log(myCart)
		for (let cart in myCart) {
			total += myCart[cart].price * myCart[cart].quantity
			// console.log(myCart[cart].price, myCart[cart].quantity, parseInt(myCart[cart].attributes.retail_iva_amount))
			subtotal += (myCart[cart].price * parseInt(myCart[cart].quantity)) - (parseInt(myCart[cart].attributes.retail_iva_amount) * parseInt(myCart[cart].quantity))
			// console.log("subtotal: ", subtotal)
			iva += parseInt(myCart[cart].attributes.retail_iva_amount * myCart[cart].quantity)
		}
		console.log("total: ", subtotal)
		console.log("total: ", total)
		$('#totalSinIva').text(new Intl.NumberFormat('de-DE').format(subtotal)+',00')

		$('#ivatotal').text(new Intl.NumberFormat('de-DE').format(iva)+',00')
		$('#montoTotal').text(new Intl.NumberFormat('de-DE').format(total)+',00')
	}
	
	$(() => {
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

			let allowed_ext = ['png', 'jpeg', 'gif', 'jpg', 'pdf']
			let ext_length = allowed_ext.length

			for (let i = 0; i < ext_length; i++){
				if (!file.type.includes(allowed_ext[i])) {
					if (ext_length - 1 == i) {
						$('#imgerror').text('Este formato no está permitido.')
						$('#imgerror').show()

						return
					} 
				} else {
					break
				}
			}

			reader.onload = (ev) => {
				$('#imgerror').hide()
				$('#foto').show();
				$('#clearbtn').show();
				$('#foto').attr('src', file.type == 'application/pdf' ? '{{asset("img/file-icon.png")}}' : ev.target.result);
				$('#foto').css('height', '110px');
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

		// function actualizarCarro(id, cantidad, tipo_compra){

		// 	console.log(tipo_compra)

		// 	$.ajax(`/shoppingcart/${id}`, {
		// 		method: 'put',
		// 		data: {
		// 			quantity: cantidad,
		// 			sale_type: tipo_compra
		// 		},
		// 		beforeSend(){
		// 			// console.log('before send')
		// 		}
		// 	})
		// 	.done((res) => {

		// 		if (res == 'rejected') {
		// 			toastr.warning('El tipo de compra no coincide.')
		// 		}
		// 		else {
		// 			$('#cart_counter').text(res)
		// 		}

		// 		console.log(res)
		// 	})
		// 	.catch((err) => {
		// 		toastr.error('Ha ocurrido un error')
		// 		console.error(err)
		// 	})
		// }

		// function calcularPrecio(ev, operacion){
		// 	console.log(ev.target, )
		// 	let id        = $(ev.target).parents('.filapadre').children('div.d-none').data('id')
		// 	let input     = $(ev.target).parents('.padre').children('.sinflechas')
		// 	let cantidad  = input.val()
		// 	let sale_type = $(ev.target).parents('.filapadre').children('.sale_type').text()
		// 	let precio    = $(ev.target).parents('.filapadre').children('.padreprecio').children('.text-muted').children('.preciopvp').text()

		// 	let formatedprecio1 = precio.replace(/\./g, '')
		// 	let formatedprecio2 = formatedprecio1.replace(',', '.')

		// 	if (operacion == 'sumar') {
		// 		if (ev.type == 'click' && ev.target.nodeName == 'BUTTON' || ev.target.nodeName == 'I') {
		// 			input.val(parseInt(cantidad) + 1)
		// 		}

		// 		actualizarCarro(id, input.val(), sale_type)
		// 		//console.log(new Intl.NumberFormat('de-DE').format(formatedprecio2 * input.val()))
		// 		return new Intl.NumberFormat('de-DE').format(formatedprecio2 * input.val())
		// 	} else {
		// 		if (cantidad < 2) {
		// 			cantidad = 1
		// 		} else {
		// 			if (ev.type == 'click') {
		// 				input.val(parseInt(cantidad) - 1)
		// 			}
		// 		}

		// 		actualizarCarro(id, input.val(), sale_type)

		// 		return new Intl.NumberFormat('de-DE').format(formatedprecio2 * input.val())
		// 	}
		// }

		// function calcularPrecioTotal(){
		// 	let result = 0
		// 	let sinIva = 0

		// 	// calcular precio total
		// 	$('.precio').each((i, el) => {
		// 		let formatedprecio1 = $(el).text().replace(/\./g, '')
		// 		let formatedprecio2 = formatedprecio1.replace(',', '.')
		// 		result += parseFloat(formatedprecio2)
		// 	})

		// 	// calcular precio sin iva
		// 	$('.preciosiniva').each((i, el) => {
		// 		let precio = $(el).val()
		// 		let qty    = $(el).parents('.filapadre').children('.padrecantidad').children('.padre').children('.sinflechas').val()
		// 		let r = precio * qty
		// 		sinIva += parseFloat(r)
		// 	})

		// 	return {
		// 		total: new Intl.NumberFormat('de-DE').format(result),
		// 		sinIva: new Intl.NumberFormat('de-DE').format(sinIva)
		// 	}
		// }

		// function calcularIVATotal(){
		// 	let result = 0

		// 	$('.iva').each((i, el) => {
		// 		let formatedprecio1 = $(el).text().replace(/\./g, '')
		// 		let formatedprecio2 = formatedprecio1.replace(',', '.')
		// 		let cantidad        = $(el).parents('.filapadre').children('.padrecantidad').children('.padre').children('.sinflechas').val()
				
		// 		let r = formatedprecio2 * cantidad
				
		// 		result += parseFloat(r)
		// 		// debugger
		// 	})

		// 	return new Intl.NumberFormat('de-DE').format(result)
		// }

		// saber si hay elementos en el carrito para mostrar la candidad en color rojo en el navbar
		$.get('/get_shoppingcart', (res) => {
			if (res > 0) {
				$('#cart_counter').removeClass('d-none')
				$('#cart_counter').text(res)
			}

			$('#loading').fadeOut()
		})

		// saber si hay elementos en la lista de deseos
		$.get('/get_wishlist', (res) => {
			if (res > 0) {
				$('#wishlist_counter').removeClass('d-none')
				$('#wishlist_counter').text(res)
			}
		})
		.catch((err) => {
			tostr.error('Ha ocurrido un error.')
			console.error(err)
		})

		$('.add, .sinflechas').on('click keyup', (e) => {
			// let result = calcularPrecio(e, 'sumar')
			
			// $(e.target).parents('.filapadre').children('.padreprecio').children('.precio').text(result)

			// let total = calcularPrecioTotal()
			// $('#montoTotal').text(total.total)

			// let precioSinIva = calcularPrecioTotal()
			// $('#totalSinIva').text(total.sinIva)

			// let ivaTotal = calcularIVATotal()
			// $('#ivatotal').text(ivaTotal)
		})

		$('.substract').click((e) => {
			console.log(e)
			myCart.find((el) => {
				
			})
			// let result = calcularPrecio(e, 'restar')
			// $(e.target).parents('.filapadre').children('.padreprecio').children('.precio').text(result)

			// let total = calcularPrecioTotal()
			// $('#montoTotal').text(total.total)

			// let precioSinIva = calcularPrecioTotal()
			// $('#totalSinIva').text(total.sinIva)

			// let ivaTotal = calcularIVATotal()
			// $('#ivatotal').text(ivaTotal)
		})

		$('.eliminar').click(function() {
			console.log("clase eliminar")
			let producto = $(this).parents('.itempadre')
			let id = $(this).parents('.filapadre').children('.d-none').data('id')

			$.ajax(`shoppingcart/${id}`, { method: 'delete' })
			.done((res) => {

				$('#cart_counter').text(res)
				producto.remove()

				if ($('.itempadre').length < 1) {
					$('.list-group').html(`
						<h5 class="text-center my-5">
							<i class="fas fa-2x fa-shopping-cart mb-3"></i><br>
							No hay productos en el carrito.
						</h5>
					`)
				}

			})
			.catch((err) => {
				toastr.error('Ha ocurrido un error')
				console.error(err)
			})
		})

		$('#limpiar').click(function() {

			$.ajax('/limpiar_carrito', { method: 'delete' })
			.done((res) => {

				$('#limpiar_carrito').remove()

				$('#cart_counter').text(res)
				$('.itempadre').remove()

				$('.list-group').html(`
					<h5 class="text-center my-5">
						<i class="fas fa-2x fa-shopping-cart mb-3"></i><br>
						No hay productos en el carrito.
					</h5>
				`)
				myCart = {}
				$('#totalSinIva').text(new Intl.NumberFormat('de-DE').format(0)+',00')
				$('#ivatotal').text(new Intl.NumberFormat('de-DE').format(0)+',00')
				$('#montoTotal').text(new Intl.NumberFormat('de-DE').format(0)+',00')
			})
			.catch((err) => {
				console.error(err)
			})
		})

		$('#delivery').change(function() {
			let resp = $(this).val()
			console.log(resp)
			if (resp == 'si') {
				
			} else {
				$('#sector_info').text('')
				$('#stimatedtime_info').text('')
				$('#detalles_info').text('')
				$('#tarifa_info').text('')
				$('#sector').text('')
				$('#stimatedtime').text('')
				$('#detalles').text('')
				$('#tarifa').text('')

				$('.delivery_cost').addClass('d-none')
				$('#delivery_cost').text(0)
				$('#direccion').val('')

				let total = calcularPrecioTotal()
				$('#montoTotal').text(total.total)

				let precioSinIva = calcularPrecioTotal()
				$('#totalSinIva').text(total.sinIva)

				let ivaTotal = calcularIVATotal()
				$('#ivatotal').text(ivaTotal)

				$('#delivery_btn').addClass('d-none')
				$('.detallesescondidos').addClass('d-none')
			}
		})

		$('#direccion').change(function(){

			let direccion = $(this).val()

			$('#direccion_loader').removeClass('sr-only')

			$.get(`/direcciones/${direccion}`, (res) => {
				$('#stimatedtime').text(`${res.travel_rate.stimated_time} min`)
				$('#tarifa').text(new Intl.NumberFormat('de-DE').format(res.travel_rate.rate))
				$('#detalles').text(`${res.details}`)

				$('#direccion_loader').addClass('sr-only')		
				$('#direccion_content').removeClass('d-none')			
			})
			.catch((err) => {
				toastr.error('Algo ha ocurrido.')
				console.error(err)
			})
		})

		// boton listo del modal del delivery
		$('#listo').click(() => {
			let sector        = $('#direccion option:selected').text()
			let stimated_time = $('#stimatedtime').text()
			let tarifa        = $('#tarifa').text()
			let detalles      = $('#detalles').text()

			if ( ! $('#direccion')[0].validity.valueMissing ){

				$('#user_address_delivery').val($('#direccion').val())

				$('#sector_info').text(sector)
				$('#stimatedtime_info').text(stimated_time)
				$('#detalles_info').text(detalles)
				$('#tarifa_info').text(tarifa)

				$('.delivery_cost').removeClass('d-none')
				$('#delivery_cost').text(tarifa)

				let total = calcularPrecioTotal()
				$('#montoTotal').text(total.total)

				let precioSinIva = calcularPrecioTotal()
				$('#totalSinIva').text(total.sinIva)

				let ivaTotal = calcularIVATotal()
				$('#ivatotal').text(ivaTotal)

				$('.detallesescondidos').removeClass('d-none')

				$('#modalDelivery').modal('hide')
			}
			else {
				$('#direccion')[0].setCustomValidity('Este campo no puede quedar vacío.')
			}
		})

		

		$('#pay_method').change(function() {
			let valor = $(this).val()

			if ( valor == 'transferencia' || valor == 'pagomovil' ) {
				$('#pay_method_modal').modal('show')
			}
			else {
				//
			}
		})

		$('#listo2').click(() => {

			// ASEGURARME QUE LOS CAMPOS DE LA REFERENCIA E IMAGEN ESTEN VALIDADPS

			$('#nooperacion').text($('#referencia').val())
			$('#numero_referencia').val($('#referencia').val())
			$('#monto').val($('#montoTotal').text())

			$('.detallesescondidos2').removeClass('d-none')

			$('#enviarPago').removeAttr('disabled')
		})

		// $('#customFile').change(function(e) {
		// 	let reader = new FileReader()

		// 	reader.onload = () => {
		// 		$('#capture_preview').attr('src', reader.result)
		// 		$('#capture_preview').removeClass('d-none')

		// 	}

		// 	reader.readAsDataURL(e.target.files[0])
		// })


		// EMVIAR PAGO
		$('#enviarPago').click(() => {
			let data = window.localstorage.getItem('saledetail')

			console.log(data)
		})


		// CALIFICAR EL PRODICTO
		// $('.input-2').rating({
		// 	theme: 'krajee-fas',
		// 	containerClass: 'is-star',
		// 	starCaptions: {1: 'Muy malo', 2: 'Malo', 3: 'Más o menos', 4: 'Bueno', 5: 'Excelente'},
		// 	starCaptionClasses: {1: 'text-danger', 2: 'text-warning', 3: 'text-info', 4: 'text-primary', 5: 'text-success'},
		// });
	})

</script>
@endpush

@push('styles')
<style>
	.sinflechas {
		-moz-appearance: textfield;
		-webkit-appearance: textfield;
	}

	.rounded-right {
		border-top-right-radius: 10px !important;
		border-bottom-right-radius: 10px !important;
	}

	.rounded-left {
		border-top-left-radius: 10px !important;
		border-bottom-left-radius: 10px !important;
	}
</style>
@endpush