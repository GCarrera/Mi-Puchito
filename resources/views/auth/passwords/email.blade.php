@include('layouts.header')

<div class="fondo-login d-flex justify-content-center">

	<div class="animated fadeIn">
		<div class="mb-3 d-flex justify-content-center">
			<a href="/">
				<img src="{{ asset('img/pinchitos.png') }}" style="width: 200px; ">
			</a>
		</div>


			<div class="card " style="width: 350px">
				<div class="card-header">Reiniciar contraseña</div>

				<div class="card-body">
					@if (session('status'))
						<div class="alert alert-success" role="alert">
							{{ session('status') }}
						</div>
					@endif

					<form method="POST" action="{{ route('password.email') }}">
						@csrf

						<div class="form-group">
							<label for="email">Correo Electrónico</label>

							<div class="input-group mb-3">
								<div class="input-group-prepend">
	          						<div class="input-group-text"><i class="fas fa-envelope"></i></div>
								</div>
								<input id="email" autofocus type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
								@error('email')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>

						</div>

						<button type="submit" class="btn btn-primary mb-3 btn-block">
							<i class="fas fa-link mr-2"></i>Enviar link de reseteo al correo
						</button>

						<div class="text-center">
								<a href="/" class="btn btn-link">
									Inicio
								</a>
							</div>

						{{-- <div class="mb-3">
							<a href="/login">Inicia sesión</a>
						</div> --}}
					</form>
				</div>
			</div>

	</div>
</div>

@include('layouts.footer')