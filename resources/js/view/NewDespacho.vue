 <template>
	<div>
		<div class="container">
			<div class="card shadow">
				<div class="card-body">
					<h1 class="text-center">Nuevo Despacho</h1>
					<div class="mb-3">

            <form action="/despachos-almacen" method="post" @submit.prevent="despachar()"><!--Formulario-->


              <input type="hidden" name="tipo" value="1"><!--ESTABLECER SI ES UN DESPACHO O UN RETIRO-->

              <div class="form-row">
                <div class="form-group col-md-3">
                  <label for="pv">Piso de Venta:</label>
                  <select class="form-control" v-model="piso_venta">
                    <option value="">Seleccione un piso de venta</option>
                    <option v-for="(piso, index) in piso_ventas" :key="index" :value="piso.id">{{piso.nombre}}</option>
                  </select>
                </div>

                <div class="form-group col-md-4">
                  <label for="producto">Producto:</label>
                    <select class="form-control" v-model="articulo.id" @change="establecer_nombre(articulo.id)">
                    <option value="0">Seleecion producto</option>
                    <option v-for="(prod, index) in inventario" :key="index" :value="prod.id">{{prod.product_name}}</option>
                  </select>
                </div>

                <div class="form-group col-md-2">
                  <label for="cantidad">Disponibles:</label>
                  <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad" class="form-control" v-model="disponibles" disabled="">
                </div>

                <div class="form-group col-md-2">
                  <label for="cantidad">Cantidad al menor:</label>
                  <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad" class="form-control" v-model="articulo.cantidad">
                </div>

                <div class="form-group col-md-1">
                  <label class="text-center" for="">Acción:</label><br>
                  <button class="btn btn-primary btn-block" type="button" @click="agregar_producto" :disabled="disabled_nuevo">Agregar</button>
                </div>
              </div>

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Producto</th>
                    <th>cantidad</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(produc_enviar, index) in productos" :key="index">
                    <td>{{produc_enviar.nombre}}</td>
                    <td>{{produc_enviar.cantidad}}</td>
                    <td>
                      <button class="btn btn-danger" type="button" @click="eliminar(index)">Eliminar</button>
                    </td>
                  </tr>
                </tbody>
              </table>

              <button type="submit" class="btn btn-primary" :disabled="productos.length <= 0">Despachar</button>

            </form>

					</div>

				</div>
			</div>
		</div>

	</div>
</template>

<script>
	export default{
		data(){
			return{
				despachos: [],
				piso_ventas: [],
				inventario: [],
				productos: [],//LISTA DE PRODUCTOS QUE VOY A AGREGAR
				articulo: {
					id: 0,
					nombre: "",
					cantidad: "",
					modelo: {}
				},
				piso_venta: "",
				currentPage: 0,
				per_page: 0,
				total_paginas: 0,
				produc_cantidad: "",
				productos_retirar: [],
				piso_venta_retiro: "",
				inventario_piso_venta: [],
				disab: true,
				inventario_cantidad_piso: "",
				articulo_retiro: {
					id: "",
					nombre: "",
					cantidad: "",
					modelo: {}
				},
				pagination: {//PAGINACION DE RIMORSOFT
				 'total' : 0,
   				'current_page' : 0,
                'per_page' : 0,
                'last_page' : 0,
                'from' : 0,
                'to' : 0
				},
				offset: 5,
				disponibles: ""
			}
		},
		methods:{
			get_datos(){
				//SOLICITO LOS PISOS DE VENTAS Y PRODUCTOS
				axios.get('/api/despachos-datos-create').then(response => {

					console.log(response);
					this.piso_ventas = response.data.piso_ventas
					this.inventario = response.data.productos
				}).catch(e => {

				});
			},
			establecer_nombre(id, valor){//COLOCAR EL NOMBRE AL PRODUCTO QUE ESTOY AGREGANDO
				let resultado = this.inventario.find(element => element.id == id)
				this.articulo.nombre = resultado.product_name;
				this.articulo.modelo = resultado
				this.disponibles = resultado.total_qty_prod;
				console.log(this.articulo);
				if(valor == "retiro"){

					this.produc_cantidad = resultado.total_qty_prod;
				}
			},
			agregar_producto(retiro){

				if (retiro == "retiro") {

					this.productos_retirar.push(this.articulo_retiro)
					this.articulo_retiro = {id: "", nombre: "", cantidad: ""};
				}else{
					this.productos.push(this.articulo);

					//console.log(this.productos)
					this.articulo = {id: 0, nombre: "", cantidad: ""};
				}


			},
			eliminar(index, retiro){
				if (retiro == "retiro") {

					this.productos_retirar.splice(index, 1);
				}else{
				this.productos.splice(index, 1);
				}
			},
			despachar(){

				axios.post('/api/despachos', {productos: this.productos, piso_venta: this.piso_venta}).then(response => {
					console.log(response)
					this.articulo = {id: 0, nombre: "", cantidad: ""};
					this.despachos.splice(0,0, response.data);
					this.productos = [];
				}).catch(e => {

					console.log(e.response)
				})
        window.location="/despachos-almacen";
				//$('#modal-nuevo').modal('hide')
				//this.$bvModal.hide("modal-nuevo")
			},
		},
		computed: {
			disabled_nuevo(){

				if (this.articulo.id != 0 && this.articulo.cantidad != ""){

					return false;
				}else{
					return true;
				}
			}
		},
		created(){

			this.get_datos();
		}
	}
</script>
