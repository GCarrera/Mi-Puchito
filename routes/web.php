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

// Route::get('/home', function () {
//     return redirect('services');
// });
Route::post('/get_dni', 'ProfileController@getDni');

Auth::routes();

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

	Route::get('/home', 'CustomerController@index')->name('home');
	Route::get('/ventas-al-mayor', 'CustomerController@al_mayor');
	
	Route::get('/city/{state_id}', 'ShoppingCartController@getCity');
	Route::get('/sector/{city_id}', 'ShoppingCartController@getSector');
	Route::resource('shoppingcart', 'ShoppingCartController');
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
	Route::get('/get_shoppingcart', 'ShoppingCartController@get_shoppingcart');
	Route::delete('/limpiar_carrito', 'ShoppingCartController@clear');
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

Route::get('/prueba', 'ShoppingCartController@prueba');

Route::get('/prueba', function(){

	return view('prueba');
});

Route::get('test', function () {

    event(new App\Events\MyEvent('hello world'));
    return "ok";
});

