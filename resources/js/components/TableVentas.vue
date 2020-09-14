<template>
	<div>
		<!--RANGO DE FECHAS-->
		<div class="text-right my-3">
			<form action="/admin" method="get" @submit.prevent="filtrar">
				<input type="date" v-model="fecha_inicial">
				<input type="date" v-model="fecha_final" >
				<button type="submit" class="btn btn-primary">Filtrar</button>
			</form>

		</div>

		<table class="table table-bordered table-hover table-sm table-stridped">
			<thead>
				<tr>
					<th>Factura</th>
					<th>Fecha</th>
					<th>tipo</th>
					<th>Total</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody v-show="ventas != []">
				<tr v-for="(venta, index) in ventas" :key="index" :class="{'bg-danger': venta.anulado == 0 || venta.anulado == 1, 'text-white': venta.anulado == 1 || venta.anulado == 0}">
					<td>FC-00{{venta.id_extra}}</td>
					<td>{{venta.created_at}}</td>
					<td v-if="venta.type == 1">Venta</td>
					<td v-if="venta.type == 2">Compra</td>
					<td>{{venta.total}}</td>
					<td>
						<button type="button" class="btn btn-primary" @click="showModalDetalles(venta.id)">Ver</button>
					</td>




					<!-- Modal VER DETALLES, FACTURA -->
					<div class="modal fade" :id="'modal-detalles-'+venta.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  	<div class="modal-dialog modal-lg">
					    	<div class="modal-content">
					      		<div class="modal-header">
					        		<h5 class="modal-title" id="exampleModalLabel">Detalles de la venta</h5>
					        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          		<span aria-hidden="true">&times;</span>
					        		</button>
					      		</div>
						      	<div class="modal-body">
						       		<table class="table table-bordered">
						      			<thead>
							      			<tr>
							      				<th>Producto</th>
							      				<th>Cantidad</th>
							      				<th>Subtotal</th>
							      				<th>Iva</th>
							      				<th>Total</th>
							      			</tr>
						      			</thead>

						      			<tbody>
						      				<tr v-for="(producto, index) in venta.detalle" :key="index">
						      					<td>{{producto.name}}</td>
						      					<td>{{producto.pivot.cantidad}}</td>
						      					<td>{{producto.pivot.sub_total}}</td>
						      					<td>{{producto.pivot.iva}}</td>
						      					<td>{{producto.pivot.total}}</td>
						      				</tr>
						      			</tbody>

						      		</table>

						      		<div class="row">
						      			<div class="col-md-7">
						      				
						      			</div>

						      			<div class="col-md-2 text-right">
						      				<span class="font-weight-bold">Sub total:</span><br>
						      				<span class="font-weight-bold">iva:</span><br>
						      				<br>
						      				<span class="font-weight-bold">Total:</span>

						      			</div>

						      			<div class="col-md-3">
						      				<span>{{venta.sub_total}}</span><br>
							      			<span>{{venta.iva}}</span><br>
							   				<br>
							      			<span>{{venta.total}}</span>
						      			</div>
						      			
						      		</div>
						      	</div>
						     	<div class="modal-footer">
						        	<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
						      	</div>
					    	</div>
					  	</div>
					</div>
				</tr>

				<tr v-if="ventas == []">
					<td class="text-center">No hay ventas registradas</td>
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
				ventas: [],
				pagination: {//PAGINACION DE RIMORSOFT
				 'total' : 0,
   				'current_page' : 0, 
                'per_page' : 0,
                'last_page' : 0,
                'from' : 0,
                'to' : 0
				},
				offset: 5,
				fecha_inicial: 0,
				fecha_final: 0
			}
		},
		methods:{
			showModalDetalles(id){
				$('#modal-detalles-'+id).modal('show');
			},
			get_ventas(id){

				axios.get('/api/ventas-compras/'+id, {params:{fecha_i: this.fecha_inicial, fecha_f: this.fecha_final}}).then(response => {
					
					this.ventas = response.data.data;
					this.pagination = response.data;
					console.log(response);
				}).catch(e => {
					console.log(e.response)
				});
			},
			verProps(){
				console.log(this.id);
			},
			getKeeps(page){

				axios.get('/api/ventas-compras/'+this.id+'?page='+page).then(response => {
					console.log(response.data)
					this.ventas = response.data.data;
					this.pagination = response.data;
			
				}).catch(e => {
					console.log(e.response)
				});

			},
			changePage(page){//PAGINACION DE RIMORSOft
				this.pagination.current_page = page;
				this.getKeeps(page);
			},
			filtrar(){
				if (this.fecha_inicial != 0 && this.fecha_final != 0) {
					this.get_ventas(this.id);
				}
			}
		},
		watch:{
			id: function(val){
				this.get_ventas(val);
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
		created(){

		}
	}
</script>