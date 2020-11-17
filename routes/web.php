<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'CustomerController@index');
Route::get('/home', 'CustomerController@index')->name('home');
// Route::get('/home', function () {
//     return redirect('services');
// });
Route::post('/get_dni', 'ProfileController@getDni');

Auth::routes();
Route::get('/prueba', 'ShoppingCartController@prueba');


Route::get('/city/{state_id}', 'ShoppingCartController@getCity');
Route::get('/sector/{city_id}', 'ShoppingCartController@getSector');
Route::resource('shoppingcart', 'ShoppingCartController');
Route::get('/get_shoppingcart', 'ShoppingCartController@get_shoppingcart');
Route::delete('/limpiar_carrito', 'ShoppingCartController@clear');

Route::middleware(['auth', 'optimizeImages'])->group(function(){

	Route::prefix('admin')->group(function(){
		Route::get('/', 'AdminController@index')->name('admin');
		Route::get('/inventario', 'AdminController@inventario')->name('inventario');
		Route::get('/venta', 'AdminController@compra_venta')->name('venta');
		Route::get('/empresa_categorias', 'AdminController@empresa_categorias')->name('empresa_categorias');
		Route::get('/delivery', 'AdminController@delivery')->name('delivery');
		Route::resource('/cuentas-bancarias', 'BankAccountController');
		Route::put('/confirmar-pedido/{id}', 'AdminController@confirmar_pedido');
		Route::put('/confirmar-pedido-delivery/{id}', 'AdminController@confirmar_pedido_delivery');
		//USUARIOS
		Route::get('/usuarios', 'UsuariosController@index')->name('usuarios');
		Route::get('/usuarios/{id}', 'UsuariosController@show')->name('usuarios.show');

	});
	//PRECIO ACTUAL DEL DOLAR
	Route::get('/get_dolar', 'AdminController@get_dolar');
	Route::post('/establecer_dolar', 'AdminController@establecer_dolar');


	Route::get('/ventas-al-mayor', 'CustomerController@al_mayor');


	Route::resource('lista-de-deseos', 'WishlistController');

	Route::get('/perfil', 'ProfileController@perfil')->name('perfil');
	Route::get('/editar_perfil', 'ProfileController@editar_perfil')->name('editar_perfil');
	Route::resource('/direcciones', 'AddressUserDeliveryController');

	Route::resource('/travel_rates', 'TravelRateController');
	Route::resource('/products', 'ProductController');
	Route::resource('/inventory', 'InventoryController');
	//SUMAR PRODUCTOS EN EL STOCK
	Route::put('/sumar-inventory/{id}', 'InventoryController@sumar_producto');
	Route::put('/restar-inventory/{id}', 'InventoryController@restar_producto');

	Route::post('/traer_empresa', 'AdminController@traer_empresa');
	Route::post('/editar_empresa', 'AdminController@editar_empresa');
	Route::post('/traer_categoria', 'AdminController@traer_categoria');
	Route::post('/editar_categoria', 'AdminController@editar_categoria');
	Route::post('/productImageUploads', 'ProductController@upload_images');
	Route::post('/registrar_categoria', 'AdminController@registrar_categoria');
	Route::post('/registrar_empresa', 'AdminController@registrar_empresa');
	Route::post('/eliminar_empresa', 'AdminController@eliminar_empresa');
	Route::post('/eliminar_categoria', 'AdminController@eliminar_categoria');

	Route::get('/traer_ciudad/{estado}', 'TravelRateController@traer_ciudad');
	Route::get('/traer_sectores/{ciudad}', 'TravelRateController@traer_sectores');
	Route::get('/get_wishlist', 'WishlistController@get_wishlist');
	Route::post('/sale', 'SaleController@store');
	Route::post('/get_sale', 'SaleController@get_sale');

	//PDF FACTURA
	Route::get('/get-pedido/{id}', 'FacturaController@get_pedido')->name('factura.pdf');

	Route::get('/get-pedido-descarga/{id}', 'FacturaController@get_pedido_descarga')->name('factura.pdf.descarga');
});
Route::get('/categoria/{categoria}', 'CustomerController@categoria');

Route::get('/traer_productos', 'AdminController@traer_productos');


