	<!-- Scripts -->
	<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
	<script src="{{ asset('js/popper.min.js') }}"></script>
	<script src="{{ asset('js/toastr.js') }}"></script>
	<script src="{{ asset('slick/slick.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-autocomplete.min.js') }}"></script>
	<!-- <script src="{{ asset('bootstrap-star-rating/js/star-rating.js') }}"></script> -->
	<!-- <script src="{{ asset('bootstrap-star-rating/themes/krajee-fas/theme.min.js') }}" ></script> -->
	<script src="{{ asset('js/datatables.min.js') }}" ></script>
	<script src="{{ asset('js/app.js') }}"></script>
	<script>
		// Para añadir cuando no está logueado
		function buttonPressed(button) {
			LocalStorage = window.localStorage;
			LocalStorage.setItem('buttonPressed', button)
			console.log(LocalStorage.getItem('buttonPressed'))
			window.location = 'login'
		}
	</script>
	@stack('scripts')
</body>
</html>