@extends('layouts.customer')

@section('content')


<div class="loading" id="page_loader">
	<div>
		<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status">
		</div>
		<p>Espere un momento...</p>
	</div>
</div>


<div style="margin-top: 90px"></div>

<div class="container-fluid wrapper my-5">

	<div class="row">
		<div class="col-lg-3 col-12 mb-3">
			<div class="card">
				<div class="card-body p-0">
					<div class="list-group shadow-sm" id="list-tab" role="tablist">
					
						<a class="list-group-item list-group-item-action active" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">
							<i class="fas fa-road icon-width"></i>Dirección de entregas
						</a>
						<a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list" href="#list-messages" role="tab" aria-controls="messages">
							<i class="fas fa-cubes icon-width"></i>Pedidos
						</a>
						<a class="list-group-item list-group-item-action" id="list-oferta-list" data-toggle="list" href="#list-oferta" role="tab" aria-controls="oferta">
							<i class="fas fa-coins icon-width"></i>Ofertas
						</a>
						{{-- <a class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">
							<i class="fas fa-star icon-width"></i>Valoraciones
						</a> --}}
					</div>
				</div>
			</div>

			<div class="card shadow">
												
				<div class="card-body">
				
					
					<div class="row">
						<div class="col-3 col-sm-3 col-md-1 col-lg-12 col-xl-3">
							<div class="row">
								<div class="col-lg-6 col-xl-12">
								<i class="far fa-user" style="font-size: 5em;"></i>
								</div>
								<div class="col-lg-6 col-xl-12">
								<button class="mt-2 btn btn-primary mt-3" data-toggle="modal" data-target="#modal-edit"><i class="fas fa-edit"></i></button>
								</div>
							</div>
						</div>
						<div class="col-9 col-sm-9 col-md-11 col-lg-12 col-xl-9">
							<p class="font-weight-bold h4"><span>{{ $user->people->name }}</span> <span>{{ $user->people->lastname }}</span></p>
							<p><i class="far fa-envelope" style="color: #dc3545;"></i> {{ $user->email }}</p>
							<p><i class="fas fa-phone"></i> {{ $user->people->phone_number }}</p>
							<p><i class="far fa-id-badge" style="color: #007bff;"></i> {{ $user->people->dni }}</p>
						</div>
						
					</div>
				
				</div>
			</div>
		</div>
		<div class="col-lg-9 col-12 mb-3">

			<div class="card mb-4 shadow-sm">
				<div class="card-body row p-0 text-center">
					<div class="col-6 p-3">
						<h4>{{ $pedidosCount }}</h4>Pedidos
					</div>
					<div class="col-6 border-left p-3">
						<h4>{{ $wishlistCount }}</h4>Lista de deseos
					</div>
				</div>
			</div>

			<!-- Modal PARA EDITAR EL PERFIL-->
			<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog">
			  		<div class="modal-content">
			    		<div class="modal-header">
				        	<h5 class="modal-title" id="exampleModalLabel">Editar Perfil</h5>
				        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          		<span aria-hidden="true">&times;</span>
				        	</button>
			      		</div>
				      	<div class="modal-body">
				        	
				
							<form action="editar_perfil" method="GET">

								<div class="row mb-2">
									<div class="col-lg-4 col-12 mb-3">
										<label for="dni">Cédula</label>
										<input type="hidden" name="id" value="{{ $user->id }}" readonly>
										<input type="number" readonly name="dni" value="{{ $user->people->dni }}" id="dni" class="form-control" required>
									</div>
									<div class="col-lg-8 col-12 mb-3">
										<label for="name">Nombre Completo</label>
										<input type="text" name="name" value="{{ $user->people->name }}" id="name" class="form-control" required>
									</div>
								</div>

								<div class="row mb-2">
									<div class="col-lg-6 col-12 mb-3">
										<label for="email">Correo electrónico</label>
										<input type="email" name="email" value="{{ $user->email }}" id="email" class="form-control" required>
									</div>
									<div class="col-lg-6 col-12 mb-3">
										<label for="phone">Teléfono</label>
										<input type="text" name="phone" id="phone" value="{{ $user->people->phone_number }}" class="form-control" required>
									</div>
								</div>

								<div class="row mb-4">
									<div class="col-lg-6 col-12 mb-3">
										<label for="password">Contraseña</label>
										<input type="password" autocomplete="off" name="password" id="password" class="form-control">
									</div>
									<div class="col-lg-6 col-12 mb-3">
										<label for="password_confirmation">Confirmación de Contraseña</label>
										<input type="password" autocomplete="off" name="password_confirmation" id="password_confirmation" class="form-control">
									</div>
									<div class="col-lg-12 col-12 text-right">
										<button type="submit" class="btn btn-primary">
											<i class="fas fa-edit mr-2"></i> Guardar
										</button>
									</div>
								</div>
							</form>
					

				      	</div>
			   
			   	 	</div>
			  	</div>
			</div>

			<div class="card mb-4 shadow-sm">
				<div class="card-body">
					<div class="tab-content" id="nav-tabContent">
						

						<div class="tab-pane fade show active" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
							<div class="d-flex justify-content-between mb-4 align-items-center">
								<h4 class="font-weight-light">Direcciones de entrega</h4>
								<button class="btn btn-primary" data-toggle="modal" data-target="#nuevaDireccion">
									<i class="fas fa-plus mr-2"></i>Nueva
								</button>
							</div>
							<div class="table-responsive">
								<table class="table text-center table-sm table-hover table-bordered">
									<thead>
										<tr>
											<th>N°</th>
											<th>Direccion</th>
											<th >ACCIONES</th>
										</tr>
									</thead>
									<tbody>
										@forelse($rates as $rate)
											<tr>
												<td>ADSS-00{{ $rate->id }}</td>
												<td>{{ $rate->travel_rate_id ? $rate->travel_rate->sector->sector : "" }} 
												{{ $rate->details }}
												{{ $rate->travel_rate_id ?$rate->travel_rate->rate : "" }}</td>
												<td>
													<button data-id="{{ $rate->id }}" class="btn btn-primary address_edit"><i class="fas fa-edit"></i></button>
													<form action="{{route('direcciones.destroy', $rate->id)}}" method="post" class="d-inline">
														@method('DELETE')
														@csrf
														<input type="hidden" value="{{ $rate->id }}" name="id">
													<button data-id="{{ $rate->id }}" class="btn btn-danger address_delete" type="submit"><i class="fas fa-trash"></i></button>
													</form>
												</td>
											</tr>
										@empty
											<tr>
												<td colspan="3" class="text-center">No hay datos registrados.</td>
											</tr>
										@endforelse
									</tbody>
								</table>
							
							</div>
						</div>

						<div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">

							
							<div class="row mb-4">
								<div class="col d-flex justify-content-between">
									<h4 class="font-weight-light">Compras realizadas</h4>
								</div>
							</div>

							<div class="table-responsive">
								<table class="table text-center table-bordered table-sm table-hover">
									<thead class="">
										<th class="d-none d-md-table-cell small font-weight-bold">ID COMPRA</th>
										<th class="d-none d-md-table-cell small font-weight-bold">PRODUCTOS</th>
										<th class="small font-weight-bold">MONTO (Bs)</th>
										<th class="d-md-table-cell small font-weight-bold">DELIVERY</th>
										<th  class=" small font-weight-bold">Estado</th>
										<th class="small font-weight-bold">ACCIONES</th>
									</thead>
									<tbody>
										@forelse($pedidos as $compra)
											<tr>
												<td class="d-none d-md-table-cell">FC-000{{ $compra->id }}</td>
												<td class="d-none d-md-table-cell">{{ count($compra->details) }}</td>
												<td>{{ number_format( $compra->amount, 2, ',', '.') }} <br><small class="font-weight-bold" style="color: #008080">{{number_format( $compra->amount/$compra->dolar->price, 2, ',', '.')}}$</small></td>
												@if(ucfirst($compra->delivery) == "No")
												<td class="d-md-table-cell">{{ ucfirst($compra->delivery) }}</td>
												@else
													@if($compra->stimated_time != null)
													<td>{{$compra->stimated_time}}min</td>

													@else
													<td >{{ ucfirst($compra->delivery) }}</td>
													@endif
												@endif
												@if ($compra->dispatched != null)
											
													@if($compra->confirmacion == "denegado")
															
													<td class="font-weight-bold small">{{$compra->confirmacion}}
													<button type="button" class="ml-2 btn btn-danger" data-toggle="modal" data-target="#modal-denegado">
														<i class="fas fa-info"></i>
													</button>
													</td>
													@else
														@if($compra->delivery == "si")
														<td class="font-weight-bold">{{\Carbon\Carbon::createFromTimeStamp(strtotime($compra->dispatched))->diffForHumans()}}</td>
														@else
														<td><label class="font-weight-bold small">Puede retirar 
														<button type="button" class="ml-2 btn btn-primary" data-toggle="modal" data-target="#modal-direccion">
														<i class="fas fa-info"></i>
														</button>
														</label></td>
														@endif
													@endif
												@else
												<td class=""><label class="font-weight-bold small">Esperando confirmación</label></td>
												@endif
												
												<td>
													<!--
													<button data-toggle="modal" data-id="{{ $compra->id }}" data-target="#detalles" class="btn btn-primary detalle"><i class="fas fa-info"></i></button>
													-->
													<button type="button" class="btn btn-primary d-md-none" data-toggle="modal" data-target="#exampleModal4-{{ $compra->id }}"><i class="fas fa-info"></i></button>
													<a class="btn btn-danger" target="_blank" href="{{route('factura.pdf', ['id' => $compra->id])}}"><i class="fas fa-file-alt" style="color: #ffffff"></i></a>

												</td>
											</tr>

											<!-- Modal MOVIL INFORMACION DE COMPRAS-->
											<div class="modal fade" id="exampleModal4-{{ $compra->id }}" tabindex="-1" aria-labelledby="exampleModalLabel4" aria-hidden="true">
												<div class="modal-dialog">
											    	<div class="modal-content">
											    		<div class="modal-header">
											        		<h5 class="modal-title" id="exampleModalLabel">Informacion de la compra</h5>
											        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											          			<span aria-hidden="true">&times;</span>
											        		</button>
											      		</div>
											      		<div class="modal-body">
											      			<ul class="list-group">
													  			<li class="list-group-item"><span class="font-weight-bold">ID COMPRA: </span>FC-000{{ $compra->id }}</li>
																<li class="list-group-item"><span class="font-weight-bold">PRODUCTOS: </span>{{ count($compra->details) }}</li>
																<li class="list-group-item"><span class="font-weight-bold">MONTO (Bs): </span>{{ $compra->amount }}</li>
																<li class="list-group-item"><span class="font-weight-bold">MONTO ($): </span>{{ number_format( ($compra->amount/$compra->dolar->price), 2, ',', '.') }}</li>
																<li class="list-group-item"><span class="font-weight-bold">DELIVERY: </span>
																	@if(ucfirst($compra->delivery) == "No")
																	<span class="d-md-table-cell">{{ ucfirst($compra->delivery) }}</span>
																	@else
																		@if($compra->stimated_time != null)
																		<span>{{$compra->stimated_time}}min</span>

																		@else
																		<span class=" d-md-table-cell">{{ ucfirst($compra->delivery) }}</span>
																		@endif
																	@endif
																</li>
																@if($compra->dispatched != null)
																<li class="list-group-item"><span class="font-weight-bold">CONFIRMADO: </span>{{\Carbon\Carbon::createFromTimeStamp(strtotime($compra->dispatched))->diffForHumans()}}</li>
																@else
																<li class="list-group-item"><span class="font-weight-bold">CONFIRMADO: </span>No</li>
																@endif
																
															</ul>
												       	</div> 
											      		<div class="modal-footer">
											       			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											      		</div>
											    	</div>
											  	</div>
											</div>
										@empty
											<tr>
												<td colspan="5">No hay información para mostrar.</td>
											</tr>
										@endforelse

										<!-- Modal -->
										<div class="modal fade" id="modal-direccion" tabindex="-1" aria-labelledby="modal-direccion" aria-hidden="true">
											<div class="modal-dialog">
										    	<div class="modal-content">
										    		<div class="modal-header">
										        		<h5 class="modal-title" id="exampleModalLabel">Dirección</h5>
										        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										        		<span aria-hidden="true">&times;</span>
										        		</button>
										    		</div>
											    	<div class="modal-body">
											        <p>Puede retirar en: Aragua, Cagua, Centro de Cagua, Calle Sabana Larga entre Rondon y Mariño Local 1 N° 104-10-19 Cerca de las Terrazas.</p>
											      	</div>
											      	<div class="modal-footer">
											        	
											        	<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
											      	</div>
										    	</div>
										  	</div>
										</div>

										<!-- Modal DENEGADO -->
										<div class="modal fade" id="modal-denegado" tabindex="-1" aria-labelledby="modal-denegado" aria-hidden="true">
											<div class="modal-dialog">
										    	<div class="modal-content">
										    		<div class="modal-header">
										        		<h5 class="modal-title" id="exampleModalLabel">Informacion</h5>
										        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										        		<span aria-hidden="true">&times;</span>
										        		</button>
										    		</div>
											    	<div class="modal-body">
											        <p>Para más informacion comuniquese con nosotros via whatsapp <br> +58-424-337-2191</p>
											      	</div>
											      	<div class="modal-footer">
											        	
											        	<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
											      	</div>
										    	</div>
										  	</div>
										</div>

									</tbody>
								</table>
								
							</div>
						</div>
						
						<!--APARTADO PARA LAS OFERTAS-->

						<div class="tab-pane fade show" id="list-oferta" role="tabpanel" aria-labelledby="list-oferta-list">
							<div class="row justify-content-between">
								<div class="col-md-3">
									<h4 class="font-weight-bold">Ofertas</h4>
								</div>
								<div class="col-md-3">
									<ul class="nav nav-pills">
										<li class="nav-item">
											<a class="nav-link {{ Request::get('buyType') == 'major' ? 'active' : 'normal' }}" href="{{url('/perfil/?buyType=major')}}">Al mayor</a>
										</li>
										<li class="nav-item">
											<a class="nav-link {{ Request::get('buyType') != 'major' ? 'active' : 'normal' }}" href="{{url('/perfil/?buyType=minor')}}">Al menor</a>
										</li>
									</ul>
					
								</div>
							</div>

							<hr>
							
							<div class="row">
								@foreach($productos as $producto)
								<div class="col-md-3">

								@if(Request::get('buyType') != 'major')

									<!--LOGICA PARA CAMBIAR EL ICONO DE LOS BOTONES CUANDO YA ESTA EN EL CARRITO EL PRODUCTO-->
									@php
									if (isset($carrito)) {
										foreach ($carrito as $item) {
											if ($item->options->sale_type == "al-menor") {
												# code...
												$respuesta = Illuminate\Support\Arr::get($item, 'model.id', 0);
											}	
															
										}
									}
									@endphp

									<div class="card shadow">
										<div class="card-body body-producto">
											
											<img style="height: 200px; object-fit: contain" data-src="{{ url('storage/'.$producto->image) }}" class="card-img-top">
											<div class="card-body body-producto" id="body-producto">
												@if($producto->oferta == 1)
												<span class="badge badge-danger mb-2" style="font-size: 1.5em;">Oferta</span>
												@endif
												<h5 class="card-title font-weight-bold truncated-text text-center">{{ $producto->inventory->product_name }}</h5>

												{{-- <input name="star-rating" value="3.4" class="kv-ltr-theme-fas-star star-rating rating-loading" data-size="xs"> --}}
												<h6 class="font-weight-normal truncated-text text-center">Subtotal: <span >{{number_format($producto->retail_total_price - $producto->retail_iva_amount, 2, ',', '.') }}</span></h6>
												<h6 class="font-weight-normal truncated-text text-center small">Iva: <span class="">{{ number_format($producto->retail_iva_amount, 2, ',', '.') }}</span></h6> 
												
												<p class="lead font-weight-light truncated-text text-center"  style="margin-bottom: 0px;">{{ number_format($producto->retail_total_price, 2, ',', '.') }} Bs</p>

												<p class="text-right text-success">Dolares:{{ number_format($producto->retail_total_price / $dolar->price, 2, ',', '.')}}$</p>	

														
												<div class="">
													
															<button 
																id="deseos-{{ $producto->id }}"
																data-id="{{ $producto->id }}" 
																class="btn btn-block addToWishlist"
																data-producto="{{ $producto->inventory->product_name }}"
																data-precio="{{ $producto->retail_total_price }}"

															>
																<i class="fa fa-heart" style="color: #dc3545;"></i>
																<b for="deseos-{{ $producto->id }}" class="text-danger">Lista de Deseos</b>
															</button>
															
														
														@if(isset($respuesta) && $respuesta != 0)
														
															<button
																id="comprar-{{ $producto->id }}"
																type="button"
																class="text-center btn btn-block btn-primary addCartBtn"
																data-id="{{ $producto->inventory->id }}"
																data-producto="{{ $producto->inventory->product_name }}"
																data-precio="{{ $producto->retail_total_price }}"
																data-type="al-menor"
																data-cantidad="1"
															>
																<i class="fas fa-check" ></i>
															</button>
															
														
														
														@else
													
															<button
																id="comprar-{{ $producto->id }}"
																type="button"
																class="text-center btn btn-block btn-primary addCartBtn"
																data-id="{{ $producto->inventory->id }}"
																data-producto="{{ $producto->inventory->product_name }}"
																data-precio="{{ $producto->retail_total_price }}"
																data-type="al-menor"
																data-cantidad="1"
															>
																<i class="fas fa-shopping-cart" style="color: #007bff;"></i>
																<b for="comprar-{{ $producto->id }}" class="texto-carrito">Comprar<b>
															</button>
															
														
														@endif
												
											</div>
													

											</div>
										</div>
									</div>
								@else
								<!--PRODUCTOS AL MAYOR-->
								<!--LOGICA PARA CAMBIAR EL ICONO DE LOS BOTONES CUANDO YA ESTA EN EL CARRITO EL PRODUCTO-->
								@php
								if (isset($carrito)) {
									foreach ($carrito as $item) {
										if ($item->options->sale_type == "al-mayor") {
											# code...
											$respuesta = Illuminate\Support\Arr::get($item, 'model.id', 0);
										}	
														
									}
								}
								@endphp

								<div class="card shadow-sm">
									
									<img style="height: 200px; object-fit: contain" data-src="{{ url('storage/'.$producto->image) }}" class="card-img-top">
									<div class="card-body body-producto">
										@if($producto->oferta == 1)
										<span class="badge badge-danger mb-2" style="font-size: 1.5em;">Oferta</span>
										@endif
										<h5 class="card-title font-weight-bold text-center">{{ $producto->inventory->product_name }}</h5>

										<p class="text-center">
											<span class="font-weight-bold">Unidad: </span>{{ ucfirst($producto->inventory->unit_type) }}<br>
											<span class="font-weight-bold">Cantidad: </span>{{ $producto->inventory->qty_per_unit }} <br>
											<span class="font-weight-bold">Precio por unidad: </span>{{ number_format($producto->wholesale_total_individual_price, 2, ',', '.') }} <br>
											<span class="font-weight-bold">Subtotal: </span>{{ number_format(($producto->wholesale_packet_price), 2, ',', '.') }} <br>
											<span class="font-weight-bold small">Iva: </span>{{number_format($producto->wholesale_iva_amount * $producto->inventory->qty_per_unit, 2, ',', '.')  }} <br>
										</p>

										<p class="lead font-weight-normal text-center">Total: {{ number_format($producto->wholesale_total_packet_price + ($producto->wholesale_iva_amount * $producto->inventory->qty_per_unit), 2, ',', '.') }} Bs</p>

										<p class="text-right text-success">Dolares:{{ number_format($producto->wholesale_total_packet_price / $dolar->price, 2, ',', '.')}}$</p>
		
												<button id="deseos-{{ $producto->id }}" data-id="{{ $producto->inventory->id }}" class="btn btn-block mb-2 addToWishlist">
													<i class="fa fa-heart" data-toggle="tooltip" data-title="Agregar a favoritos" style="color: #dc3545;"></i>
													<b for="deseos-{{ $producto->id }}" class=" text-danger">Lista de deseos</b>
												</button>
											
											@if(isset($respuesta) && $respuesta != 0)
										
													<button
														id="comprar-{{ $producto->id }}"
														type="button"
														class="text-center btn btn-block btn-primary addCartBtn"
														data-id="{{ $producto->inventory->id }}"
														data-producto="{{ $producto->inventory->product_name }}"
														data-precio="{{ $producto->wholesale_total_packet_price }}"
														data-type="al-mayor"
														data-cantidad="1"
													>
														<i class="fas fa-check" ></i>
													</button>
												
												
													
											@else
										
												<button
													id="comprar-{{ $producto->id }}"
													type="button"
													class="text-center btn btn-block btn-primary addCartBtn"
													data-id="{{ $producto->inventory->id }}"
													data-producto="{{ $producto->inventory->product_name }}"
													data-precio="{{ $producto->wholesale_total_packet_price }}"
													data-type="al-mayor"
													data-cantidad="1"
												>
													<i class="fas fa-shopping-cart mr-2"></i>
													<b for="comprar-{{ $producto->id }}" class="texto-carrito">Comprar</b>
												</button>
												
										
											@endif
										

									</div>
								</div>


								@endif
								</div>
								@endforeach
								<div class="float-right">
									{{$productos->render()}}
								</div>
							</div>
								
						</div>
					</div>

							

						</div>

						{{-- <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">

						</div> --}}
					</div>
				</div>
			</div>
		</div>
	</div>

