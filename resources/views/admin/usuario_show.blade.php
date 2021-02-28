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
			<div class="col-lg-3 col-12 mb-3">

				<div class="profile-sidebar">
					<!-- SIDEBAR USERPIC -->
					<div class="profile-userpic text-center">
						<i class="far fa-user fa-5x"></i>
						<!--<img src="http://keenthemes.com/preview/metronic/theme/assets/admin/pages/media/profile/profile_user.jpg" class="img-responsive" alt="">-->
					</div>
					<!-- END SIDEBAR USERPIC -->
					<!-- SIDEBAR USER TITLE -->
					<div class="profile-usertitle">
						<div class="profile-usertitle-name">
							{{ $usuario->people->name }}</span> <span>{{ $usuario->people->lastname }}
						</div>
						<div class="profile-usertitle-job">
							<span><i class="far fa-envelope" style="color: #dc3545;"></i> {{ $usuario->email }}</span><br>
							@if ($usuario->people->phone_number != null)
								<span><i class="fas fa-phone"></i> {{ $usuario->people->phone_number }}</span><br>
							@endif
							<span><i class="far fa-id-badge" style="color: #007bff;"></i> {{ $usuario->people->dni }}</span>
						</div>
					</div>
					<!-- END SIDEBAR USER TITLE -->
					<!-- END MENU -->
				</div>

			</div>

			<div class="col-lg-9 col-12 mb-3">
				<div class="card">
					<div class="card-header">
						Compras
					</div>
					<div class="card-body">


						<table class="table text-center table-bordered table-sm table-hover">
									<thead>
										<th class="negrita d-none d-md-table-cell">ID COMPRA</th>
										<!--<th class="negrita d-none d-md-table-cell">PRODUCTOS</th>-->
										<th class="negrita">MONTO (BsS)</th>
										<th class="negrita d-md-table-cell">DELIVERY</th>
										<th  class="negrita d-none d-md-table-cell">ESTADO</th>

										<th>ACCIONES</th>
									</thead>
									<tbody>
										@forelse($sales as $compra)
											<tr>
												<td class="d-none d-md-table-cell align-middle">
													FC-000{{ $compra->id }}
													<br>
													<span class="small">Cantidad: </span><span class="text-info small">{{ count($compra->details) }}</span>
												</td>
												{{--<td class="d-none d-md-table-cell">{{ count($compra->details) }}</td>--}}
												<td class="align-middle">{{ number_format( $compra->amount*$compra->dolar->price, 2, ',', '.') }} <br><small class="font-weight-bold" style="color: #008080">{{number_format( $compra->amount, 2, ',', '.')}}$</small></td>
												@if(ucfirst($compra->delivery) == "No")
												<td class="d-md-table-cell align-middle">{{ ucfirst($compra->delivery) }}</td>
												@else
													@if($compra->stimated_time != null)
													<td class="align-middle">{{$compra->stimated_time}}min</td>

													@else
													<td class="align-middle d-md-table-cell">{{ ucfirst($compra->delivery) }}</td>
													@endif
												@endif
												@if ($compra->dispatched != null)

													@if($compra->confirmacion == "denegado")

													<td class="align-middle font-weight-bold small">{{$compra->confirmacion}}
													<button type="button" class="ml-2 btn btn-danger" data-toggle="modal" data-target="#modal-denegado">
														<i class="fas fa-info"></i>
													</button>
													</td>
													@else
														@if($compra->delivery == "si")
														{{--<td class="d-none d-md-table-cell small font-weight-bold">{{\Carbon\Carbon::createFromTimeStamp(strtotime($compra->dispatched))->diffForHumans()}}</td>--}}
														<td class="d-none align-middle d-md-table-cell small text-info font-weight-bold">Confirmado</td>
														@else
														<td class="align-middle"><label class="font-weight-bold small">Puede retirar
														<button type="button" class="ml-2 btn btn-primary" data-toggle="modal" data-target="#modal-direccion">
														<i class="fas fa-info"></i>
														</button>
														</label></td>
														@endif
													@endif
												@else
												<td class="align-middle d-none d-md-table-cell"><label class="font-weight-bold text-secondary small">En Espera</label></td>
												@endif

												<td class="align-middle">
													<!--
													<button data-toggle="modal" data-id="{{ $compra->id }}" data-target="#detalles" class="btn btn-primary detalle"><i class="fas fa-info"></i></button>
													-->
													<button type="button" class="btn btn-primary d-md-none" data-toggle="modal" data-target="#exampleModal4-{{ $compra->id }}">más</button>
													<button class="btn btn-sm btn-primary" data-toggle="tooltip" data-title="Detalles" onclick='showInfo({{ $compra->id }})'>
														<i class="fas fa-search"></i>
													</button>
													{{--<a class="btn btn-danger" target="_blank" href="{{route('factura.pdf', ['id' => $compra->id])}}"><i class="fas fa-file-alt" style="color: #ffffff"></i></a>--}}

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
													  			<li class="list-group-item"><span class="font-weight-bold">ID COMPRA: </span>{{ $compra->code }}</li>
																<li class="list-group-item"><span class="font-weight-bold">PRODUCTOS: </span>{{ $compra->count_product }}</li>
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

							<div class="float-right">
							{{$sales->render()}}
							</div>

							<!-- Modal -->
							<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="almacen" aria-hidden="true">
								<div class="modal-dialog modal-lg" role="document">
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

											<div class="mt-3">
													<span class="float-left"><span class="font-weight-bold" id="fecha-create">Esta factura fue emitida:</span></span>
													<span class="float-right"><span class="font-weight-bold">Cliente:<span id="user-name"></span></span></span><br>
											</div>

									<!--TABLA DE PRODUCTOS-->

									<table class="table table-striped table-bordered mt-3">
											<thead class="bg-info text-white">
													<tr>
															<th>Producto</th>
															<th>Cantidad</th>
															<th>Precio unitario</th>
															<!--<th>iva unitario</th>-->
															<th>Subtotal</th>
													</tr>
											</thead>
											<tbody id="table-products">

											</tbody>
									</table>
									<div class="text-right">
											<span class=""><span class="font-weight-bold">Total: </span><span id="total-show"></span></span>
									</div>

										</div>

										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-2"></i>Cerrar</button>
											<a type="button" id="factura-pdf" class="btn btn-danger" target="_blank"><i class="fas fa-file-pdf mr-2"></i>PDF</a>
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

