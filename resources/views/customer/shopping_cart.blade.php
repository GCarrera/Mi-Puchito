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

<div style="margin-top: 4%"></div>

<!--<img data-src="/img/banner2.jpg" style="height: 470px; margin-top: 4%;" class="d-block w-100" alt="...">-->
<div id="img-carrito">
	<div class="row align-items-center h-100">
		<div class="col-12">
			<h1 class="text-center text-white font-weight-bold" style="font-size: 5em;">Carrito de compra</h1>
		</div>
	</div>
</div>

<div class="container-fluid wrapper my-5">
	<div class="row d-lg-none d-md-none text-center">
		<h4 class="col-md-12">
			Carrito de Compra
		</h4>
	</div>
	<div class="row">
		<div class="col-lg-9 col-12">
			<div class="card shadow-sm mb-3">
				<div class="card-body">

					<ul class="list-group" style="">
						@forelse ($cart as $c)
						@if($c->options->sale_type != 'al-mayor')
							@php
							$subtotal = ($c->price - $c->options->retail_iva_amount) * $c->qty;
							$iva = $c->options->retail_iva_amount * $c->qty;

							$totalSinIva += $subtotal;
							@endphp
						@endif
							<li class="list-group-item itempadre">
								<div class="row filapadre">

									<div class="d-none" data-id="{{ $c->rowId }}"></div>
									<div class="d-none sale_type">{{ $c->options->sale_type }}</div>

									<div class="col-md-4 col-sm-6 col-12 order-1">
										
										@if( $c->options->sale_type == 'al-mayor' )
											<p class="text-muted small">PRODUCTO AL MAYOR</p>
										@else
											<p class="text-muted small">PRODUCTO AL MENOR</p>
											
										@endif
							

										<div class="d-flex justify-content-start">
											<img data-src="/storage/{{ $c->options->image }}" style="height: 70px;" class="mr-2">
											<p class="small">
												<span class="font-weight-bold">{{ $c->name }}</span><br>

												@if( $c->options->sale_type == 'al-mayor' )
													<span>1 {{ $c->model->inventory->unit_type }} de {{ $c->model->inventory->qty_per_unit }} productos</span>
												@endif
											</p>
										</div>

										<button class="btn btn-danger eliminar d-sm-none" onclick="delete_item('{{$c->rowId}}')" style="position: absolute; top: 4px; right: 4px;">
												<i class="fas fa-trash" style="font-size: 1.5em;"></i>
										</button>
									</div>

									<div class="col-md-2 col-sm-6 col-xs-6 col-12 order-2 padrecantidad">
										<button class="btn btn-danger eliminar d-none d-sm-block d-md-none" onclick="delete_item('{{$c->rowId}}'')" style="position: relative; top: 4px; left: 84%;">
												<i class="fas fa-trash" style="font-size: 1.5em;"></i>
											</button>
										<p class="text-muted small text-center">CANTIDAD</p>
										<div class="input-group mb-3 padre mx-auto" id="carrito-cantidades">
											<div class="input-group-prepend">
												<button class="btn btn-primary btn-sm" onclick="substract('{{$c->rowId}}')"><i class="fas fa-angle-down"></i></button>
											</div>
											<input type="text" onkeypress="soloNumeros(event)" class="form-control input-cantidad sinflechas-{{$c->rowId}} rounded-0" value="{{ $c->qty }}" min="1" data-carrito="{{$c->rowId}}">
											<div class="input-group-append">
												<button class="btn btn-primary btn-sm" onclick="add('{{$c->rowId}}')"><i class="fas fa-angle-up"></i></button>
											</div>
										</div>
									</div>
									
									<div class="col-md-3 col-sm-6 col-12 order-3 padreprecio">
										<p class="text-muted small text-center">PRECIO Bs</p>

										@if($c->options->sale_type == 'al-mayor')
										@php
										$ivaprimero = $c->options->wholesale_iva_amount * $c->model->inventory->qty_per_unit;
										$subtotal = $c->options->wholesale_packet_price;
										$iva = $ivaprimero * $c->qty;

										$totalSinIva += $subtotal;
										@endphp
										<p class="small text-center">
											<span class="font-weight-bold precio-{{$c->rowId}}">{{ number_format($subtotal, 2, ',', '.') }}</span>
											<br>
											<span class="iva_product iva_product-{{$c->rowId}}">Iva: {{ number_format($iva, 2, ',', '.') }}</span>
										</p>
										@else
										<p class="small text-center">
											<span class="font-weight-bold precio-{{$c->rowId}}">{{ number_format($subtotal, 2, ',', '.') }}</span>
											<br>
											<span class="iva_product iva_product-{{$c->rowId}}">Iva: {{ number_format($iva, 2, ',', '.') }}</span>
										</p>
										@endif
										@if($c->options->sale_type == 'al-mayor')
											<input type="hidden" class="preciosiniva" value="{{ $c->options->wholesale_packet_price }}">
											<P class="text-muted small text-center">
												<span class="preciopvp">{{ number_format(($c->options->wholesale_total_packet_price + $iva), 2, ',', '.') }}</span> Bs
											</P>
											<br>
											{{--<span class="text-muted small">
												<span>IVA {{ $c->options->iva }}%: <span class="iva">{{  number_format($c->options->wholesale_iva_amount, 2, ',', '.') }}</span> Bs</span>
											</span>--}}
											<br>
										@else
											<input type="hidden" class="preciosiniva" value="{{ $c->options->cost }}">
											<p class="text-center">
											<span class="text-muted small">
												<span class="preciopvp">{{ number_format($c->price, 2, ',', '.') }}</span> Bs
											</span>
											</p>
											<!--<br>-->
											{{-- <span class="text-muted small">
												<span>IVA {{ $c->options->iva }}%: <span class="iva">{{ number_format($c->options->retail_iva_amount, 2, ',', '.') }}</span> Bs</span>
											</span> --}}
											<!--<br>-->
										@endif
									</div>
									
									<div class="col-md-2 col-sm-5 col-12 order-4">
										<p class="text-muted small text-center">DESCRIPCIÓN</p>
										<p class="font-weight-normal precio text-center">{{ $c->model->inventory->description }}</p>
										<br>
										@if($c->options->sale_type == 'al-mayor')
										<p class="font-weight-bold small">Disponibles para la venta: {{ $c->model->inventory->quantity }}</p>
										@else
										<p class="font-weight-bold small">Disponibles para la venta: {{ floor($c->model->inventory->total_qty_prod) }}</p>
										@endif
									</div>
									{{-- <div class="col-md-2 col-sm-6 col-12">
										<p class="text-muted small">CALIFICAR</p>
										<input name="input-2" value="2.4" class="star-rating kv-ltr-theme-fas-star rating-loading" data-size="xs">
									</div> --}}

									<div class="col-md-1 col-sm-1 col-12 order-0 order-sm-5 d-none d-md-block">
										<div class="w-100 text-center">
											<button class="btn btn-danger eliminar" onclick="delete_item('{{$c->rowId}}')" style="position: absolute; top: 4px; right: 4px;">
												<i class="fas fa-trash" style="font-size: 1.5em;"></i>
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
							<i class="fas fa-trash mr-2"></i>Vaciar carro
						</button>
					@endif
					<a href="/home" class="btn btn-primary">
						<i class="fas fa-cart-plus mr-2"></i>Agregar más
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
				<div class="card-body" style="font-size: 0.9em;">
					<div class="row mb-1 no-gutters">
						<div class="col-4">
							<span>Subtotal:</span>
						</div>
						<div class="col-8 text-right">
							<span><span id="totalSinIva">{{ number_format($totalSinIva, 2, ',', '.') }} </span> Bs.</span>
						</div>
					</div>
					<div class="row mb-1 no-gutters">
						<div class="col-5">
							<span>IVA Total:</span>
						</div>
						<div class="col-7 text-right">
							<span><span id="ivatotal">{{ number_format($ivatotal, 2, ',', '.') }}</span> Bs</span>
						</div>
					</div>
					<div class="row mb-1 no-gutters">
						<div class="col-4">
							<span class="font-weight-bold">Total:</span>
						</div>
						<div class="col-8 text-right">
							<span class="font-weight-bold"><span id="montoTotal">{{ $total }}</span> Bs</span>
						</div>
					</div>
					<div class="row mb-3 no-gutters">
						<div class="col-4">
							<span class="font-weight-bold">Total dolares:</span>
						</div>
						<div class="col-8 text-right">
							<span class="font-weight-bold"><span id="montoDolares"></span> $</span>
						</div>
					</div>	
					<div class="row mb-3 no-gutters">
						<div class="col-4">
							<span class="font-weight-bold">Precio actual del dolar:</span>
						</div>
						<div class="col-8 text-right">
							<span class="font-weight-bold"><span id="dolar">{{ number_format($dolar->price, 2, ',', '.') }}</span> Bs</span>
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
						<input type="hidden" name="dolar" value="{{$dolar->id}}">
						<input type="hidden" name="monto" id="monto">
						<input type="hidden" name="user_address_delivery" id="user_address_delivery">
						<input type="hidden" name="numero_referencia" id="numero_referencia">
						<!--<input type="file" name="capture_payment" class="custom-file-input" accept="image/*,application/pdf" id="capture_payment" style="height: 1px; width: 1px">-->

						<div class="row mb-4">
							<div class="col">
								<label for="delivery" class="text-success">¿Desea el servicio delivery?</label><br>
								<i>Solo se hará delivery en el Municipio Sucre.</i>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" onchange="changeRadio(this.value)" onclick="openModalDelivery()" class="form-check-input" name="delivery" value="si">Si
									</label>
								</div>
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" onchange="changeRadio(this.value)" class="form-check-input" name="delivery" value="no">No
									</label>
								</div>
								<i id="address" class="d-none">Puede retirar en: Aragua, Cagua, Centro de Cagua, Calle Sabana Larga entre Rondon y Mariño Local 1 N° 104-10-19 Cerca de las Terrazas.</i>

								<i id="address-descrip" class="d-none"></i>
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
							<div class="col-12" id="detallesescondidos-2">
								<div class="row">
									<div class="col-5">
										<p class="font-weight-bold">Sector:</p>
									</div>
									<div class="col">
										<p class="text-right" id="sector_info">...</p>
									</div>
								</div>
								{{-- <div class="row">
									<div class="col-5">
										<p class="font-weight-bold">Tiempo estimado:</p>
									</div>
									<div class="col">
										<p class="text-right" id="stimatedtime_info">...</p>
									</div>
								</div> --}}
								<div class="row">
									<div class="col-5">
										<p class="font-weight-bold">Detalles:</p>
									</div>
									<div class="col">
										<p class="text-right" id="detalles_info">...</p>
									</div>
								</div>
								{{-- <div class="row">
									<div class="col-5">
										<p class="font-weight-bold">Tarifa:</p>
									</div>
									<div class="col">
										<p class="text-right"><span id="tarifa_info">...</span> Bs</p>
									</div>
								</div> --}}
							</div>
						</div>

						<hr>

						<div class="row mb-4">
						
							<div class="col">
								<label for="pay_method">Selecciona el método de pago</label>
								<div class="form-check">
									<label class="form-check-label">
										<input id="pay_method" type="radio" onclick="openModanPayMethod()" class="form-check-input" name="pay_method" value="2">Tranferencias bancarias ó pago movil
									</label>
								</div>

								<div class="form-check">
									<label class="form-check-label">
										<input id="pay_method-2" type="radio" class="form-check-input" name="pay_method" value="1">Dolares (efectivo)
									</label>
								</div>
							</div>
						</div>

						<div class="row d-none detallesescondidos2">
							<div class="col-12">
								<div class="row">
									<div class="col-8">
										<p class="font-weight-bold">N° Operación:</p>
									</div>
									<div class="col-4">
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
				<h6 class="font-weight-light">Información bancaria</h6>
				@forelse($cb as $c)
					@if(isset($c->name_enterprise) && isset($c->account_number) && isset($c->bank) && isset($c->dni))
					<div class="card-body">
						<h5><b>Transferencias:</b></h5>
						<div class="row">
							<h6 class="col-3"><b>Nombre: </b> <i id="name-{{$loop->iteration}}">{{ $c->name_enterprise }}</i></h6>
							<h6 class="col-6"><b>Cuenta: </b> <i id="account-{{$loop->iteration}}">{{ str_replace("-", "", $c->account_number) }} </i> <i class="far fa-copy text-primary" data-toggle="tooltip" title="Copiar" onclick="copiarAlPortapapeles('account-{{$loop->iteration}}')"></i></h6>
							<h6 class="col-3"><b>Banco: </b> <i id="code-{{$loop->iteration}}">{{ $c->bank }}</i> <i class="far fa-copy text-primary" data-toggle="tooltip" title="Copiar" onclick="copiarAlPortapapeles('code-{{$loop->iteration}}')"></i></h6>
							<h6 class="col-6"><b>Cedula: </b><i id="dni-{{$loop->iteration}}">{{ $c->dni }}</i> <i class="far fa-copy text-primary" data-toggle="tooltip" title="Copiar" onclick="copiarAlPortapapeles('dni-{{$loop->iteration}}')"></i></h6>
	
						</div>
					</div>
					@endif
					@if(isset($c->phone_enterprise) && isset($c->dni) && isset($c->code))
					<div class="card-body">
						<h5><b>Pago movil:</b></h5>
						<div class="row">
							<h6 class="col-6"><b>Telf: </b><i id="phone-{{$loop->iteration}}">{{ str_replace("-", "", $c->phone_enterprise) }}</i> <i class="far fa-copy text-primary" data-toggle="tooltip" title="Copiar" onclick="copiarAlPortapapeles('phone-{{$loop->iteration}}')"></i></h6>
							<h6 class="col-6"><b>Cedula: </b><i id="dni-{{$loop->iteration}}">{{ $c->dni }}</i> <i class="far fa-copy text-primary" data-toggle="tooltip" title="Copiar" onclick="copiarAlPortapapeles('dni-{{$loop->iteration}}')"></i></h6>
							<h6 class="col-3"><b>Código: </b> <i id="code-{{$loop->iteration}}">{{ $c->code }}</i> <i class="far fa-copy text-primary" data-toggle="tooltip" title="Copiar" onclick="copiarAlPortapapeles('code-{{$loop->iteration}}')"></i></h6>
						</div>
					</div>
					@endif
				@empty
					<div class="card-body">
						<div class="row">
							<b>No hay información disponible</b>
						</div>
					</div>
				@endforelse
					
				<hr>	

				<h6 class="font-weight-light">Información del pago</h6>
				<div class="row">
					<div class="col-12">


						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item">
							  <a class="nav-link active" id="attach-file-tab" data-toggle="tab" href="#attach-file" role="tab" aria-controls="attach-file" aria-selected="true">Agregar captura</a>
							</li>
							<li class="nav-item">
							  <a class="nav-link" id="reference-number-tab" data-toggle="tab" href="#reference-number" role="tab" aria-controls="reference-number" aria-selected="false">Agregar referencia</a>
							</li>
						  </ul>
						  <div class="tab-content" id="myTabContent">
							<div class="tab-pane fade show active" id="attach-file" role="tabpanel" aria-labelledby="attach-file-tab">
								<b>Agregar captura</b><br>
								<div class="file-input-wrapper">
									<img class="img-fluid img-thumbnail shadow" style="height: 200px; display: none" id="foto">
									<p id="image_name" class="mt-3 mb-1"></p>
									<p id="image_weigth" class="mb-3"></p>
		
									<p id="imgerror" class="text-danger" style="display: none;"></p>

									<button id="clearbtn" type="button" class="btn btn-primary" style="display: none"><i class="fas fa-trash mr-2"></i>Limpiar</button>
									<label for="fileattached" class="btn btn-primary mb-0"><i class="fas fa-folder-open mr-2"></i>Buscar</label>
									<input id="fileattached" name="fileattached" type="file" accept="image/*,application/pdf" class="form-control-file" hidden>
								</div>		
							</div>
							<div class="tab-pane fade" id="reference-number" role="tabpanel" aria-labelledby="reference-number-tab">
								<div class="col-12">
									<label for="referencia" class="font-weight-bold">Agregar referencia</label>
									<input type="number" id="referencia" placeholder="Colocar los últimos 4 digitos" class="form-control w-100" name="referencia">
								</div>
							</div>
						  </div>
					</div>
					<!-- <div class="col-12 text-center col-md-7">
						<label for="customFile">Captura de la operación</label>
						<label id="btn_search" class="btn btn-primary" for="customFile"><i class="fas fa-folder-open mr-2"></i>Buscar</label>
					</div> -->
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
				<button type="button" id="listo2" data-dismiss="modal" class="btn btn-primary"><i class="fas fa-check mr-2"></i>Listo</button>
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
					{{-- <div class="col-12">
						<label for="state_id">Estado</label><br>
						<select required name="state_id" data-live-search="true" class="selectpicker mb-2 border form-control" data-width="100%" id="state_id"></select>
						@foreach ($collection as $item)
							
						@endforeach
					</div> --}}
					
					<div class="col-12">
						<label for="forma_delivery">Seleccione una forma de colocar la direccion para el delivery</label><br>
						<select name="forma_delivery" class="selectpicker mb-2 border form-control" id="forma-delivery">
							<option value="" id="select-option-forma">Seleccione una forma</option>
							<option value="1">Escribir la direccion</option>
							<option value="2">Buscar mi direccion</option>
						</select>
					</div>

					<div id="direc-descrip-caja" class="w-100">
						<div class="col-12">
							<textarea name="direc_descrip_area" id="direc-descrip-area" cols="30" rows="5" class="form-control" maxlength="255" ></textarea>
						</div>
					</div>

					<div id="select-multiples" class="w-100">
						<div class="col-12">
							<label for="city_id">Ciudad</label><br>
							<select required name="city_id" data-live-search="true" class="selectpicker mb-2 border form-control" data-width="100%" id="city_id">
								<option value="0" selected>Seleccione Ciudad</option>
							@foreach ($cities as $city)
								<option value="{{$city->id}}">{{$city->city}}</option>
							@endforeach
							</select>
						</div>
						<div class="col-12">
							<label for="sector_id">Sector</label><br>
							<select required name="sector_id" data-live-search="true" class="selectpicker mb-2 border form-control" data-width="100%" id="sector_id">
								<option value="0">Seleccione Sector</option>
							</select>
						</div>
						<div class="col-12">
							<label for="detalles">Dirección exacta</label><br>
							<input type="text" class="form-control" name="detalles" id="detalles" placeholder="Calle, Número de Casa, Algún punto de referencia.">
						</div>
					</div>
				</div>
				
				<div id="caja-direc-anteriores" style="overflow: scroll; max-height: 150px;">
					<b style="font-size: 1.3em; margin-top: 5%;">Direcciones anteriores:</b>
					@foreach($user_address as $address)
					<div class="list-group">
					  <button type="button" class="list-group-item list-group-item-action direc-list" value="{{$address->id}}" name="direc_list" id="direc-list-{{$address->id}}">{{$address->details}} {{ $address->travel_rate_id ? ", ".$address->travel_rate->sector->sector : "" }} {{ $address->travel_rate_id ? $address->travel_rate->rate : "" }}
					  </button>
					</div>
					@endforeach
					<input type="text" class="form-control" id="input-direc-anteriores" name="input_direc_anteriores" hidden>
					<input type="text" class="form-control" id="input-text-direc-anteriores" name="input_text_direc_anteriores" hidden>
				</div>

				<div class="row" id="">
					
					<div class=" mx-auto py-5 sr-only" id="direccion_loader">
						<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status"></div>
					</div>

					<div class="col-12 d-none" id="direccion_content">
						{{-- <div class="row">
							<div class="col-4">
								<p class="font-weight-bold">Tiempo estimado:</p>
							</div>
							<div class="col">
								<p id="stimatedtime">...</p>
							</div>
						</div> --}}
						{{-- <div class="row">
							<div class="col-4">
								<p class="font-weight-bold">Tarifa:</p>
							</div>
							<div class="col">
								<p><span id="tarifa">0</span> Bs</p>
							</div>
						</div> --}}
						{{-- <div class="row">
							<div class="col-4">
								<p class="font-weight-bold">Detalles:</p>
							</div>
							<div class="col">
								<p id="detalles">...</p>
							</div>
						</div> --}}
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="listo" class="btn btn-primary"><i class="fas fa-check mr-2"></i>Listo</button>
			</div>
		</div>
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
				<p class="text-center font-weight-light">¿Estás seguro de querer eliminar los productos?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
				<button type="button" id="limpiar" data-dismiss="modal" class="btn btn-primary"><i class="fas fa-check mr-2"></i>Sí, estoy seguro</button>
			</div>
		</div>
	</div>
