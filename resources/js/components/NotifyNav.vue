<template>

  <div v-if="sales.length > 0">

    <li class="nav-item dropdown d-none d-sm-block">
      <span class="nav-link dropdown-toggle" id="dropdownNotifyNav" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell" style="color: #17a2b8 !important;"></i>
      </span>
      <div class="dropdown-menu" aria-labelledby="dropdownNotifyNav">
        <div v-for="sale in sales">

          <a class="dropdown-item" href="#" @click="hideNotify(sale.id)">
            <h6>FC-{{ rellenar(sale.id, 4) }}</h6>
            <span class="text-muted small text-capitalize">{{ sale.confirmacion }} - <span class="text-success">{{ new Intl.NumberFormat("de-DE", {minimumFractionDigits: 2, maximumFractionDigits: 2}).format(sale.amount) }}$</span></span>
          </a>
          <div class="dropdown-divider"></div>

        </div>
      </div>
    </li>

  </div>

</template>

<script>
    export default {
      props: {
        /*data:{
          type: Array,
          required: true,
          default: () => []
        },*/
      },
  		data(){
  			return{
          sales: [],
  			}
  		},
      mounted() {
        window.Echo.channel('venta-status')
        .listen('NotificacionVentaStatusChangedEvent', (e) => {
          this.getNotify();
        });
      },
      methods: {
        rellenar(id, len) {
          return "0".repeat(len - id.toString().length) + id.toString();
        },
        getNotify(){
            axios.get('/notificaciones')
            .then(response=>{
              if (response.data != 'false' && response.data.length > 0) {
                this.sales = response.data;
              }
            }).catch(e => {
              console.log(e.response)
            });
        },
        hideNotify(id) {
          axios.get('/finalizar-notificacion/'+id)
          .then(response=>{
            window.location = '/perfil';
          }).catch(e => {
            console.log(e.response)
          });
      	}
      },
      created(){
  			this.getNotify();
  		}
    }
</script>
