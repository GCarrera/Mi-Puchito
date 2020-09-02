$(() => {

	

	let host = location.host;
	let pathname = location.href;

	// manejar la clase active del navbar
	if( pathname.endsWith('admin') ){
		$('.nav-item.active').removeClass('active');
		$('#admin').addClass('active');
	}
	else if( pathname.endsWith('admin/inventario') ){
		$('.nav-item.active').removeClass('active');
		$('#inventario').addClass('active');
	}
	else if( pathname.endsWith('admin/venta') ){
		$('.nav-item.active').removeClass('active');
		$('#venta').addClass('active');
	}
	else if( pathname.endsWith('admin/delivery') ){
		$('.nav-item.active').removeClass('active');
		$('#delivery').addClass('active');
	}

	else if( pathname.endsWith('/home') ){
		$('.nav-item.active').removeClass('active');
		$('#inicio').addClass('active');
	}
	else if( pathname.endsWith('/lista-de-deseos') ){
		$('.nav-item.active').removeClass('active');
		$('#lista-de-deseos').addClass('active');
	}
	else if( pathname.endsWith('/shoppingcart') ){
		$('.nav-item.active').removeClass('active');
		$('#shoppingcart').addClass('active');
	}


	// inicializacion de componentes


	//$('table').DataTable();
	// $('.dataTables_length').addClass('bs-select');
	// $('.carousel').carousel();
	$('[data-toggle="tooltip"]').tooltip()

	// $('select').selectpicker();



	// $('.star-rating').rating({
 //    	// displayOnly: true,
	// 	theme: 'krajee-fas',
	// 	containerClass: 'is-star',
	// 	starCaptions: {
	// 		'0.5': 'Muy malo',
	// 		1: 'Muy malo',
	// 		'1.5': 'Muy malo',
	// 		2: 'Malo',
	// 		'2.5': 'Malo',
	// 		3: 'Más o menos',
	// 		'3.5': 'Mas o menos',
	// 		4: 'Bueno',
	// 		'4.5': 'Bueno',
	// 		5: 'Excelente'
	// 	},
	// 	starCaptionClasses: {
	// 		1: 'text-danger',
	// 		'1.5': 'text-danger',
	// 		2: 'text-warning',
	// 		'2.5': 'text-warning',
	// 		3: 'text-info',
	// 		'3.5': 'text-info',
	// 		4: 'text-primary',
	// 		'4.5': 'text-primary',
	// 		5: 'text-success'
	// 	},
	// });

});