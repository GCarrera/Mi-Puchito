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
		<div class="col">
			<div class="card shadow-sm">
				<div class="card-header d-flex justify-content-between">
					<h4>Ventas del día</h4>
					<p class="lead">Martes, 25 de Octubre</p>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table text-center table-sm table-hover table-bordered">
							<thead>
								<tr>
									<th>ID FACTURA</th>
									<th>TIPO VENTA</th>
									<th>MONTO (Bs)</th>
									<th>CLIENTE</th>
									<th>DELIVERY</th>
									<th>INFORMACION</th>
								</tr>
							</thead>
							<tbody>
								@forelse($ventas as $venta)
									<tr>
										<td>{{$venta->code}}</td>
										<td></td>
										<td>{{$venta->amount}}</td>
										<td></td>
										<td>{{ $venta->delivery}}</td>
										<td>
											<button class="btn btn-md btn-primary">
												<i class="fas fa-info-circle"></i>
											</button>
										</td>
									</tr>
								@empty

									<tr>
										<td colspan="6">No hay datos para mostrar.</td>
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

@endsection

@push('scripts')
<script>
	$(() => {
		$('#loading').fadeOut()
	})
</script>
@endpush