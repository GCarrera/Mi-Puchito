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
					<td>{{producto.inventario.precio.total_menor}}</td>
					<td>
						<button type="button" class="btn btn-primary" data-toggle="modal" :data-target="'#verDetalles'+producto.id">Detalles</button>
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
					      						<th>propiedades</th>
					      						<th>Valores</th>

					      					</tr>
					      				</thead>

					      				<tbody>
					      					<tr>
					      						<th>Cantidad</th>
					      						<td>{{producto.cantidad}}</td>
					      				
					      					</tr>
					      					
					      					<tr>
					      						<th>Unidad</th>
					      						<td>{{producto.inventario.unit_type_menor}}</td>
					      					
					      					</tr>

					      					<tr>
					      						<td>Subtotal</td>
					      						<td>{{producto.inventario.precio.sub_total_menor}}</td>
					      					
					      					</tr>

					      					<tr>
					      						<td>Iva</td>
					      						<td>{{producto.inventario.precio.iva_menor}}</td>
					      					
					      					</tr>

					      					<tr>
					      						<td>Total</td>
					      						<td>{{producto.inventario.precio.total_menor}}</td>
					      			
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
			}////////////////////////////////////////////////7
		},
	}
</script>