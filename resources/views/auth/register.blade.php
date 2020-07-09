@include('layouts.header')

<div class="d-flex justify-content-center" style="height: 100%">
	<div class="animate fadeIn">

		<div class="mb-3 d-flex justify-content-center">
			<img src="{{ asset('img/pinchitos.png') }}" style="width: 200px; ">
		</div>

		<div class="card mb-4" style="width: 400px">
			<h4 class="card-header info-color white-text text-center py-3">
				Crear cuenta
			</h4>

			<div class="card-body">
				<form method="POST" action="{{ route('register') }}">
					@csrf

					<label for="dni">{{ __('Cédula') }}</label>
					<div class="input-group mb-3">

						<div class="input-group-prepend">
							<div class="input-group-text"><i class="fas fa-id-card"></i></div>
						</div>
						<input id="dni" type="text" class="form-control @error('dni') is-invalid @enderror" name="dni" value="{{ old('dni') }}" required>

						@error('dni')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>

					<label for="name">{{ __('Nombre') }}</label>
					<div class="input-group mb-3">

						<div class="input-group-prepend">
							<div class="input-group-text"><i class="fas fa-user"></i></div>
						</div>
						<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required >

						@error('name')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>

					<label for="lastname">{{ __('Apellido') }}</label>
					<div class="input-group mb-3">

						<div class="input-group-prepend">
							<div class="input-group-text"><i class="fas fa-user"></i></div>
						</div>
						<input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required >

						@error('lastname')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>

					<label for="email">{{ __('Correo Electrónico') }}</label>
					<div class="input-group mb-3">

						<div class="input-group-prepend">
							<div class="input-group-text"><i class="fas fa-envelope"></i></div>
						</div>

						<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

						@error('email')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>

					{{--<div class="form-group row">
						<label for="phone_number" class="col-md-4 col-form-label text-md-right">{{ __('Teléfono') }}</label>

						<div class="col-md-6">
							<input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" required>

							@error('phone_number')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
							@enderror
						</div>
					</div>--}}

					<label for="password">{{ __('Contraseña') }}</label>
					<div class="input-group mb-3">

						<div class="input-group-prepend">
							<div class="input-group-text"><i class="fas fa-lock"></i></div>
						</div>
						<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

						@error('password')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>

					<label for="password-confirm">{{ __('Confirme Contraseña') }}</label>
					<div class="input-group mb-4">
						
						<div class="input-group-prepend">
							<div class="input-group-text"><i class="fas fa-lock"></i></div>
						</div>

						<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
					</div>

					<div class="form-group mb-3">
						<button type="submit" class="btn btn-md btn-primary btn-block">
							<i class="fas fa-save mr-2"></i>{{ __('Registrar y acceder') }}
						</button>
					</div>

					<hr>

					<div  class="mb-3">
						¿Ya tienes una cuenta? <a href="/login">Inicia sesión</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


@push('scripts')
<script>
	$(() => {
		$('#password').click((e) => {
			console.log(e.target)
		})
	})
</script>
@endpush


@include('layouts.footer')