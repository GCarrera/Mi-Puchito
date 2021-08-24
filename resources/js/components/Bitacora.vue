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

      <b-col sm="10" lg="9" class="my-1">
        <b-form-group
          label="Buscar"
          label-for="filter-input"
          label-cols-sm="7"
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

    </b-row>

    <!-- Main table element -->
    <b-table
      :items="logs"
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

  </b-container>
</template>

<script>
  export default {
    props: {
      logs:{
        type: Array,
        required: true,
        default: () => []
      },
    },
    data() {
      return {
        fields: [
          {
            key: 'accion',
            label: 'Evento',
            sortable: true,
            thClass: 'text-center negrita',
            tdClass: 'small text-center',
          },
          {
            key: 'inventories',
            label: 'Producto',
            sortable: true,
            thClass: 'text-center negrita',
            tdClass: 'small text-center',
          },
          {
            key: 'usuario',
            label: 'Usuario',
            sortable: true,
            thClass: 'text-center negrita',
            tdClass: 'small text-center',
          },
          {
            key: 'created_at',
            label: 'Fecha',
            sortable: true,
            thClass: 'text-center negrita',
            tdClass: 'small text-center',
          }
        ],
        totalRows: 1,
        currentPage: 1,
        perPage: 5,
        pageOptions: [5, 10, 15, { value: 100, text: "Mostrar Todo" }],
        sortBy: '',
        sortDesc: false,
        sortDirection: 'asc',
        filter: null,
        filterOn: [],
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
      this.totalRows = this.logs.length;
    },
    methods: {      
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length
        this.currentPage = 1
      }
    }
  }
</script>
