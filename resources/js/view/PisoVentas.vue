<template>
	<div style="height: 100%;">
		<div class="row">

			<div class="col-md-12" >
				<div class="card">
					<div class="card-body">
						<div v-if="piso_venta_selected != null" style="font-size: 1em;" class="mt-3">
							<span id="atras" style="cursor: pointer;" class="d-inline-block" tabindex="0">
								<b-icon icon="arrow-left-circle-fill" font-scale="2" variant="primary" v-on:click="ir_atras()"></b-icon>
						  </span>
						  <b-tooltip target="atras">Atras</b-tooltip>
							<h1 class="text-center">Resumen de {{piso_venta_selected.nombre}}</h1>
						<!--DATOS GLOBALES-->
						<div class="row text-white text-center">
							<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
								<b-button v-bind:href="url_ventas" variant="outline-primary">Ventas: {{count.ventas}}</b-button>
							</div>
							<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
								<b-button v-bind:href="url_inventario" variant="outline-primary">Inventario: {{count.compras}}</b-button>
							</div>
							<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
								<b-button v-bind:href="url_despachos" variant="outline-primary">Despachos: {{count.despachos}}</b-button>
							</div>
							<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
								<b-button v-bind:href="url_retiros" variant="outline-primary">Retiros: {{count.retiros}}</b-button>
							</div>
						</div>

					</div> <!-- end if piso_venta_selected != null -->
					<div v-else>
						<h1 class="text-center text-italic">Pisos de Ventas Activos</h1>

						<div class="list-group">
						  <a v-for="(piso, index) in piso_ventas" :key="index" href="#" class="list-group-item list-group-item-action" v-on:click="establecer_piso(piso.id, $event)">
						    <div class="d-flex w-100 justify-content-between">
						      <h5 class="mb-1">{{piso.nombre}}</h5>
						      <small>{{new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2}).format(piso.dinero)}}</small>
						    </div>
						    <p class="mb-1">{{piso.ubicacion}}</p>
								<div class="d-flex w-100 justify-content-between">
									<small v-if="piso.sincro != null" class="text-muted">Ultima Actualización: {{piso.sincro.created_at}}</small>
									<small v-else class="text-muted">Ultima Actualización: No a Actualizado Nunca</small>

									<span id="cajas_vaciadas">
							      <small>
											<a v-bind:href="'/piso-ventas/cajas/'+piso.id" class="text-secondary">
												Cajas Vaciadas Hoy: <span v-if="count.caja != null">{{count.caja}} <b-icon icon="search" font-scale="1" variant="secondary"></b-icon></span>
											</a>
										</small>
									</span>
									<b-tooltip target="cajas_vaciadas">Ver Detalles</b-tooltip>

						    </div>
						  </a>
						</div>
					</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import tableVentas from '../components/TableVentas';
	import tableDespachos from '../components/TableDespachos';
	import tableInventario from '../components/TableInventario';
	import tableCajas from '../components/TableCajas';

	export default{
		components:{
			tableVentas,
			tableDespachos,
			tableCajas,
			tableInventario
		},
		data(){
			return{
				pv: "pv-",
				url_ventas: "a",
				url_inventario: "",
				url_despachos: "",
				url_retiros: "",
				url_cajas: "",
				piso_ventas: [],
				piso_venta_id: "",
				tipo: null,
				piso_venta_selected: null,
				count:{
					ventas: 0,
					compras: 0,
					despachos: 0,
					retiros: 0,
					sincronizacion: 0,
					caja: 0
				},
			}
		},
		methods:{
			collapsePv(id){
				console.log(id);
				$('#'+id).collapse({
				  toggle: false
				})
			},
			vista(id){
				this.url_ventas = '/piso-ventas/ventas/'+id;
				this.url_inventario = '/piso-ventas/inventario/'+id;
				this.url_despachos = '/piso-ventas/despachos/'+id;
				this.url_retiros = '/piso-ventas/retiros/'+id;
				this.url_cajas = '/piso-ventas/cajas/'+id;
			},
			get_piso_ventas(){

				axios.get('/api/get-piso-ventas').then(response => {

					this.piso_ventas = response.data;
					console.log(this.piso_ventas);
				}).catch(e => {
					console.log(e.response)
				});
			},

			ir_atras(){
				this.piso_venta_selected = null;
			},
			establecer_piso(id, event){
				console.log("this is establecer_piso hey");
				//console.log(this.piso_venta_id);
				var cajasDom = event.path.find(element => element.id == "cajas_vaciadas");
				if (cajasDom === undefined) {

					this.piso_venta_selected = this.piso_ventas.find(element => element.id == id);
					console.log(this.piso_ventas);
					this.resumen(id);
					this.vista(id);

				}

			},
			resumen(id){
				console.log("this is resumen");
				axios.get('/api/resumen/'+id).then(response => {
					console.log("respuesta de resumen");
					console.log(response);
					this.count = response.data;

				}).catch(e => {
					console.log("error de resumen");
					console.log(e.response)
				});
			}
		},
		created(){
			this.get_piso_ventas();
		}
	}
</script>