</div>


<!-- Modal -->
<div class="modal fade" id="nuevaDireccion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Nueva dirección para la entrega</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/direcciones" method="post">
				@csrf

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
				

			</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
					<button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Guardar</button>
				</div>
			</form>
		</div>
	</div>
</div>

{{-- INFORMACION DE LA CO;PRA --}}
<div class="modal fade" id="detalles" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Información de la compra</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal_loader py-5" id="modal_loader">
				<div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status"></div>
			</div>

			<div class="modal-body">
				
				<div class="row mb-2">
					<div class="col col-sm-4">
						<span class="font-weight-bold">Factura</span>
					</div>
					<div class="col col-sm-8">
						<span id="factura_id"></span>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col col-sm-4">
						<span class="font-weight-bold">Cliente</span>
					</div>
					<div class="col col-sm-8">
						<span id="cliente"></span>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col col-sm-4">
						<span class="font-weight-bold">Monto</span>
					</div>
					<div class="col col-sm-8">
						<span id="monto"></span>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col col-sm-4">
						<span class="font-weight-bold">Productos</span>
					</div>
					<div class="col col-sm-8">
						<span id="productos"></span>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col col-sm-4">
						<span class="font-weight-bold">Fecha</span>
					</div>
					<div class="col col-sm-8">
						<span id="fecha"></span>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
			</div>
		</div>
	</div>
