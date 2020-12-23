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
					<th>Monto</th>
					<th>Fecha</th>
				</tr>
			</thead>
			<tbody v-show="cajas != []">
				<tr v-for="(caja, index) in cajas" :key="index">
					<td>{{new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2}).format(caja.monto)}}</td>
					<td>{{caja.created_at}}</td>

				</tr>

				<tr v-if="cajas == []">
					<td class="text-center">No se a vaciado caja aún.</td>
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
				cajas: [],
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
			get_all_cajas(id){

				axios.get('/api/all-cajas/'+id).then(response => {
					this.cajas = response.data.data;
					this.pagination = response.data;
				}).catch(e => {
					console.log(e.response)
				});
			},
			get_cajas(id){

				axios.get('/api/cajas/'+id, {params:{fecha_i: this.fecha_inicial, fecha_f: this.fecha_final}}).then(response => {
					console.log("get_cajas");
					console.log(response);
					this.cajas = response.data.data;
					this.pagination = response.data;
				}).catch(e => {
					console.log(e.response)
				});
			},
			verProps(){
				console.log(this.id);
			},
			getKeeps(page){

				axios.get('/api/cajas/'+this.id+'?page='+page).then(response => {
					console.log(response.data)
					this.cajas = response.data.data;
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
					this.get_cajas(this.id);
				}
			}
		},
		watch:{
			id: function(val){
				this.get_cajas(val);
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
			this.get_cajas(this.id);
		}
	}
</script>
