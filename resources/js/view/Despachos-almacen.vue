<template>
 <div>
   <div class="container">
     <div class="card shadow">
       <div class="card-body">
         <h1 class="text-center">Despachos-almacen</h1>
         <div class="mb-3">
           <div class="row justify-content-between">
             <div class="col-12 col-md-4">

             </div>
             <div class="col-12 col-md-4">
               <div class="ml-auto">
                 <a type="button" class="btn btn-primary" href="/nuevo-despacho/new">
                   Nuevo
                 </a>
                 <button type="button" class="btn btn-danger" @click="showModalRetiro">Retirar</button>
               </div>
             </div>

           </div>
         </div>

         <table class="table table-bordered text-center">
           <thead>
             <tr>
               <th class="negrita">FECHA</th>
               <th class="negrita">PISO DE VENTA</th>
               <th class="negrita">Tipo</th>
               <th class="negrita">ESTADO</th>
               <th class="negrita">ACCIONES</th>
             </tr>
           </thead>
           <tbody>
             <tr v-for="(despacho, index) in despachos" :key="index">
               <td class="align-middle">{{despacho.created_at}} {{despacho.id}}</td>
               <td class="align-middle">{{despacho.piso_venta.nombre}}</td>
               <td class="align-middle">{{despacho.type == 1? "Despacho" : "Retiro"}}</td>
               <td v-if="despacho.confirmado == 4" class=" align-middle small negrita text-secondary">Pendiente</td>
               <td v-else-if="despacho.confirmado == 1" class=" align-middle small negrita text-success">Confirmado</td>
               <td v-else-if="despacho.confirmado == 2" class=" align-middle small negrita text-danger">Negado</td>
               <td v-else class=" align-middle small negrita text-warning">Incompleto</td>
               <td v-if="despacho.confirmado != 3" class="align-middle">
                 <button class="btn btn-primary" @click="showModalDetalles(despacho)">Ver</button>
                 <a :href="'/get-despacho/'+despacho.id" role="button" class="btn btn-primary" target="_blank">Imprimir</a>
                 <!--<button class="btn btn-primary" @click="showPdf(despacho)">Imprimir</button>-->
                 <!--<button class="btn btn-primary" data-toggle="modal" data-target="#modalVer">Ver</button>-->

               </td>
               <td v-else class="align-middle">
                 <a :href="'/terminar-despacho/'+despacho.id" role="button" class="btn btn-primary">Editar</a>
               </td>

             </tr>

             <tr v-if="despachos == []">
               <td class="text-center">No hay despachos registrados</td>
             </tr>

           </tbody>

         </table>
         <!-- Modal PARA VER LOS DETALLES -->
         <div class="modal fade" id="modalVer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

                     <tr v-for="(product, index) in dataModal.productos" :key="index">
                       <td>{{product.product_name}}</td>
                       <!--<td v-if="product.pivot.tipo == 1">al menor</td>
                       <td v-if="product.pivot.tipo == 2">al mayor</td>-->
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
         <!--
         <div class="overflow-auto">
           <b-pagination v-model="currentPage" @change="paginar($event)" :per-page="per_page"  :total-rows="total_paginas" size="sm"></b-pagination>
         </div>
         -->

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
     </div>
   </div>

   <!-- Modal RETIRO DESPACHO-->

   <div class="modal fade" id="modal-retiro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Retirar productos de algun piso de venta</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
               </button>
             </div>
             <form action="/despachos-almacen" method="post" @submit.prevent="retirar()"><!--Formulario-->
             <div class="modal-body">
               <select class="form-control" v-model="piso_venta_retiro" @change="buscar_inventario">
             <option value="">Seleccione un piso de venta</option>
             <option v-for="(piso, index) in piso_ventas" :key="index" :value="piso.id">{{piso.nombre}}</option>
           </select>

           <div class="form-row">
             <div class="form-group col-md-3">
               <label for="producto">Producto:</label>
                 <select class="form-control" v-model="articulo_retiro.id" @change="establecer_nombre_retiro(articulo_retiro.id)" :disabled="disab">
                 <option value="">Seleecion producto</option>
                 <option v-for="(prod, index) in inventario_piso_venta" :key="index" :value="prod.inventory_id">{{prod.name}}</option>
               </select>
             </div>

             <div class="form-group col-md-3">
               <label for="cantidad">Cantidad disponible:</label>
               <input type="number" name="cantidad_disponible" id="cantidad" placeholder="Cantidad disponible" class="form-control" v-model="inventario_cantidad_piso" disabled>
             </div>

             <div class="form-group col-md-3">
               <label for="cantidad">Cantidad al menor:</label>
               <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad" class="form-control" v-model="articulo_retiro.cantidad">
             </div>

             <div class="form-group col-md-3">
               <label class="text-center" for="">Acción:</label><br>
               <button class="btn btn-primary btn-block" type="button" @click="agregar_producto('retiro')" :disabled="disabled_retiro">Agregar</button>
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
               <tr v-for="(produc_enviar, index) in productos_retirar" :key="index">
                 <td>{{produc_enviar.nombre}}</td>
                 <td>{{produc_enviar.cantidad}}</td>
                 <td>
                   <button class="btn btn-danger" type="button" @click="eliminar(index,'retiro')">Eliminar</button>
                 </td>
               </tr>
             </tbody>
           </table>
             </div>
             <div class="modal-footer">
               <button type="submit" class="btn btn-primary" :disabled="productos_retirar.length <= 0">Retirar</button>
             </div>
             </form>
         </div>
       </div>
   </div>

   <!---->
   <!--
   <b-modal id="modal-retiro" size="lg" title="Retirar productos de algun piso de venta" hide-footer>

             <form action="/despachos-almacen" method="post" @submit.prevent="retirar()">

               <select class="form-control" v-model="piso_venta_retiro" @change="buscar_inventario">
             <option value="">Seleccione un piso de venta</option>
             <option v-for="(piso, index) in piso_ventas" :key="index" :value="piso.id">{{piso.nombre}}</option>
           </select>

           <div class="form-row">
             <div class="form-group col-md-3">
               <label for="producto">Producto:</label>
                 <select class="form-control" v-model="articulo_retiro.id" @change="establecer_nombre_retiro(articulo_retiro.id)" :disabled="disab">
                 <option value="">Seleecion producto</option>
                 <option v-for="(prod, index) in inventario_piso_venta" :key="index" :value="prod.inventory_id">{{prod.name}}</option>
               </select>
             </div>

             <div class="form-group col-md-3">
               <label for="cantidad">Cantidad disponible:</label>
               <input type="number" name="cantidad_disponible" id="cantidad" placeholder="Cantidad disponible" class="form-control" v-model="inventario_cantidad_piso" disabled>
             </div>

             <div class="form-group col-md-3">
               <label for="cantidad">Cantidad al menor:</label>
               <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad" class="form-control" v-model="articulo_retiro.cantidad">
             </div>

             <div class="form-group col-md-3">
               <label class="text-center" for="">Acción:</label><br>
               <button class="btn btn-primary btn-block" type="button" @click="agregar_producto('retiro')">Agregar</button>
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
               <tr v-for="(produc_enviar, index) in productos_retirar" :key="index">
                 <td>{{produc_enviar.nombre}}</td>
                 <td>{{produc_enviar.cantidad}}</td>
                 <td>
                   <button class="btn btn-danger" type="button" @click="eliminar(index,'retiro')">Eliminar</button>
                 </td>
               </tr>
             </tbody>
           </table>

             <div class="modal-footer">
               <button type="submit" class="btn btn-primary" data-dismiss="modal">Retirar</button>
             </div>
             </form>

   </b-modal>
   -->


 </div>
