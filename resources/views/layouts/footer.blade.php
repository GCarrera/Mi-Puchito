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
		
		function soloNumeros(e){
			var key = window.event ? e.which : e.keyCode;
			if (key < 48 || key > 57) {
				//Usando la definición del DOM level 2, "return" NO funciona.
				e.preventDefault();
			}
		}
	</script>
	@stack('scripts')
</body>
</html>