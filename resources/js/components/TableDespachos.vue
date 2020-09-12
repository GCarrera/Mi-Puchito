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
			<thead class="">
				<tr>
					<th>Fecha</th>
					<th>tipo</th>
					<th>Confirmado</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(despacho, index) in despachos" :key="index">
					<td>{{despacho.created_at}} {{despacho.id}}</td>
					<th>{{despacho.type == 1? "despacho" : "retiro"}}</th>		
					<td v-if="despacho.confirmado == null" class="small font-weight-bold">No se ah confirmado</td>
					<td v-else class="small font-weight-bold">{{despacho.confirmado == 1 ? "confirmado" : "negado"}}</td>
					<td>
						<button class="btn btn-primary" data-toggle="modal" :data-target="'#modalVer-'+despacho.id">Ver</button>
					</td>

					<!-- Modal PARA VER LOS DETALLES -->
					<div class="modal fade" :id="'modalVer-'+despacho.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  	<div class="modal-dialog">
					    	<div class="modal-content">
					      		<div class="modal-header">
					        		<h5 class="modal-title" id="exampleModalLabel">Detalles</h5>
					        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          		<span aria-hidden="true">&times;</span>
					        		</button>
					      		</div>
					      		<div class="modal-body">
					        	
					      		<table class="table table-bordered">
								<thead>
									<tr>
										<th>Producto</th>
										<th>cantidad</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(product, index) in despacho.productos" :key="index">
										<td>{{product.product_name}}</td>
										<td>{{product.pivot.cantidad}}</td>
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

				<tr v-if="despachos == []">
					<td class="text-center">No hay despachos registrados</td>
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
				despachos: [],
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
			get_despachos(id){

				axios.get('/api/despachos-retiros/'+id, {params:{fecha_i: this.fecha_inicial, fecha_f: this.fecha_final}}).then(response => {

					this.despachos = response.data.data;
					this.pagination = response.data;
				}).catch(e => {
					console.log(e.response)
				});
			},
			getKeeps(page){

				axios.get('/api/despachos-retiros/'+this.id+'?page='+page, {params:{fecha_i: this.fecha_inicial, fecha_f: this.fecha_final}}).then(response => {
					console.log(response.data)
					this.despachos = response.data.data;
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
					this.get_despachos(this.id);
				}
			}
		},
		watch:{
			id: function(val){
				this.get_despachos(val);
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
		}
	}
</script>