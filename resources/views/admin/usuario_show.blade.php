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
				<div class="card shadow">
												
					<div class="card-body">
					
						
						<div class="row">
							<div class="col-3 col-sm-3 col-md-1 col-lg-12 col-xl-3">
								<div class="row">
									<div class="col-lg-6 col-xl-12">
									<i class="far fa-user" style="font-size: 5em;"></i>
									</div>
									<div class="col-lg-6 col-xl-12">
										<!--<button class="mt-2 btn btn-primary mt-3" data-toggle="modal" data-target="#modal-edit"><i class="fas fa-edit"></i></button>-->
									</div>
								</div>
							</div>
							<div class="col-9 col-sm-9 col-md-11 col-lg-12 col-xl-9">
								<p class="font-weight-bold h4"><span>{{ $usuario->people->name }}</span> <span>{{ $usuario->people->lastname }}</span></p>
								<p><i class="far fa-envelope" style="color: #dc3545;"></i> {{ $usuario->email }}</p>
								<p><i class="fas fa-phone"></i> {{ $usuario->people->phone_number }}</p>
								<p><i class="far fa-id-badge" style="color: #007bff;"></i> {{ $usuario->people->dni }}</p>
							</div>
							
						</div>
					
					</div>
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
										<th class="d-none d-md-table-cell">ID COMPRA</th>
										<th class="d-none d-md-table-cell">PRODUCTOS</th>
										<th>MONTO (Bs)</th>
										<th class="d-md-table-cell">DELIVERY</th>
										<th  class="d-none d-md-table-cell">CONFIRMADO</th>
										
										<th>ACCIONES</th>
									</thead>
									<tbody>
										@forelse($sales as $compra)
											<tr>
												<td class="d-none d-md-table-cell">FC-000{{ $compra->id }}</td>
												<td class="d-none d-md-table-cell">{{ count($compra->details) }}</td>
												<td>{{ number_format( $compra->amount, 2, ',', '.') }} <br><small class="font-weight-bold" style="color: #008080">{{round($compra->amount/$compra->dolar->price)}}$</small></td>
												@if(ucfirst($compra->delivery) == "No")
												<td class="d-md-table-cell">{{ ucfirst($compra->delivery) }}</td>
												@else
													@if($compra->stimated_time != null)
													<td>{{$compra->stimated_time}}min</td>

													@else
													<td class=" d-md-table-cell">{{ ucfirst($compra->delivery) }}</td>
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
														<td class="d-none d-md-table-cell small font-weight-bold">{{\Carbon\Carbon::createFromTimeStamp(strtotime($compra->dispatched))->diffForHumans()}}</td>
														@else
														<td><label class="font-weight-bold small">Puede retirar 
														<button type="button" class="ml-2 btn btn-primary" data-toggle="modal" data-target="#modal-direccion">
														<i class="fas fa-info"></i>
														</button>
														</label></td>
														@endif
													@endif
												@else
												<td class="d-none d-md-table-cell"><label class="font-weight-bold small">Esperando confirmación</label></td>
												@endif
												
												<td>
													<!--
													<button data-toggle="modal" data-id="{{ $compra->id }}" data-target="#detalles" class="btn btn-primary detalle"><i class="fas fa-info"></i></button>
													-->
													<button type="button" class="btn btn-primary d-md-none" data-toggle="modal" data-target="#exampleModal4-{{ $compra->id }}">más</button>
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
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
<script>
	$(() => {

		$('#loading').fadeOut()
	})
</script>

@endpush