/*
Route::get('/prueba', function(){

	return view('prueba');
});
*/
Route::get('test', function () {

    return view('welcome');
});
//PARA LOS PISOS DE VENTAS
Route::get('/piso-ventas', 'PisoVentasController@index')->name('piso.ventas.index');
Route::get('/piso-ventas-precio', 'PisoVentasController@precios')->name('piso.ventas.precios');
Route::get('/piso-ventas-anclar', 'PisoVentasController@anclar')->name('piso.ventas.anclar');
//DESPACHOS ALMACEN
Route::get('/despachos-almacen', 'DespachosController@index_almacen')->name('despachos.almacen.index');
//RUTAS APIS
Route::group(['prefix' => 'api'], function(){
	//USUARIO
	Route::get('/get-id', 'UsersController@get_id');
	//INVENTARIO
	Route::get('/get-inventario', 'InventarioController@get_inventario');
	Route::get('/ultimo-inventory', 'InventarioController@ultimo_inventory');
	Route::get('/get-inventory/{id}', 'InventarioController@get_inventory');//WEB
	Route::post('/registrar-inventory', 'InventarioController@store_inventory');
	Route::get('/get-precios-inventory/{id}', 'InventarioController@get_precios_inventory');//WEB
	Route::post('/actualizar-precios-inventory', 'InventarioController@actualizar_precios_inventory');

	//ACTUALIZAR INVENTORY_ID
	Route::post('/get-inventories', 'InventarioController@get_inventories');

	//DESPACHOS
	Route::get('/get-despachos', 'DespachosController@get_despachos');
	Route::post('/confirmar-despacho', 'DespachosController@confirmar_despacho');
	Route::post('/negar-despacho', 'DespachosController@negar_despacho');
	Route::get('/get-despachos-sin-confirmacion/{piso_venta_id}', 'DespachosController@get_despachos_sin_confirmacion');
	Route::post('/get-despachos-confirmados', 'DespachosController@get_despachos_confirmados');
	Route::post('/actualizar-confirmados', 'DespachosController@actualizar_confirmaciones');

	Route::post('/get-despachos-web', 'DespachosController@get_despachos_web');
	Route::get('/ultimo-despacho', 'DespachosController@ultimo_despacho');
	Route::post('/registrar-despachos-piso-venta', 'DespachosController@registrar_despacho_piso_venta');

	//DESPACHOS ALMACEN
	Route::get('/despachos-datos-create', 'DespachosController@get_datos_create');
	Route::post('/despachos', 'DespachosController@store');
	Route::get('/get-despachos-almacen', 'DespachosController@get_despachos_almacen');
	Route::post('/despachos-retiro', 'DespachosController@store_retiro');
	Route::get('/inventario-piso-venta/{id}', 'DespachosController@get_datos_inventario_piso_venta');

	//VENTAS
	Route::get('/get-ventas', 'VentasController@get_ventas');
	Route::get('/ventas-datos-create', 'VentasController@get_datos_create');
	Route::post('/ventas', 'VentasController@store');
	Route::post('/ventas-comprar', 'VentasController@store_compra');
	//SINCRONIZACION
	Route::get('/ultima-sincronizacion/{id}','SincronizacionController@ultimo');
	Route::post('/sincronizacion', 'SincronizacionController@store')->name('sincronizacion.store');

	//VENTA REFRESCAR
	Route::get('/get-piso-venta-id', 'VentasController@get_piso_venta_id');
	Route::get('/ultima-venta/{piso_venta}', 'VentasController@ultima_venta');//WEB
	Route::get('/ventas-sin-registrar/{piso_venta}/{id}', 'VentasController@ventas_sin_registrar');
	Route::post('/registrar-ventas', 'VentasController@registrar_ventas');//WEB

	Route::get('/get-ventas-anuladas','VentasController@get_ventas_anuladas');
	Route::post('/actualizar-anulados','VentasController@actualizar_anulados');//WEB
	Route::post('/actualizar-anulados-local','VentasController@actualizar_anulados_local');

	//RESUMEN
	Route::get('/get-piso-ventas', 'PisoVentasController@get_piso_ventas');
	Route::get('/resumen/{id}', 'PisoVentasController@resumen');
	Route::get('/ventas-compras/{id}', 'PisoVentasController@ventas_compras');
	Route::get('/despachos-retiros/{id}', 'PisoVentasController@despachos_retiros');
	Route::get('/productos-piso-venta/{id}', 'PisoVentasController@productos_piso_venta');

	//CAJA REFRESCAR

	Route::put('/actualizar-dinero-piso-venta/{id}', 'PisoVentasController@actualizar_dinero_piso_venta');
	Route::get('/ultima-vaciada-caja/{id}', 'PisoVentasController@ultima_vaciada_caja');
	Route::post('/registrar-cajas', 'PisoVentasController@registrar_cajas');

	//ACTUALIZAR PRECIOS
	Route::get('/productos/{id}', 'PisoVentasController@productos_piso_venta_actualizar_precios');
	Route::put('/actualizar-precio/{id}', 'PisoVentasController@actualizar_precio');

	Route::get('/requisitos-anclar/{id}', 'PisoVentasController@requisitos_anclar');
	Route::put('/enlazar/{id}', 'PisoVentasController@enlazar');
});
