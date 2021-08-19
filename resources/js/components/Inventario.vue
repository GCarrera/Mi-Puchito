<template>
  <b-container fluid>
    <!-- User Interface controls -->
    <b-row>

      <b-col sm="2" md="3" class="my-1">
        <b-form-group
          label="Mostrar"
          label-for="per-page-select"
          label-cols-sm="8"
          label-cols-md="6"
          label-cols-lg="5"
          label-align-sm="left"
          label-size="sm"
          class="mb-0"
        >
          <b-form-select
            id="per-page-select"
            v-model="perPage"
            :options="pageOptions"
            size="sm"
          ></b-form-select>
        </b-form-group>
      </b-col>

      <b-col sm="8" lg="6" class="my-1">
        <b-form-group
          label="Buscar"
          label-for="filter-input"
          label-cols-sm="3"
          label-align-sm="right"
          label-size="sm"
          class="mb-0"
        >
          <b-input-group size="sm">
            <b-form-input
              id="filter-input"
              v-model="filter"
              type="search"
              placeholder="..."
            ></b-form-input>

            <b-input-group-append>
              <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
            </b-input-group-append>
          </b-input-group>
        </b-form-group>
        
      </b-col>
      <b-col sm="2" lg="3" class="my-1">
        <b-form-group
          label="Mostrar"
          label-for="per-page-select"
          label-cols-sm="8"
          label-cols-md="6"
          label-cols-lg="5"
          label-align-sm="right"
          label-size="sm"
          class="mb-0"
        >
          <b-form-checkbox v-model="checkZero" name="check-button" switch @change="changeData">
            <small>Sin Existencia</small>
          </b-form-checkbox>
        </b-form-group>
      </b-col>

    </b-row>

    <!-- Main table element -->
    <b-table
      :items="inventario"
      :fields="fields"
      :current-page="currentPage"
      :per-page="perPage"
      :filter="filter"
      :filter-included-fields="filterOn"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      :sort-direction="sortDirection"
      stacked="md"
      show-empty
      small
      @filtered="onFiltered"
      empty-filtered-text="No se consiguieron coincidencias"
      empty-text="No hay datos para mostrar"
    >

      <template #cell(actions)="row">
        <b-button v-b-tooltip.hover title="Editar" size="sm" @click="editmodal(row.item, row.index, $event.target)" class="mr-1" variant="info">
          <b-icon-pencil></b-icon-pencil>
        </b-button>
        <b-button v-b-tooltip.hover title="Sumar Productos" size="sm" @click="plusmodal(row.item, row.index, $event.target)" class="mr-1" variant="success">
          <b-icon-patch-plus></b-icon-patch-plus>
        </b-button>
        <b-button v-b-tooltip.hover title="Restar Productos" size="sm" @click="minusmodal(row.item, row.index, $event.target)" class="mr-1" variant="warning">
          <b-icon-patch-minus></b-icon-patch-minus>
        </b-button>
        <b-button v-b-tooltip.hover title="Eliminar" size="sm" @click="deletemodal(row.item, row.index, $event.target)" class="mr-1" variant="danger">
          <b-icon-trash></b-icon-trash>
        </b-button>
      </template>

    </b-table>

    <b-row>
      <b-col sm="7" md="6" class="my-1">
        <b-pagination
          v-model="currentPage"
          :total-rows="totalRows"
          :per-page="perPage"
          align="fill"
          size="sm"
          class="my-0"
        ></b-pagination>
      </b-col>
    </b-row>

    <!-- delete modal -->
    <b-modal :id="deleteModal.id" :title="deleteModal.title" @hide="resetDeleteModal" @ok="deleteOk(deleteModal.idDelete)">
      <template #default="{  }">
        <p>{{ deleteModal.content }}</p>
      </template>
      <template #modal-footer="{ ok, cancel }">
        <b-button size="sm" variant="primary" @click="ok(deleteModal.idDelete)">
            Borrar
        </b-button>
        <b-button size="sm" @click="cancel()">
            Cancelar
        </b-button>
      </template>
    </b-modal>

    <!-- plus modal -->
    <b-modal id="modalPlus" size="sm" :title="plusModal.title" @hide="resetPlusModal" @ok="plusOk(plusModal.idPlus)">
      <template #default="{  }">
        <b-container fluid>
          <b-form ref="form" @submit.stop.prevent="plusOk">
            <b-form-group
              label="Cantidad"
              label-for="cantidadPlus"
              :invalid-feedback="feedbackPlus"
              :state="plusState"
            >

              <b-form-input
                id="cantidadPlus"
                v-model="cantidadPlus"
                :state="plusState"
                required
                placeholder="Ej. 5"
                size="sm"
                pattern="^[0-9]+(.[0-9]+)?$"
              ></b-form-input>

            </b-form-group>

          </b-form>
        </b-container>
      </template>
      <template #modal-footer="{ ok, cancel }">
        <b-button size="sm" variant="primary" @click="ok(plusModal.idPlus)">
            Sumar
        </b-button>
        <b-button size="sm" @click="cancel()">
            Cancelar
        </b-button>
      </template>
    </b-modal>

    <!-- minus modal -->
    <b-modal id="modalMinus" size="sm" :title="minusModal.title" @hide="resetMinusModal" @ok="minusOk(minusModal.idMinus)">
      <template #default="{  }">
        <b-container fluid>
          <b-form ref="form" @submit.stop.prevent="minusOk">
            <b-form-group
              label="Cantidad"
              label-for="cantidadMinus"
              :invalid-feedback="feedbackMinus"
              :state="minusState"
            >

              <b-form-input
                id="cantidadMinus"
                v-model="cantidadMinus"
                :state="minusState"
                required
                placeholder="Ej. 5"
                size="sm"
                pattern="^[0-9]+(.[0-9]+)?$"
              ></b-form-input>

            </b-form-group>

          </b-form>
        </b-container>
      </template>
      <template #modal-footer="{ ok, cancel }">
        <b-button size="sm" variant="primary" @click="ok(minusModal.idMinus)">
            Restar
        </b-button>
        <b-button size="sm" @click="cancel()">
            Cancelar
        </b-button>
      </template>
    </b-modal>

  </b-container>
