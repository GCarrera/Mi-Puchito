<template>
	<div style="height: 100%;">
		<div class="row">
			<div class="col-md-3">
				<div class="card shadow">
					<div class="card-body">
						<select name="piso_venta" id="piso_venta" class="form-control" v-model="piso_venta_id" @change="establecer_piso">
							<option value="">Seleccione el piso de ventas</option>
							<option :value="piso.id" v-for="(piso, index) in piso_ventas" :key="index">{{piso.nombre}}</option>
						</select>

						<div v-if="piso_venta_selected != null" style="font-size: 1em;" class="mt-3">
							<span><span class="font-weight-bold">Nombre:</span> {{piso_venta_selected.nombre}}</span> <br>
							<span><span class="font-weight-bold">Lugar:</span> {{piso_venta_selected.ubicacion}}</span> <br>
							<span><span class="font-weight-bold">Dinero:</span> {{new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2}).format(piso_venta_selected.dinero)}}</span> <br>
							<span class="font-weight-bold">Cajas Vaciadas Hoy:</span> <span v-if="count.caja != null">{{count.caja}} <a v-bind:href="url_cajas" class="fas fa-search text-secondary" title="Ver Detalles"></a></span>
						</div>
						<hr>
						<!--<span class="font-weight-bold">Ultima vez que sincronizo:</span> <span v-if="count.sincronizacion != null">{{count.sincronizacion.created_at}}</span> <br>
						<span class="font-weight-bold">Ultima vez que vacio la caja:</span> <span v-if="count.caja != null">{{count.caja.created_at}}</span> <br>-->
					</div>
				</div>
			</div>
			<div class="col-md-9" >
				<div class="card">
					<div class="card-body">
						<div v-if="piso_venta_selected != null" style="font-size: 1em;" class="mt-3">
							<h1 class="text-center font-italic">Resumen de {{piso_venta_selected.nombre}}</h1>
						<!--DATOS GLOBALES-->
						<div class="row text-white text-center">
							<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
								<a v-bind:href="url_ventas" class="btn btn-secondary btn-lg" tabindex="-1" role="button" aria-disabled="true">Ventas: {{count.ventas}}</a>
							</div>
							<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
								<a v-bind:href="url_inventario" class="btn btn-secondary btn-lg" tabindex="-1" role="button" aria-disabled="true">Inventario: {{count.compras}}</a>
							</div>
							<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
								<a v-bind:href="url_despachos" class="btn btn-secondary btn-lg" tabindex="-1" role="button" aria-disabled="true">Despachos: {{count.despachos}}</a>
							</div>
							<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
								<a v-bind:href="url_retiros" class="btn btn-secondary btn-lg" tabindex="-1" role="button" aria-disabled="true">Retiros: {{count.retiros}}</a>
							</div>
						</div>

						<!--<div class="row text-white text-center">
							<div class="col-md-6" style="line-height: 5em; font-size: 1.5em;">
								<a v-bind:href="url_cajas" class="btn btn-secondary btn-lg" tabindex="-1" role="button" aria-disabled="true">Caja: {{new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2}).format(piso_venta_selected.dinero)}}</a>
							</div>
							<div class="col-md-6" style="line-height: 5em; font-size: 1.5em;">
								<a v-bind:href="url_retiros" class="btn btn-secondary btn-lg" tabindex="-1" role="button" aria-disabled="true">Retiros: {{count.retiros}}</a>

							</div>
						</div>-->
						<!--<div v-if="tipo != null" class="mt-3" id="vista">
							<div class="card shadow">
								<div class="card-body">
									<div v-if="tipo === 'ventas'">
										<h4 class="text-center">Ventas de {{piso_venta_selected.nombre}}</h4>
										<tableVentas :id="piso_venta_id"/>
									</div>
								</div>
							</div>
						</div>
						<!--TABLAS DE VENTAS Y COMPRAS
						<div class="mt-3">
							<div class="card shadow">
								<div class="card-body">
									<h4 class="text-center">Ventas y compras</h4>
									<tableVentas :id="piso_venta_id"/>
								</div>
							</div>
						</div>-->
						<!--TABLAS DE DESPACHOS Y RETIROS
						<div class="mt-3">
							<div class="card shadow">
								<div class="card-body">
									<h4 class="text-center">Despachos y retiros</h4>
									<tableDespachos :id="piso_venta_id"/>
								</div>
							</div>
						</div>-->

						<!--TABLA DE INVENTARIO
						<div class="mt-3">
							<div class="card shadow">
								<div class="card-body">
									<h4 class="text-center">Inventario</h4>
									<tableInventario :id="piso_venta_id"/>
								</div>
							</div>
						</div>-->

					</div> <!-- end if piso_venta_selected != null -->
					<div v-else>
						<h1 class="text-center text-italic">Pisos de Ventas Activos</h1>

						<div class="list-group">
						  <a v-for="(piso, index) in piso_ventas" :key="index" href="#" class="list-group-item list-group-item-action">
						    <div class="d-flex w-100 justify-content-between">
						      <h5 class="mb-1">{{piso.nombre}}</h5>
						      <small>{{new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2}).format(piso.dinero)}}</small>
						    </div>
						    <p class="mb-1">{{piso.ubicacion}}</p>
								<small v-if="piso.sincro != null" class="text-muted">Ultima Actualización: {{piso.sincro.created_at}}</small>
								<small v-else class="text-muted">Ultima Actualización: No a Actualizado Nunca</small>
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
			vista(tipo){
				this.url_ventas = '/piso-ventas/ventas/'+this.piso_venta_id;
				this.url_inventario = '/piso-ventas/inventario/'+this.piso_venta_id;
				this.url_despachos = '/piso-ventas/despachos/'+this.piso_venta_id;
				this.url_retiros = '/piso-ventas/retiros/'+this.piso_venta_id;
				this.url_cajas = '/piso-ventas/cajas/'+this.piso_venta_id;
			},
			get_piso_ventas(){

				axios.get('/api/get-piso-ventas').then(response => {

					this.piso_ventas = response.data;
					console.log(this.piso_ventas);
				}).catch(e => {
					console.log(e.response)
				});
			},
			establecer_piso(){
				console.log("this is establecer_piso hey");
				//console.log(this.piso_venta_id);
				this.piso_venta_selected = this.piso_ventas.find(element => element.id == this.piso_venta_id);
				this.resumen();
				this.vista();

			},
			resumen(){
				console.log("this is resumen");
				axios.get('/api/resumen/'+this.piso_venta_id).then(response => {
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