</div>




@endsection

@push('scripts')
<script>
	var myCart = @json($cart);
	var dolar = @json($dolar->price)

	function openModanPayMethod(){
		
			$('#pay_method_modal').modal('show');
		}


	function changeRadio(val) {
		if ($('#pay_method').val() == "dolares") {
			$('#enviarPago').attr('disabled', false)
		}


		if (val == 'si') {


			//$('#detalles').val('');//VACIA EL CAMPO DIRECCION EXACTA

			$('#address').addClass('d-none').removeClass('d-block')

		} else {
			$('#address').addClass('d-block').removeClass('d-none')
			$('#address-descrip').addClass('d-none').removeClass('d-block')
			$('#address-descrip').text("")

			$('#detallesescondidos').addClass('d-none').removeClass('d-block')
			$('#detallesescondidos-2').addClass('d-none').removeClass('d-block')
				
		}
	}

	function openModalDelivery() {
		$('#modalDelivery').modal('show')
	}

	function substract(id) {
		if (myCart[id].qty == 1) {
			return
		}
		myCart[id].qty = parseInt(myCart[id].qty) - 1;
		$('.sinflechas-'+id).val(myCart[id].qty)

		let data = {"qty": $('.sinflechas-'+id).val()}

		
		$.ajax({
		    type: 'PUT',
		    url: '/shoppingcart/'+ id,
		    data: data, // access in body
		}).done(function (response) {

		    console.log(response);
		    subtotal_item(id)
			subtotal()
		}).fail(function (e) {

		    console.log(e);
		});
	}

	function delete_item(id) {
		console.log(id, myCart[id])
		delete myCart[id]
		console.log(myCart)
		subtotal()
	}

	function add(id) {
		myCart[id].qty = parseInt(myCart[id].qty) + 1
		if (myCart[id].qty >= parseInt(myCart[id].options.wholesale_qty)) {

		}
		$('.sinflechas-'+id).val(myCart[id].qty)

		let data = {"qty": $('.sinflechas-'+id).val()}

		
		$.ajax({
		    type: 'PUT',
		    url: '/shoppingcart/'+ id,
		    data: data, // access in body
		}).done(function (response) {

		    console.log(response);
		    subtotal_item(id)
			subtotal()
		}).fail(function (e) {

		    console.log(e);
		});
		
		
	}
	//CHANGE PARA EL INPUT DE LAS CANTIDADES
	$('.input-cantidad').change(function(e){
		
		let id = e.target.attributes[5].value
		//$('.sinflechas-'+id).val(myCart[id].qty)
		let data = {"qty": e.target.value}
	
		$.ajax({
		    type: 'PUT',
		    url: '/shoppingcart/'+ id,
		    data: data, // access in body
		}).done(function (response) {

		    console.log(response);
		    subtotal_item(id)
			subtotal()
		}).fail(function (e) {

		    console.log(e);
		});

		
		
	});


	function subtotal_item(id) {
		// console.log(typeof myCart[id].price, typeof parseInt(myCart[id].options.retail_iva_amount), typeof myCart[id].qty)
		myCart[id].subtotal = (myCart[id].price - parseInt(myCart[id].options.retail_iva_amount)) * myCart[id].qty
		if(myCart[id].options.sale_type == "al-menor"){

			myCart[id].iva = myCart[id].options.retail_iva_amount * myCart[id].qty
		}else{
			myCart[id].iva = (myCart[id].options.wholesale_iva_amount * myCart[id].options.wholesale_quantity) * myCart[id].qty
		}

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
			//console.log("carrito "+myCart[cart])
			if (myCart[cart].options.sale_type == "al-mayor") {

				subtotal += (myCart[cart].price * parseInt(myCart[cart].qty))
	
				iva += parseInt((myCart[cart].options.wholesale_iva_amount * myCart[cart].options.wholesale_quantity) * myCart[cart].qty)

				total += (myCart[cart].price + parseInt((myCart[cart].options.wholesale_iva_amount * myCart[cart].options.wholesale_quantity))) * myCart[cart].qty

			}else if(myCart[cart].options.sale_type == "al-menor"){

			total += myCart[cart].price * myCart[cart].qty
			// console.log(myCart[cart].price, myCart[cart].qty, parseInt(myCart[cart].options.retail_iva_amount))
			subtotal += (myCart[cart].price * parseInt(myCart[cart].qty)) - (parseInt(myCart[cart].options.retail_iva_amount) * parseInt(myCart[cart].qty))
			// console.log("subtotal: ", subtotal)
			iva += parseInt(myCart[cart].options.retail_iva_amount * myCart[cart].qty)
			}


			
		}
		console.log("total: ", subtotal)
		console.log("total: ", total)
		$('#totalSinIva').text(new Intl.NumberFormat('de-DE').format(subtotal)+',00')

		$('#ivatotal').text(new Intl.NumberFormat('de-DE').format(iva)+',00')
		$('#montoTotal').text(new Intl.NumberFormat('de-DE').format(total)+',00')
		$('#montoDolares').text(new Intl.NumberFormat('de-DE').format(total / dolar))
		//$('#montoDolares').text(dolar);
		$('#monto').val(total)
	}
	
	$(() => {
		subtotal()
		
		@if ($errors->any())
			@foreach ($errors->all() as $error)
				toastr.error("{{ $error }}")
            @endforeach
		@endif()

		$('#state_id').on('change', function() { 
			var state_id = $('#state_id').val(); 
			$.ajax({
				url: "city/"+state_id,
				type: "GET",
				dataType: "json",
				error: function(element){
				//console.log(element);
				}, 
				success: function(respuesta){
					$("#city_id").html('<option value="" selected="true"> Seleccione una opción </option>');
					respuesta.forEach(element => {
						$('#city_id').append('<option value='+element.id+'> '+element.city+' </option>')
					});
				}
			});

		})

		$('#city_id').on('change', function() { 
			var city_id = $('#city_id').val(); 
			$.ajax({
				url: "sector/"+city_id,
				type: "GET",
				dataType: "json",
				error: function(error){
				//console.log(error);
				}, 
				success: function(respuesta){
					console.log(respuesta)
					$("#sector_id").html('<option value="" selected="true"> Seleccione una opción </option>');
					respuesta.forEach(element => {
						console.log(element)
						$('#sector_id').append('<option value='+element.id+'> '+element.sector+' </option>')
					});
					$('.selectpicker').selectpicker('refresh');
				}
			});

		})

		$('#fileattached').change((e) => {
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
				$('#fileattached').val('')
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
		// 			qty: cantidad,
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
				$('.detallesescondidos').addClass('d-none');
			} else {
				$('#sector_info').text('')
				$('#stimatedtime_info').text('')
				$('#detalles_info').text('')
				$('#tarifa_info').text('')
				$('#sector').text('')
				$('#stimatedtime').text('')
				$('#detalles').text('')
				// $('#tarifa').text('')

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

		// $('#sector_id').change(function(){

		// 	let direccion = $(this).val()

		// 	$('#direccion_loader').removeClass('sr-only')

		// 	$.get(`/direcciones/${direccion}`, (res) => {
		// 		$('#stimatedtime').text(`${res.travel_rate.stimated_time} min`)
		// 		// $('#tarifa').text(new Intl.NumberFormat('de-DE').format(res.travel_rate.rate))
		// 		$('#detalles').text(`${res.details}`)

		// 		$('#direccion_loader').addClass('sr-only')
		// 		$('#direccion_content').removeClass('d-none')
		// 	})
		// 	.catch((err) => {
		// 		toastr.error('Algo ha ocurrido.')
		// 		console.error(err)
		// 	})
		// })

		// boton listo del modal del delivery
		$('#listo').click(() => {

			if ($('#forma-delivery').val() == 2) {
				$('#address-descrip').addClass('d-none').removeClass('d-block');
				let sector        = $('#sector_id option:selected').text()
				// let stimated_time = $('#stimatedtime').text()
				// let tarifa        = $('#tarifa').text()
				let detalles      = $('#detalles').val()
				$('#detallesescondidos-2').addClass('d-block').removeClass('d-none')
				if ( ! $('#sector_id')[0].validity.valueMissing ){
					$('#user_address_delivery').val($('#sector_id').val())
					$('#sector_info').text(sector)
					$('#detalles_info').text(detalles)
					// $('#stimatedtime_info').text(stimated_time)
					// $('#tarifa_info').text(tarifa)
					// $('.delivery_cost').removeClass('d-none')
					// $('#delivery_cost').text(tarifa)
					$('.detallesescondidos').removeClass('d-none')
					$('#modalDelivery').modal('hide')
				} else {
					$('#sector_id')[0].setCustomValidity('Este campo no puede quedar vacío.')
				}
			}else if($('#forma-delivery').val() == 1){
				$('#address-descrip').text($('#direc-descrip-area').val());
				
				$('#address-descrip').addClass('d-block').removeClass('d-none');
				$('#modalDelivery').modal('hide');

				$('#detallesescondidos-2').addClass('d-none').removeClass('d-block')
			}else if ($('#forma-delivery').val() == ""){
				$('#address-descrip').text($('#input-text-direc-anteriores').val());
				$('#address-descrip').addClass('d-block').removeClass('d-none');
				$('#detallesescondidos-2').addClass('d-none').removeClass('d-block')
				$('#modalDelivery').modal('hide');
			}

			console.log($('#forma-delivery').val());
		})

	
		$('#pay_method').change(function() {
			let valor = $(this).val()
			
			if ( valor == '2' || valor == '3' ) {
				$('#enviarPago').attr('disabled', true)
				$('#pay_method_modal').modal('show')
			} else {
				for (let i = 0; i < $('input:radio[name=delivery]').length; i++) {
					console.log($('input:radio[name=delivery]')[i].checked)
					if (!$('input:radio[name=delivery]')[i].checked) {
						$('#enviarPago').attr('disabled', true)
					} else {
						$('#enviarPago').attr('disabled', false)
						break
					}
				}
			}
			
		})
		
		$('#pay_method-2').click(function() {
			$('#enviarPago').attr('disabled', false);
			$('.detallesescondidos2').addClass('d-none')//OCULTAMOS LA INFORMACION DE LA DIRECCION
			$('#referencia').val("")//VACIAMOS EL CAMPO DE REFERENCIA
			$('#numero_referencia').val("")//VACIAMOS EL CAMPO NUMERO DE REFERENCIA QUE ES EL QUE ESTA EN EL FORM
			//VACIOS EL IMPUT FILE
			$('#imgerror').text('')
			$('#fileattached').val('')
			$('#foto').hide()
			$('#image_name').text('')
			$('#image_weigth').text('')
			$('#clearbtn').hide()
		})
		

		$('#listo2').click(() => {
			//SI NO HAY CAPTURA Y SI HAY REFERENCIA
			if ($('#fileattached').val() == "" && $('#referencia').val() != "") {
				
				$('.detallesescondidos2').removeClass('d-none')
				$('#numero_referencia').val($('#referencia').val())
				$('#nooperacion').text($('#referencia').val())
				$('#enviarPago').removeAttr('disabled')
				
			}
			//SI NO HAY REFERENCIA Y HAY CAPTURA
			if ($('#referencia').val() == "" && $('#fileattached').val() != "") {
				
				
				$('.detallesescondidos2').addClass('d-none')
				$('#enviarPago').removeAttr('disabled')
				
			}
			//SI HAY REFERENCIA Y CAPTURA
			if ($('#referencia').val() != "" && $('#fileattached').val() != "") {
				$('#nooperacion').text($('#referencia').val())
				$('#enviarPago').removeAttr('disabled')

			}
			//SI NO HAY REFERENCIA Y NO HAY CAPTURA
			if ($('#referencia').val() == "" && $('#fileattached').val() == "") {

				$('.detallesescondidos2').addClass('d-none')
			}
			
			//$('#monto').val($('#montoTotal').text())
			
		})

		// $('#customFile').change(function(e) {
		// 	let reader = new FileReader()

		// 	reader.onload = () => {
		// 		$('#capture_preview').attr('data-src', reader.result)
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
		//LUIS
		$('#select-multiples').hide();

		$('#direc-descrip-caja').hide();

		$('#forma-delivery').on('change', (e) => {

			$('#select-multiples').hide();

			$('#direc-descrip-caja').hide();

			$('#caja-direc-anteriores').hide();

			if ($('#forma-delivery').val() == 2) {

				$('#select-multiples').show();
				$('#caja-direc-anteriores').hide();
			}else if($('#forma-delivery').val() == 1){

				$('#direc-descrip-caja').show();
				$('#caja-direc-anteriores').hide();
			}else if($('#forma-delivery').val() == ""){

				$('#caja-direc-anteriores').show();
			}
			console.log($('#forma-delivery').val());
		})

		//RADIO CON LAS DIRECCIONES ANTERIORES DEL MODAL

		$('.direc-list').click(function(e){

			$('#input-direc-anteriores').val(e.target.value);
			$('#input-text-direc-anteriores').val(e.target.textContent);

			$('.direc-list').removeClass('active')
			$('#direc-list-'+ e.target.value).addClass('active');
			console.log(e.target.classList);
			//console.log($('input-direc-anteriores').val());
		});
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