</template>

<script>
  export default {
    props: {
      inventario:{
        type: Array,
        required: true,
        default: () => []
      },
      invzero:{
        type: Array,
        required: true,
        default: () => []
      },
    },
    data() {
      return {
        checkZero: true,
        datatab: [],
        feedbackPlus: "La cantidad es necesaria",
        plusState: null,
        cantidadPlus: "",
        feedbackMinus: "La cantidad es necesaria",
        minusState: null,
        cantidadMinus: "",
        fields: [
          {
            key: 'product_name',
            label: 'PRODUCTO',
            sortable: true,
            thClass: 'text-center negrita',
            tdClass: 'small text-center',
          },
          {
            key: 'total_qty_prod',
            label: 'CANTIDAD',
            sortable: true,
            thClass: 'text-center negrita',
            tdClass: 'small text-center',
          },
          {
            key: 'updated_at',
            label: 'FECHA',
            sortable: true,
            thClass: 'text-center negrita',
            tdClass: 'small text-center',
          },
          {
            key: 'actions', label: 'Acciones'
          }
        ],
        totalRows: 1,
        currentPage: 1,
        perPage: 5,
        pageOptions: [10, 20, 30, { value: 100, text: "Mostrar Todo" }],
        sortBy: '',
        sortDesc: false,
        sortDirection: 'asc',
        filter: null,
        filterOn: [],
        deleteModal: {
          id: 'delete-modal',
          title: '',
          content: '',
          idDelete: ''
        },
        plusModal: {
          id: 'plus-modal',
          title: '',
          content: '',
          idPlus: ''
        },
        minusModal: {
          id: 'minus-modal',
          title: '',
          content: '',
          idMinus: ''
        },
      }
    },
    computed: {
      sortOptions() {
        // Create an options list from our fields
        return this.fields
          .filter(f => f.sortable)
          .map(f => {
            return { text: f.label, value: f.key }
          })
      }
    },
    mounted() {
      // Set the initial number of items
      this.totalRows = this.inventario.length;
      // Asignar array para data
      this.datatab = this.inventario;
    },
    methods: {
      deleteOk(id){
        axios.post('/inventory/'+id, {
          _method: 'delete'
        })
        .then(response=>{
            window.location = "/admin/inventariov";
        }).catch(e => {
          console.log(e.response)
        });
      },
      plusOk(id){
        var plus = parseFloat(this.cantidadPlus);
        axios.post('/sumar-inventory/'+id, {
          _method: 'put',
          cantidad: plus
        })
        .then(response=>{
            window.location = "/admin/inventariov";
        }).catch(e => {
          console.log(e.response)
        });
      },
      minusOk(id){
        var minus = parseFloat(this.cantidadMinus);
        axios.post('/restar-inventory/'+id, {
          _method: 'put',
          cantidad: minus
        })
        .then(response=>{
            window.location = "/admin/inventariov";
        }).catch(e => {
          console.log(e.response)
        });
      },
      deletemodal(item, index, button) {
        this.deleteModal.title = `Borrar: ${item.product_name}`
        this.deleteModal.idDelete = item.id
        this.deleteModal.content = `Esta seguro que desea borrar el producto: ${item.product_name}??`
        this.$root.$emit('bv::show::modal', this.deleteModal.id, button)
      },
      editmodal(item, index, button) {
        $('#editPRoduct').modal('show');

        $('#form_edit').attr('action', `/inventory/${item.id}`)

        $.get({
          url: `/inventory/${item.id}`,
          beforeSend(){
            $('#modal_loader').show()
          }
        })
        .done((response) => {
          console.log(response)

          $('#product_name_edit').val(response.product_name)

          $('#enterprise_edit').val(response.enterprise_id)
          $('#enterprise_edit').change()

          $('#category_edit').val(response.category_id)
          $('#category_edit').change()

          $('#cantidad_edit').val(response.quantity)

          $('#tipo_unidad_edit').val(response.unit_type)
          $('#tipo_unidad_edit').change()

          $('#presentacion_edit').val(response.unit_type_menor)
          $('#presentacion_edit').change()

          $('#cant_prod_edit').val(response.qty_per_unit)

          $('#description_edit').val(response.description)
          $('#stock_min_edit').val(response.stock_min)

          let cantidad  = $('#cantidad').val() || $('#cantidad_edit').val()
          let cant_prod = $('#cant_prod').val() || $('#cant_prod_edit').val()

          let productos_totales = cantidad * cant_prod

          $('#cantidad_producto_hd_edit').val(productos_totales)

          $('#modal_loader').fadeOut()
        })
        .fail((err) => {
          console.error(err)
          toastr.error('Algo a ocurrido.')
        })
      },
      plusmodal(item, index, button) {
        this.plusModal.title = `Sumar: ${item.product_name}`
        this.plusModal.idPlus = item.id
        this.$root.$emit('bv::show::modal', 'modalPlus', button)
      },
      minusmodal(item, index, button) {
        this.minusModal.title = `Restar: ${item.product_name}`
        this.minusModal.idMinus = item.id
        this.$root.$emit('bv::show::modal', 'modalMinus', button)
      },
      resetDeleteModal() {
        this.deleteModal.title = ''
        this.deleteModal.content = ''
        this.deleteModal.idDelete = ''
      },
      resetPlusModal() {
        this.plusModal.title = ''
        this.plusModal.content = ''
        this.plusModal.idPlus = ''
      },
      resetMinusModal() {
        this.minusModal.title = ''
        this.minusModal.content = ''
        this.minusModal.idMinus = ''
      },
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length
        this.currentPage = 1
      },
      changeData(checked) {
        if (checked) {
          this.datatab = this.inventario;
          console.log(this.datatab);      
        } else {
          this.datatab = this.invzero;
          console.log(this.datatab);      
        }
      }
    }
  }
</script>