</div>


@endsection

@push('scripts')
<script>

	$(() => {

		toastr.options = {
			"closeButton": true,
			"progressBar": true,
			"positionClass": "toast-bottom-left",
		}

		// Mostrar notificaciones
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		@if (session('pedidos'))
			$('#list-messages-list').trigger('click')
		@endif()
		// saber si hay elementos en el carrito para mostrar la candidad en color rojo en el navbar
		$.get('/get_shoppingcart', (res) => {
			if (res > 0) {
				$('#cart_counter').removeClass('d-none')
				$('#cart_counter').text(res)
			}

			$('#page_loader').fadeOut()
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


		$('#travelRate').change((ev) => {
			let id = $(ev.target).val()

			$.get(`/travel_rates/${id}`, (res) => {

				$('#stimated_time').val(res.rate.stimated_time)
				$('#rate').val(new Intl.NumberFormat().format(res.rate.rate))

			})
			.catch((err) => {
				toastr.error('Algo ha salido mal.')
				console.error(err)
			})
		})

		$('.travelRateModalEditar').click(function() {
			let id = $(this).data('id')
			$('#editForm').attr('action', `/travel_rates/${id}`)

			$.get(`/travel_rates/${id}`, (res) => {
				$('#sectoredithd').val(res.sector.id)
				$('#sectoredit').val(res.sector.sector)
				$('#tarifaedit').val(res.rate.rate)
				$('#stimated_timeedit').val(res.rate.stimated_time)
			})
			.catch((err) => {
				toastr.error('Ha ocurrido un error.')
				console.error(err)
			})
		})

		$('.travelRateModalBorrar').click(function() {
			let id = $(this).data('id')

			$('#travelFormDel').attr('action', `/travel_rates/${id}`)

		})


		$('.detalle').click(function() {
			
			let data = $(this).data()
			
			$.post({
				url: '/get_sale',
				data,
				beforeSend(){
					$('#modal_loader').show()
				}
			})
			.done((res) => {
				console.log(res)

				$('#factura_id').text(res.code)
				$('#cliente').text(`${res.user.people.name} ${res.user.people.lastname}`)
				$('#monto').text(`${res.amount} Bs`)
				
				$('#productos').text('')
				for(let producto of res.details){
					$('#productos').append(`
						<p>${producto.product}</p>
					`)
				}
				
				$('#fecha').text(res.created_at)

				$('#modal_loader').fadeOut()
			})
			.fail((err) => {
				console.error(err)
				toastr.error('Algo ha fallado.')
			})
		})

		@if ($errors->any())
			@foreach ($errors->all() as $error)
				toastr.error("{{ $error }}")
            @endforeach
		@endif()

		@if (session('success'))
			toastr.success("{{ session('success') }}")
		@endif()
	})

	//MODAL DE DIRECCION

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

	//CONSULTA DE LOS SECTORES DE LA CIUDAD SELECCIONADA
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

	//CONSULTA AL MAYOR Y AL MENOR

	$('#alMayor').click(function(e){
		window.location = '/perfil/?buyType='+ e.target.value;
	})

	$('#alMenor').click(function(e){
		window.location = '/perfil/?buyType='+ e.target.value;
	})

	//AGREGAR AL CARRITO

	$('.addCartBtn').click(function(e){

			let data = $(this).data()
			let that = $(this)

			$.post({
				url: '/shoppingcart',
				data,
				beforeSend(){
					that.attr('disabled', true)
					that.html('<i class="fas fa-circle-notch fa-spin fa-"></i>')
				}
			})
			.done((res) => {
				console.log(res)
				
				if (res == 'rejected') {
					toastr.info('Este producto ya está agregado con un tipo de compra distinto.')
				} else {
					toastr.success('<i>Producto añadido al carrito</i>')
					$('#cart_counter').removeClass('d-none')
					$('#cart_counter').text(res)
					$('#cart_counter-2').removeClass('d-none')
					$('#cart_counter-2').text(res)
				}

				that.removeAttr('disabled')
				that.html('<i class="fas fa-check" style="color: #28a745;"></i>')
				$('.texto-carrito').text("Producto agregado");
				
				
			})
			.fail((err) => {
				toastr.error('Ha ocurrido un error.')
				console.error(err)
			})
		});

		// Para añadir cuando está logueado
		
		$('.addToWishlist').click(function(){
			let producto = $(this).data('id')
			let that = $(this)

			$.post({
				url: '/lista-de-deseos',
				data: { productoid: producto },
				beforeSend(){
					that.attr('disabled', true)
					that.html('<i class="fas fa-circle-notch fa-spin fa-"></i>')
				}
			})
			.done((res) => {
				if (res.type == 'warning') {
					toastr.warning(res.mess)
				}
				else {
					toastr.success(res.mess)
					$('#wishlist_counter').removeClass('d-none')
					$('#wishlist_counter').text(res.wl_qty)
				}

				that.removeAttr('disabled')
				that.html('<i class="fas fa-heart"></i>')
			})
			.fail((err) => {
				console.log(err)
			})
		})

		//BORRAR UNA DIRECCION

		$('.address_delete').click(function(event) {
			let data = $(this).data();
			console.log(data);
		});

</script>
@endpush