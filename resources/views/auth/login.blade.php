@include('layouts.header')

	<div class="d-flex justify-content-center">
		<div class="animated fadeIn">

			<div class="mb-3 d-flex justify-content-center">
				<a href="/">
					<img src="{{ asset('img/pinchitos.png') }}" style="width: 200px; ">
				</a>
			</div>

			<!-- Material form login -->
			<div class="card" style="width: 350px">
				<h4 class="card-header info-color white-text text-center py-3">
					Iniciar Sesión
				</h4>

				<!--Card content-->
				<div class="card-body px-5 py-4">

					<!-- Form -->
					<form method="POST" action="{{ route('login') }}" style="color: #757575;">

						@csrf


						<div class="input-group mb-3">
						<!-- Email -->
        					<div class="input-group-prepend">
          						<div class="input-group-text"><i class="fas fa-envelope"></i></div>
							</div>
							<input autofocus id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" @error('email') value="{{ old('email') }}" @enderror value="" required placeholder="Email">

							@error('email')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
							@enderror
      					</div>

						<!-- Password -->
						<div class="input-group mb-3">
							<div class="input-group-prepend">
          						<div class="input-group-text"><i class="fas fa-unlock"></i></div>
							</div>
							<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Contraseña" value="">
							@error('password')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
							@enderror
						</div>

						<div class="mt-3">
							<!-- Remember me -->
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
								<label class="custom-control-label" for="remember">Recuérdame</label>
							</div>
						</div>

						<!-- Sign in button -->
						<button class="btn btn-primary btn-rounded btn-block my-3" type="submit"><i class="fas fa-sign-in-alt mr-2"></i>Entrar</button>

						<div class="d-flex justify-content-between">
							<div>
								<!-- Forgot password -->
								@if (Route::has('password.request'))
									<a class="btn btn-link" href="{{ route('password.request') }}">Olvidé mi clave</a>
								@endif
							</div>
							<div>
								<a class="btn btn-success" href="{{ route('register') }}">Registrarme</a>
							</div>
						</div>
						<div class="text-center">
							<a href="/" class="btn btn-link">
								Inicio
							</a>
						</div>

					</form>
					<!-- Form -->

				</div>

			</div>
			<!-- Material form login -->
		</div>
	</div>

@include('layouts.footer')

<script>
		$(document).ready(function() {
			console.log('adsd')
			toastr.options = {
				"closeButton": true,
				"progressBar": true,
				"positionClass": "toast-top-right",
				"timeOut": 10000,
			}
			LocalStorage = window.localStorage;
			console.log(LocalStorage.getItem('buttonPressed'))
			var val = LocalStorage.getItem('buttonPressed')
			switch (val) {
				case 'wish':
					toastr.info('Para añadir a la lista de deseos por favor Inicie Sesión')
					localStorage.removeItem('buttonPressed');					
					break;
				case 'cart':
					toastr.info('Para añadir al carrito de compra por favor Inicie Sesión')
					localStorage.removeItem('buttonPressed');
					break;
				default:
					break;
			}

		})

</script>