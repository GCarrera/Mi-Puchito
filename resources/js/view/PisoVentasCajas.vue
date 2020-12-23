<template>
	<div style="height: 100%;">
		<div class="row">

			<div class="col-md-12" >
				<div class="card">
					<div class="card-body">
						<div style="font-size: 1em;" class="mt-3">
							<h1 class="text-center font-italic">Cierres de Caja de {{dataPiso}}</h1>

							<div class="mt-3">
								<div class="card shadow">
									<div class="card-body">
										<h4 class="text-center">Cajas</h4>
										<tableCajas :id="dataCajas"/>
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
							</div>>-->
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import tableCajas from '../components/TableCajas';

	export default{
		components:{

			tableCajas,
		},
		data(){
			return{
				dataCajas: this.$attrs.datacajas,
				dataPiso: this.$attrs.nombrepiso,
				url_ventas: "a",
				url_inventario: "",
				url_despachos: "",
				url_retiros: "",
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
				this.vista();

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