function showInfo(id) {
	console.log(id);
	$('#detailModal').modal('show');

	$.get({
		url : `/admin/delivery-data/${id}`,
		beforeSend(){
			$('#modal_loader').show()
		}
	})
	.done((data) => {

		console.log(data);
		var venta = data;

		$('#almacen').text(`Factura: 000${venta.id}`);
		$('#fecha-create').text(`${venta.created_at}`);
		$('#user-name').text(`${venta.user.people.name}`);
		$('#user-dni').text(`${venta.user.people.dni}`);

		$('#table-products').empty();

		$.each( venta.details, function( key, value ) {

			//console.log(value.inventory);

				var subtotal = new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(value.sub_total / value.quantity);
				var total = new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(value.amount);

				$('#table-products').append('<tr><td>'+value.product.inventory.product_name+'</td><td>'+value.quantity+'</td><td>'+subtotal+'</td><td>'+total+'</td></tr>');

		});

		var totalShow = new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(venta.amount);
		$('#total-show').text(totalShow);
		var url = '/get-pedido-descarga/'+venta.id;
		$('#factura-pdf').attr('href', url);

		$('#modal_loader').fadeOut();
	})
	.fail((err)=> {
		console.log(err)
		toastr.error('Ha ocurrido un error.')
	})

}

	$(() => {

		$('#loading').fadeOut()
	})
</script>

@endpush
@push('styles')
<style>
	.profile {
	margin: 20px 0;
	}

	/* Profile sidebar */
	.profile-sidebar {
	padding: 20px 0 10px 0;
	background: #fff;
	}

	.profile-userpic img {
	float: none;
	margin: 0 auto;
	width: 50%;
	height: 50%;
	-webkit-border-radius: 50% !important;
	-moz-border-radius: 50% !important;
	border-radius: 50% !important;
	}

	.profile-usertitle {
	text-align: center;
	margin-top: 20px;
	}

	.profile-usertitle-name {
	color: #5a7391;
	font-size: 16px;
	font-weight: 600;
	margin-bottom: 7px;
	}

	.profile-usertitle-job {
	text-transform: uppercase;
	color: #5b9bd1;
	font-size: 12px;
	font-weight: 600;
	margin-bottom: 15px;
	}

	.profile-userbuttons {
	text-align: center;
	margin-top: 10px;
	}

	.profile-userbuttons .btn {
	text-transform: uppercase;
	font-size: 11px;
	font-weight: 600;
	padding: 6px 15px;
	margin-right: 5px;
	}

	.profile-userbuttons .btn:last-child {
	margin-right: 0px;
	}

	.profile-usermenu {
	margin-top: 30px;
	}

	.profile-usermenu ul li {
	border-bottom: 1px solid #f0f4f7;
	}

	.profile-usermenu ul li:last-child {
	border-bottom: none;
	}

	.profile-usermenu ul li a {
	color: #93a3b5;
	font-size: 14px;
	font-weight: 400;
	}

	.profile-usermenu ul li a i {
	margin-right: 8px;
	font-size: 14px;
	}

	.profile-usermenu ul li a:hover {
	background-color: #fafcfd;
	color: #5b9bd1;
	}

	.profile-usermenu ul li.active {
	border-bottom: none;
	}

	.profile-usermenu ul li.active a {
	color: #5b9bd1;
	background-color: #f6f9fb;
	border-left: 2px solid #5b9bd1;
	margin-left: -2px;
	}

	/* Profile Content */
	.profile-content {
	padding: 20px;
	background: #fff;
	min-height: 460px;
	}
</style>
@endpush