</template>

<script>
 export default{
   data(){
     return{
       dataModal: [],
       despachos: [],
       piso_ventas: [],
       inventario: [],
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
       disponibles: ""
     }
   },
   methods:{
     showModalDetalles(id){
       console.log(id);
       this.dataModal = id;
       $('#modalVer').modal('show');
     },

     showPdf(data){
       console.log(data);
       //axios.get('/get-despacho/', {data: data.id}).then(response => {
       axios.get('/get-despacho/'+data.id).then(response => {
         console.log(response);
       }).catch(e => {
         console.log(e.response)
       });
     },

     get_despachos(){

       axios.get('/api/get-despachos-almacen').then(response => {
         //console.log(response.data);
         this.per_page = response.data.per_page;
         this.total_paginas = response.data.total;
         this.despachos = response.data.data

         console.log(this.despachos)
       }).catch(e => {
         console.log(e.response)
       });
     },
     get_datos(){
       //SOLICITO LOS PISOS DE VENTAS Y PRODUCTOS
       axios.get('/api/despachos-datos-create').then(response => {

         console.log(response);
         this.piso_ventas = response.data.piso_ventas
         this.inventario = response.data.productos
       }).catch(e => {

       });
     },
     establecer_nombre(id, valor){//COLOCAR EL NOMBRE AL PRODUCTO QUE ESTOY AGREGANDO
       let resultado = this.inventario.find(element => element.id == id)
       this.articulo.nombre = resultado.product_name;
       this.articulo.modelo = resultado
       this.disponibles = resultado.total_qty_prod;
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
     showModalNuevo(){

       this.get_datos();
       $('#modal-nuevo').modal('show')
       //this.$bvModal.show("modal-nuevo")
     },
     despachar(){

       axios.post('/api/despachos', {productos: this.productos, piso_venta: this.piso_venta}).then(response => {
         console.log(response)
         this.articulo = {id: 0, nombre: "", cantidad: ""};
         this.despachos.splice(0,0, response.data);
         this.productos = [];
       }).catch(e => {

         console.log(e.response)
       })
       $('#modal-nuevo').modal('hide')
       //this.$bvModal.hide("modal-nuevo")
     },
     paginar(event){

       axios.get('/api/get-despachos-almacen?page='+event).then(response => {
         console.log(response.data)
         this.per_page = response.data.per_page;
         this.total_paginas = response.data.total;
         this.despachos = response.data.data

       }).catch(e => {
         console.log(e.response)
       });
     },
     showModalRetiro(){//MODAL PARA RETIRAR DE UN ALMACEN

       this.get_datos();
       $('#modal-retiro').modal('show')
       //this.$bvModal.show("modal-retiro")
     },
     get_datos_retiro(){


     },
     retirar(){

       axios.post('/api/despachos-retiro', {productos: this.productos_retirar, piso_venta: this.piso_venta_retiro}).then(response => {
         console.log(response)
         this.articulo_retiro = {id: "", nombre: "", cantidad: ""};
         this.despachos.splice(0,0, response.data);
         this.productos_retirar = [];
       }).catch(e => {

         console.log(e.response)
       })
       $('#modal-retiro').modal('hide')
       this.$bvModal.hide("modal-retiro")
     },
     buscar_inventario(){
       //console.log(this.piso_venta_retiro)

       axios.get('/api/inventario-piso-venta/'+this.piso_venta_retiro).then(response => {
         console.log(response)
         this.inventario_piso_venta = response.data
         this.disab = false;

       }).catch(e => {
         console.log(e.response)
       });

     },
     establecer_nombre_retiro(id){

       let resultado = this.inventario_piso_venta.find(element => element.inventory_id == id)
       this.articulo_retiro.nombre = resultado.name;
       this.articulo_retiro.modelo = resultado
       this.inventario_cantidad_piso = resultado.piso_venta[0].pivot.cantidad
     },
     getKeeps(page){

       axios.get('/api/get-despachos-almacen?page='+page).then(response => {
         console.log(response.data)
         this.despachos = response.data.despachos.data
         this.pagination = response.data.pagination;

       }).catch(e => {
         console.log(e.response)
       });

     },
     changePage(page){//PAGINACION DE RIMORSOft
       this.pagination.current_page = page;
       this.getKeeps(page);
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
       ////////////////////////////////////////////////7
     },
     disabled_nuevo(){

       if (this.articulo.id != 0 && this.articulo.cantidad != ""){

         return false;
       }else{
         return true;
       }
     },
     disabled_retiro(){
       if (this.articulo_retiro.id != "" && this.articulo_retiro.cantidad != ""){

         return false;
       }else{
         return true;
       }
     }
   },
   created(){

     //this.get_despachos();
     this.getKeeps();
   }
 }
</script>
