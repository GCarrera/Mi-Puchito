@include('layouts.header')

	<div class="fondo-login d-flex justify-content-center align-items-center">
		<div class="animated fadeIn">
			<!-- Material form login -->
			<div class="card" style="width: 380px">

				<h4 class="card-header info-color white-text text-center py-4">
					<strong>Prometheus V1</strong>
				</h4>

				<!--Card content-->
				<div class="card-body px-lg-5 py-5 pt-0">

					<!-- Form -->
					<form method="POST" action="{{ route('login') }}" style="color: #757575;">

						@csrf


						<div class="input-group mb-4">
						<!-- Email -->
        					<div class="input-group-prepend">
          						<div class="input-group-text"><i class="fas fa-envelope"></i></div>
							</div>
							<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" @error('email') value="{{ old('email') }}" @enderror value="elon@musk.com" required placeholder="Email">

							@error('email')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
							@enderror
      					</div>

						<!-- Password -->
						<div class="input-group mb-4">
							<div class="input-group-prepend">
          						<div class="input-group-text"><i class="fas fa-unlock"></i></div>
							</div>
							<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Contraseña" value="admin123">
							@error('password')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
							@enderror
						</div>

						<div class="mt-4">
							<!-- Remember me -->
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
								<label class="custom-control-label" for="remember">Recuérdame</label>
							</div>
						</div>

						<!-- Sign in button -->
						<button class="btn btn-primary btn-rounded btn-block my-4" type="submit"><i class="fas fa-sign-in-alt mr-2"></i>Entrar</button>

						<div class="d-flex justify-content-between">
							<div>
								<!-- Forgot password -->
								@if (Route::has('password.request'))
									<a class="btn btn-link" href="{{ route('password.request') }}">
										{{ __('Olvidé mi clave') }}
									</a>
								@endif
							</div>
							<div>
								<a class="btn btn-link" href="{{ route('register') }}">Registrarme</a>
							</div>
						</div>

					</form>
					<!-- Form -->

				</div>

			</div>
			<!-- Material form login -->
		</div>
	</div>

@include('layouts.footer')