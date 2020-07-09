@include('layouts.header')

<div class="fondo-login d-flex justify-content-center">
	<div class="animated fadeIn">
		
		<div class="d-flex justify-content-center">
			<div class="mb-3 d-flex justify-content-center">
				<img src="{{ asset('img/pinchitos.png') }}" style="width: 200px; ">
			</div>
			<div class="card">
				<div class="card-header">{{ __('Verify Your Email Address') }}</div>

				<div class="card-body">
					@if (session('resent'))
						<div class="alert alert-success" role="alert">
							{{ __('A fresh verification link has been sent to your email address.') }}
						</div>
					@endif

					{{ __('Before proceeding, please check your email for a verification link.') }}
					{{ __('If you did not receive the email') }},
					<form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
						@csrf
						<button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@include('layouts.footer')