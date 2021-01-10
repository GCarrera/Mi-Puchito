<template>
	<div>
		<table class="table table-bordered table-hover table-sm table-stridped">
			<thead>
				<tr>
					<th rowspan="">Producto</th>
					<th>Cantidad</th>
					<th>Precio</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(producto, index) in productos" :key="index">
					<td>{{producto.inventario.name}}</td>
					<td>{{producto.cantidad}}</td>
					<td>{{new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2}).format(producto.inventario.precio.total_menor)}}</td>
					<td>
						<button type="button" class="btn btn-primary" data-toggle="modal" :data-target="'#verDetalles'+producto.id">Detalles</button>
						<b-button id="show-btn" @click="showModal(producto.id, producto.cantidad)" v-b-tooltip.hover title="Editar Cantidad"><i class="fas fa-edit"></i></b-button>
					</td>

					<!-- Modal PARA VER LOS DETALLES -->
					<div class="modal fade" :id="'verDetalles'+producto.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  	<div class="modal-dialog modal-lg">
					    	<div class="modal-content">
					      		<div class="modal-header">
					        		<h5 class="modal-title" id="exampleModalLabel">Detalles del producto</h5>
					        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          		<span aria-hidden="true">&times;</span>
					        		</button>
					      		</div>
					      		<div class="modal-body">

					      			<h5 class="text-center font-weight-bold">{{producto.inventario.name}}</h5>

					      			<table class="table table-bordered">

					      				<thead>
					      					<tr>
					      						<th>Propiedades</th>
					      						<th>Valores</th>

					      					</tr>
					      				</thead>

					      				<tbody>
					      					<tr>
					      						<td>Cantidad:</td>
					      						<td>{{producto.cantidad}}</td>

					      					</tr>

					      					<tr>
					      						<td>Unidad</td>
					      						<td>{{producto.inventario.unit_type_menor}}</td>

					      					</tr>

					      					<!--<tr>
					      						<td>Subtotal</td>
					      						<td>{{producto.inventario.precio.sub_total_menor}}</td>

					      					</tr>

					      					<tr>
					      						<td>Iva</td>
					      						<td>{{producto.inventario.precio.iva_menor}}</td>

					      					</tr>-->

					      					<tr>
					      						<td>Total</td>
					      						<td>{{new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2}).format(producto.inventario.precio.total_menor)}}</td>

					      					</tr>


					      				</tbody>
					      			</table>

					      		</div>
					      		<div class="modal-footer">
					        		<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
					      		</div>
					    	</div>
					  	</div>
					</div>
				</tr>

				<tr v-if="productos == []">
					<td class="text-center">No hay productos registrados</td>
				</tr>
			</tbody>
		</table>

		<b-modal ref="my-modal" size="sm" title="Modificar cantidad" @ok="handleOk">
      <div class="d-block text-center">
				<form ref="form" @submit.stop.prevent="handleSubmit">
	        <b-form-group
	          label="Cantidad"
						label-cols="5"
						label-cols-lg="3"
						label-size="sm"
	          label-for="cantidad-input"
	          invalid-feedback="Ingrese una cantidad valida por favor"
	        >
	          <b-form-input
	            id="cantidad-input"
	            size="sm"
	            v-model="cantidad"
	            required
	            :state="cantidadState"
	            aria-describedby="input-live-feedback"
	          ></b-form-input>
						<input type="hidden" name="idCantidad" v-model="idCantidad">
	        </b-form-group>

					<b-form-invalid-feedback id="input-live-feedback">
			      Ingrese una cantidad valida
			    </b-form-invalid-feedback>
	      </form>
      </div>
			<template #modal-footer="{ ok, cancel }">
	      <!-- Emulate built in modal footer ok and cancel button actions -->
	      <b-button size="sm" variant="success" @click="ok()">
	        Cambiar
	      </b-button>
	      <b-button size="sm" variant="danger" @click="cancel()">
	        Cancelar
	      </b-button>
	    </template>
    </b-modal>

		<nav>
  			<ul class="pagination">
  				<li v-if="pagination.current_page > 1" class="page-item">
  					<a href="#" @click.prevent='changePage(pagination.current_page - 1)' class="page-link">
  						<span>Anterior</span>
  					</a>
  				</li>

  				<li v-for="page in pagesNumber" :class="[page == isActived ? 'active' : '']" class="page-item">
  					<a href="#" @click.prevent="changePage(page)" class="page-link">
  						{{page}}
  					</a>
  				</li>

  				<li v-if="pagination.current_page < pagination.last_page" class="page-item">
  					<a href="#" class="page-link" @click.prevent='changePage(pagination.current_page + 1)'>
  						<span>Siguiente</span>
  					</a>
  				</li>
  			</ul>
  		</nav>

	</div>
