<!-- Scripts -->
<script src="{{ asset('public/js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('public/js/popper.min.js') }}"></script>
<script src="{{ asset('public/js/toastr.js') }}"></script>-
<script src="{{ asset('public/slick/slick.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap-autocomplete.min.js') }}"></script>
<!-- <script src="{{ asset('public/bootstrap-star-rating/js/star-rating.js') }}"></script> -->
<!-- <script src="{{ asset('public/bootstrap-star-rating/themes/krajee-fas/theme.min.js') }}" ></script> -->
<!--<script src="{{ asset('public/js/datatables.min.js') }}" ></script>-->
<script src="{{ asset('public/js/app.js') }}"></script>
<script src="{{ asset('public/js/scripts.js') }}"></script>
<script src="{{ asset('public/js/moment.min.js') }}"></script>
<script src="{{ asset('public/js/daterangepicker.js') }}"></script>
<script src="{{ asset('public/js/jquery.lazy.min.js') }}"></script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>

<script>

	var comma = false;


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

	function soloNumeros(e, i){
		valor = i.value;
		var patron = /\.+/;
		hallado = valor.match(patron);
		if (hallado != null) {
			comma = true;
		} else {
			comma = false;
		}
		var key = window.event ? e.which : e.keyCode;
		if (key == 46) {
			if (comma == true) {
				e.preventDefault();
			} else {
				comma = true;
			}
		}
		//console.log(key);
		//44 -> , 46 -> .
		if (key < 44 || key > 57 || key == 45 || key == 47 || key == 44) {
			//Usando la definición del DOM level 2, "return" NO funciona. 188
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


	$.get({
		url : `/notificaciones`,
	})
	.done((data) => {

		if (data != 'false' && data.length > 0) {
			console.log(data);
			$('#buy_counter').empty();
			$('#buy_counter').addClass("badge badge-info");
			$('#buy_counter').append('<div class="dropleft"><span class="" id="dropdownNotify" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-bell"></i></span><div class="dropdown-menu" aria-labelledby="dropdownNotify" id="notifyMenu"></div></div>');
			$('#notifyMenu').empty();

			$('#notify-nav').empty();
			$('#notify-nav').append('<li class="nav-item dropdown"><span class="nav-link dropdown-toggle" id="dropdownNotifyNav" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-bell" style="color: #17a2b8 !important;"></i></span><div class="dropdown-menu" aria-labelledby="dropdownNotifyNav" id="notifyMenuNav"></div></li>');
			$('#notifyMenuNav').empty();
			const fill = (number, len) =>
			"0".repeat(len - number.toString().length) + number.toString();

			$.each( data, function( key, value ) {

					var codigo = value.id;
					codigo = fill(codigo, 4);
					var status = value.confirmacion;
					var total = new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(value.amount);

					$('#notifyMenu').append('<a class="dropdown-item" href="#" onclick="hideNotify('+value.id+')"><h6>FC-'+codigo+'</h6><span class="text-muted small text-capitalize">'+status+' - <span class="text-success">'+total+'$</span></span></a><div class="dropdown-divider"></div>');
					$('#notifyMenuNav').append('<a class="dropdown-item" href="#" onclick="hideNotify('+value.id+')"><h6>FC-'+codigo+'</h6><span class="text-muted small text-capitalize">'+status+' - <span class="text-success">'+total+'$</span></span></a><div class="dropdown-divider"></div>');

			});

		}
	})
	.fail((err)=> {
		console.log("sin notificacion");
		console.log(err)
		//toastr.error('Ha ocurrido un error.')
	})

	function hideNotify(id) {
		console.log("id venta: "+id);
		$.get({
			url : `/finalizar-notificacion/`+id,
		})
		.done((data) => {

				window.location = '/perfil';

		})
		.fail((err)=> {
			console.log(err);
		})
	}
</script>
@stack('scripts')

</body>
</html>
