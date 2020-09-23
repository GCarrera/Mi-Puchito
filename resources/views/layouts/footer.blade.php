	<!-- Scripts -->
	<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
	<script src="{{ asset('js/popper.min.js') }}"></script>
	<script src="{{ asset('js/toastr.js') }}"></script>-
	<script src="{{ asset('slick/slick.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-autocomplete.min.js') }}"></script>
	<!-- <script src="{{ asset('bootstrap-star-rating/js/star-rating.js') }}"></script> -->
	<!-- <script src="{{ asset('bootstrap-star-rating/themes/krajee-fas/theme.min.js') }}" ></script> -->
	<!--<script src="{{ asset('js/datatables.min.js') }}" ></script>-->
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/scripts.js') }}"></script>
	<script src="{{ asset('js/moment.min.js') }}"></script>
	<script src="{{ asset('js/daterangepicker.js') }}"></script>
	<script src="{{ asset('js/jquery.lazy.min.js') }}"></script>
	
	<script>
			//LAZY LOAD
		$(function(){

			$('img').lazy({
				effect: 'fadeIn',
			});
		})
			
		

		// Para añadir cuando no está logueado
		function buttonPressed(button) {
			LocalStorage = window.localStorage;
			LocalStorage.setItem('buttonPressed', button)
			console.log(LocalStorage.getItem('buttonPressed'))
			window.location = '/login'
		}
		
		function soloNumeros(e){
			var key = window.event ? e.which : e.keyCode;
			if (key < 48 || key > 57) {
				//Usando la definición del DOM level 2, "return" NO funciona.
				e.preventDefault();
			}
		}

		function copiarAlPortapapeles(id){
			var codigoACopiar = document.getElementById(id);    //Elemento a copiar
			//Debe estar seleccionado en la página para que surta efecto, así que...
			var seleccion = document.createRange(); //Creo una nueva selección vacía
			seleccion.selectNodeContents(codigoACopiar);    //incluyo el nodo en la selección
			//Antes de añadir el intervalo de selección a la selección actual, elimino otros que pudieran existir (sino no funciona en Edge)
			window.getSelection().removeAllRanges();
			window.getSelection().addRange(seleccion);  //Y la añado a lo seleccionado actualmente
			try {
				var res = document.execCommand('copy'); //Intento el copiado
				if (res)
					toastr.success('Texto copiado al portapapeles')
				else
					toastr.error('No se pudo copiar')
			} catch(ex) {
				toastr.error('No se pudo copiar')
			}
			window.getSelection().removeRange(seleccion);
		}

		//MODAL DEL DOLAR

		$('#btn_dolar').click(function(e) {
			e.target.preventDefault;
			$.get({
				url : `/get_dolar`
			})
			.done((data) => {
				$('#dolar-precio').text(data.price);
				$('#modal-dolar').modal('show');
				//console.log(data);
			})
			.fail((err)=> {
				console.log(err)
				toastr.error('Ha ocurrido un error.')
			})
			//
		});

		$('#fechas').daterangepicker();
	</script>
	@stack('scripts')
</body>
</html>