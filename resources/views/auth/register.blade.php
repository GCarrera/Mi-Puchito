@include('layouts.header')

<div class="d-flex justify-content-center" style="height: 100%">
	<div class="animate fadeIn">

		<div class="mb-3 d-flex justify-content-center">
			<img src="{{ asset('img/pinchitos.png') }}" style="width: 200px; ">
		</div>

		<div class="card mb-4" style="width: 100%;">
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
						<input id="dni" autofocus type="text" class="form-control @error('dni') is-invalid @enderror" name="dni" value="{{ old('dni') }}" >

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
						<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"  >

						@error('name')
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

						<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email">

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
							<div class="input-group-text">
								<i class="fas fa-lock"></i>
							</div>
						</div>
						<input id="password" type="password" class="form-control rounded-right border border-right-0 @error('password') is-invalid @enderror" name="password"  autocomplete="new-password" >

						<div class="input-group-prepend">
							<div class="input-group-text rounded-right rounder-icon-password-input">
								<i id="icon-eye-password" class="fas fa-eye"></i>
							</div>
						</div>

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

						<input id="password-confirm" type="password" class="form-control rounded-right border border-right-0" name="password_confirmation"  autocomplete="new-password">

						<div class="input-group-prepend">
							<div class="input-group-text rounded-right rounder-icon-password-input">
								<i id="icon-eye-password-confirm" class="fas fa-eye"></i>
							</div>
						</div>
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
					<div  class="mb-3 text-center">
						 <a href="/">Inicio</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


@push('scripts')
<script>

	$(() => {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		// $('#dni').blur(function() {
		// 	toastr.error('blur')
		// 	var data = { dni: $('dni').val() }
		// 	$.post({url: 'get_dni', data})
		// 	.done((res) => {
		// 		toastr.success('Exito')
		// 		console.log(res)
		// 	})
		// 	.fail((err) => {
		// 		toastr.error('Ha ocurrido un error')
		// 	})
		// })
		@if ($errors->any())
			@foreach ($errors->all() as $error)
				toastr.error("{{ $error }}")
            @endforeach
		@endif()

		toastr.options = {
			"closeButton": true,
			"progressBar": true,
			"positionClass": "toast-bottom-left",
		}

		$('#icon-eye-password').click((e) => {
			if ($('#password').val()) {
				if ($('#icon-eye-password').hasClass('fa-eye')) {
					$('#password').removeAttr('type');
					$('#icon-eye-password').addClass('fa-eye-slash').removeClass('fa fa-eye');
				} else {
					$('#password').attr('type', 'password');
					$('#password-confirm').attr('type', 'password');
					$('#icon-eye-password').addClass('fa fa-eye').removeClass('fa-eye-slash');;
				}
			}
		});

		//Validar que el icono cambie cuando el input este vacio, o si deciden borrar el pass completamente.
		$('#password').keyup((e) => {
			if ($('#password').val() === '') {
				$('#password').attr('type', 'password');
				$('#icon-eye-password').addClass('fa fa-eye').removeClass('fa-eye-slash');
				$('#icon-eye-password-confirm').addClass('fa fa-eye').removeClass('fa-eye-slash');
				$('#password-confirm').attr('type', 'password');
				$('#password-confirm').val('');
			}
		});

		/*------------------------------ Confirmar contraseña --------------------------------------*/
		$('#icon-eye-password-confirm').click((e) => {
			if ($('#password').val()) {
				if ($('#icon-eye-password-confirm').hasClass('fa-eye')) {
					$('#password-confirm').removeAttr('type');
					$('#icon-eye-password-confirm').addClass('fa-eye-slash').removeClass('fa fa-eye');
				} else {
					$('#password-confirm').attr('type', 'password');
					$('#icon-eye-password-confirm').addClass('fa fa-eye').removeClass('fa-eye-slash');;
				}
			}
		});

		$('#password-confirm').keyup((e) => {
			if ($('#password-confirm').val() === '') {
				$('#password-confirm').attr('type', 'password');
				$('#icon-eye-password-confirm').addClass('fa fa-eye').removeClass('fa-eye-slash');
			}
		});

	})
</script>
@endpush


@include('layouts.footer')