</template>

<script>
	export default{
		props: ['id'],
		data(){
			return{
				cantidad:"",
				idCantidad:"",
				productos: [],
				pagination: {//PAGINACION DE RIMORSOFT
				 'total' : 0,
   				'current_page' : 0,
                'per_page' : 0,
                'last_page' : 0,
                'from' : 0,
                'to' : 0
				},
				offset: 5
			}
		},
		methods:{
			checkFormValidity() {
        const valid = this.$refs.form.checkValidity()
        this.nameState = valid
        return valid
      },
			handleOk(bvModalEvt) {
        // Prevent modal from closing
        bvModalEvt.preventDefault()
        // Trigger submit handler
        this.handleSubmit()
      },
			handleSubmit() {
				var exReg = /^\d+(\.\d{1,3})?$/;
        // Exit when the form isn't valid
        if (exReg.test(this.cantidad)) {
					// Push the name to submitted names
					axios.post('/api/piso-venta-cantidad-edit', {idproductos: this.idCantidad, cantidad: this.cantidad}).then(response => {

						this.cantidad = "";
						this.idCantidad = "";

						this.$refs['my-modal'].hide();
						window.location = "/piso-ventas/inventario/"+this.id;
					}).catch(e => {
						console.log(e.response)
					});
					// Hide the modal manually
        } else {
					console.log("ño");
        }

      },
			showModal(id, cant) {
				this.idCantidad = id;
				this.cantidad = cant;
        this.$refs['my-modal'].show()
      },
			get_productos(id){

				axios.get('/api/productos-piso-venta/'+id).then(response => {

					this.productos = response.data.data;
					this.pagination = response.data;
					console.log(response.data);
				}).catch(e => {
					console.log(e.response)
				});
			},
			getKeeps(page){

				axios.get('/api/productos-piso-venta/'+this.id+'?page='+page).then(response => {
					console.log(response.data)
					this.productos = response.data.data;
					this.pagination = response.data;

				}).catch(e => {
					console.log(e.response)
				});

			},
			changePage(page){//PAGINACION DE RIMORSOft
				this.pagination.current_page = page;
				this.getKeeps(page);
			}
		},
		watch:{
			id: function(val){
				this.get_productos(val);
			}
		},
		computed: {//PAGINACION DE RIMORSOFT
			cantidadState() {
				var exReg = /^\d+(\.\d{1,3})?$/;
				//var exReg = /^[0-9]+([.][0-9]+)?$/;
				//var exReg = /(?:\d|\(\d{3}\))([.\/\.])\d{3}/;
				return exReg.test(this.cantidad);
      },
			isActived(){
				return this.pagination.current_page;
			},
			pagesNumber(){
				if(!this.pagination.to){
					return [];
				}

				let from = this.pagination.current_page - this.offset;//TODO Offset

				if(from < 1){
					from = 1;
				}

				let to = from + (this.offset*2);//TODO

				if (to >= this.pagination.last_page) {

					to = this.pagination.last_page;
				}

				let pagesArray = [];
				while(from <= to){
					pagesArray.push(from);
					from++;
				}

				return pagesArray;
			}
		},
		created(){
			this.get_productos(this.id);
		}
	}
</script>
