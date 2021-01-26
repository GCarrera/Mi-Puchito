<template>
 <div>
   <div class="container">
     <div class="card shadow">
       <div class="card-body">
         <h1 class="text-center">Nuevo Despacho</h1>
         <div class="mb-3">

           <form action="/despachos-almacen" method="post" @submit.prevent="despachar()" onkeydown="return event.key != 'Enter';"><!--Formulario-->


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
                 <v-select id="producto" @input="setFocus" :labelSearchPlaceholder="labelSearch" :labelNotFound="labelNot" :labelTitle="labelTit" :options="inventarioSelect" v-model="selectedValue" searchable showDefaultOption/>
                   <!--<select class="form-control" v-model="articulo.id" @change="establecer_nombre(articulo.id)">
                   <option value="0">Seleecion producto</option>
                   <option v-for="(prod, index) in inventario" :key="index" :value="prod.id">{{prod.product_name}}</option>
                 </select>-->
               </div>

               <div class="form-group col-md-2">
                 <label for="cantidad">Disponibles:</label>
                 <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad" class="form-control" v-model="disponibles" disabled="">
               </div>

               <div class="form-group col-md-2">
                 <label for="cantidad">Cantidad al menor:</label>
                 <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad" class="form-control" v-model="articulo.cantidad" ref="cantidad" v-on:keyup.enter="agregar_producto_enter">
               </div>

               <div class="form-group col-md-1">
                 <label class="text-center" for="">Acción:</label><br>
                 <button class="btn btn-primary btn-block" type="button" @click="agregar_producto" :disabled="disabled_nuevo"><i class="fas fa-plus" data-toggle="tooltip" data-title="Agregar"></i></button>
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

               <span v-if="loading == false">
                 <button type="submit" class="btn btn-primary" :disabled="productos.length <= 0">
                   Despachar
                 </button>
               </span>

               <span v-if="loading == true">
                 <button type="submit" class="btn btn-primary" :disabled="true">
                   <div class="spinner-border text-light text-center" role="status" v-if="loading == true">
                     <span class="sr-only">Cargando...</span>
                   </div>
                 </button>
               </span>


           </form>

         </div>

       </div>
     </div>
   </div>

 </div>
</template>

<script>
 import VSelect from '@alfsnd/vue-bootstrap-select'
 export default{
   components: {
       VSelect,
   },
   data(){
     return{
       toastCount: 0,
       loading: false,
       despachos: [],
       piso_ventas: [],
       inventario: [],
       inventarioSelect: [],
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
       disponibles: "",
       //data select vue
       selectedValue: null,
       labelSearch: "Buscar",
       labelNot: 'No se encontro nada',
       labelTit: "Nada seleccionado"
     }
   },
   methods:{
     makeToast(variant = null) {
      this.toastCount++
      this.$bvToast.toast(`Estamos procesando el despacho por espere un momento, gracias`, {
        title: 'Excelente!!!',
        autoHideDelay: 5000,
        variant: variant,
        solid: true,
        //toaster: 'b-toaster-bottom-left',

      })
    },
     setFocus(){
       this.$refs.cantidad.focus();
       console.log("setFocus");
     },
     agregar_producto_enter(){
       var validation = parseFloat(this.disponibles)-parseFloat(this.articulo.cantidad);

       if (this.articulo.id != 0 && this.articulo.cantidad != "" && validation >= 0){

         this.agregar_producto();

       }
     },
     get_datos(){
       console.log("get_datos");
       //SOLICITO LOS PISOS DE VENTAS Y PRODUCTOS
       axios.get('/api/despachos-datos-create').then(response => {

         console.log(response);
         this.piso_ventas = response.data.piso_ventas
         this.inventario = response.data.productos

         this.inventario.forEach(item => {

           let datos = {value: item.id, text: item.product_name}
           this.inventarioSelect.push(datos);
         });
         //console.log(this.inventarioSelect);
       }).catch(e => {

       });
     },
     establecer_nombre(id, valor){//COLOCAR EL NOMBRE AL PRODUCTO QUE ESTOY AGREGANDO
       console.log('establecer_nombre');
       //console.log(id);
       //console.log(valor);
       let resultado = this.inventario.find(element => element.id == id)
       this.articulo.nombre = resultado.product_name;
       this.articulo.modelo = resultado;
       this.articulo.id = resultado.id;
       this.disponibles = resultado.total_qty_prod;
       console.log('this.articulo');
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

       this.loading = true;
       this.makeToast('info');

       //console.log(this.productos);

       axios.post('/api/despachos', {productos: this.productos, piso_venta: this.piso_venta}).then(response => {
         console.log(response)
         this.articulo = {id: 0, nombre: "", cantidad: ""};
         this.despachos.splice(0,0, response.data);
         this.productos = [];
         this.loading = false;
         window.location="/despachos-almacen";
       }).catch(e => {

         console.log(e.response)
       })
       //$('#modal-nuevo').modal('hide')
       //this.$bvModal.hide("modal-nuevo")
     },
   },
   computed: {
     disabled_nuevo(){
       console.log('disabled_nuevo');
       console.log(this.articulo.id);
       console.log(this.articulo.cantidad);
       var validation = parseFloat(this.disponibles)-parseFloat(this.articulo.cantidad);

       if (this.articulo.id != 0 && this.articulo.cantidad != "" && validation >= 0){

         return false;
       }else{
         return true;
       }
     }
   },
   created(){

     this.get_datos();
   },
   watch:{
     selectedValue: function (val) {
       if (val != null) {
         this.establecer_nombre(val.value)
         console.log('selectedValue');
             console.log(val)
           }
       }
   }
 }
</script>
