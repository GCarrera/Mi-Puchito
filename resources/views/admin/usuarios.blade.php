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
		<div class="card">
			<div class="card-header">
				<h3>Lista de usuarios</h3>
			</div>
			<div class="card-body">
				<table class="table table-bordered">
					<tr>
						<th>Id</th>
						<th>Nombre</th>
						<th>Apellido</th>
						<th>Cedula</th>
						<th>tlf</th>
						<th>Correo</th>
						<th>Acciones</th>
					</tr>
					<tbody>
						@foreach($usuarios as $usuario)
						<tr>
							<td><a href="{{route('usuarios.show', ['id' => $usuario->id])}}">C-00{{$usuario->id}}</a></td>
							<td>{{$usuario->people->name}}</td>
							<td>{{$usuario->people->lastname}}</td>
							<td>{{$usuario->people->dni}}</td>
							<td>{{$usuario->people->phone_number}}</td>
							<td>{{$usuario->email}}</td>
							<td>
								<a type="button" class="ml-2 btn btn-primary" href="{{route('usuarios.show', ['id' => $usuario->id])}}" style="min-width: 40px;">
								<i class="fas fa-info"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
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