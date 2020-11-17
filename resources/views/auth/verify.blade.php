@include('layouts.header')

<div class="fondo-login d-flex justify-content-center">
	<div class="animated fadeIn">
		
		<div class="d-flex justify-content-center">
			<div class="mb-3 d-flex justify-content-center">
				<img src="{{ asset('img/pinchitos.png') }}" style="width: 200px; ">
			</div>
			<div class="card">
				<div class="card-header">{{ __('Revisa tu correo electrónico.') }}</div>

				<div class="card-body">
					@if (session('resent'))
						<div class="alert alert-success" role="alert">
							{{ __('El link de verificación ha sido enviado a tu correo electrónico.') }}
						</div>
					@endif

					{{ __('Antes de continuar, por favor revisa tu correo electrónico.') }}
					{{ __('Si usted no ha recibido el correo.') }},
					<form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
						@csrf
						<button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click para solicitar otro') }}</button>.
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@include('layouts.footer')