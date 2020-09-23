<template>
	<div style="height: 100%;">
		<div class="row">
			<div class="col-md-3">
				<div class="card shadow">
					<div class="card-body">
						<select name="piso_venta" id="piso_venta" class="form-control" v-model="piso_venta_id" @change="establecer_piso">
							<option value="">Seleccione el piso de venta</option>
							<option :value="piso.id" v-for="(piso, index) in piso_ventas" :key="index">{{piso.nombre}}</option>
						</select>

						<div v-if="piso_venta_selected != null" style="font-size: 1em;" class="mt-3">
							<span><span class="font-weight-bold">Nombre:</span> {{piso_venta_selected.nombre}}</span> <br>
							<span><span class="font-weight-bold">Lugar:</span> {{piso_venta_selected.ubicacion}}</span> <br>
							<span><span class="font-weight-bold">Dinero:</span> {{piso_venta_selected.dinero}}</span> <br>
							
						</div>
						<hr>
						<span class="font-weight-bold">Ultima vez que sincronizo:</span> <span v-if="count.sincronizacion != null">{{count.sincronizacion.created_at}}</span> <br>
						<span class="font-weight-bold">Ultima vez que vacio la caja:</span> <span v-if="count.caja != null">{{count.caja.created_at}}</span> <br>
					</div>
				</div>
			</div>
			<div class="col-md-9" >
				<div class="card">
					<div class="card-body">	
						<h1 class="text-center font-italic">Resumen del mes:</h1>
						<!--DATOS GLOBALES-->
						<div class="row text-white text-center">
							<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
								<div class="bg-primary rounded shadow">Ventas: {{count.ventas}}</div>
								 
							</div>
							<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
								<div class="bg-danger rounded shadow">Compras: {{count.compras}}</div>							
							</div>
							<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
								<div class="bg-warning rounded shadow">Despachos: {{count.despachos}}</div>
							</div>
							<div class="col-md-3" style="line-height: 5em; font-size: 1.5em;">
								<div class="bg-success rounded shadow">Retiros: {{count.retiros}}</div>
								
							</div>
						</div>
						<!--TABLAS DE VENTAS Y COMPRAS-->
						<div class="mt-3">
							<div class="card shadow">
								<div class="card-body">
									<h4 class="text-center">Ventas y compras</h4>
									<tableVentas :id="piso_venta_id"/>
								</div>
							</div>
						</div>
						<!--TABLAS DE DESPACHOS Y RETIROS-->
						<div class="mt-3">
							<div class="card shadow">
								<div class="card-body">
									<h4 class="text-center">Despachos y retiros</h4>
									<tableDespachos :id="piso_venta_id"/>
								</div>
							</div>
						</div>

						<!--TABLA DE INVENTARIO-->
						<div class="mt-3">
							<div class="card shadow">
								<div class="card-body">
									<h4 class="text-center">Inventario</h4>
									<tableInventario :id="piso_venta_id"/>
								</div>
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

	export default{
		components:{
			tableVentas,
			tableDespachos,
			tableInventario
		},
		data(){
			return{
				piso_ventas: [],
				piso_venta_id: "",
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
			get_piso_ventas(){

				axios.get('/api/get-piso-ventas').then(response => {

					this.piso_ventas = response.data;
					console.log(this.piso_ventas);
				}).catch(e => {
					console.log(e.response)
				});
			},
			establecer_piso(){
				//console.log(this.piso_venta_id);
				this.piso_venta_selected = this.piso_ventas.find(element => element.id == this.piso_venta_id);
				this.resumen();


			},
			resumen(){

				axios.get('/api/resumen/'+this.piso_venta_id).then(response => {
					console.log(response);
					this.count = response.data;
					
				}).catch(e => {
					console.log(e.response)
				});
			}
		},
		created(){
			this.get_piso_ventas();
		}
	}
</script>