<template>
    <div v-if="$isLoading('GETTING_Q_SUPPLIERS')">
        <loading-screen size='is-large'></loading-screen>
    </div>
    <div v-else id="q-suppliers">
        <div class="columns">
            <!-- Selecting supplier to manage -->
            <div v-for="supplier in suppliersAvailable" class="column is-3">
                <!-- Suppliers -->
                <div class="box q-supplier pointer"
                     :class="{'selected':supplier.id===selectedSupplier.id}"
                     @click="selectSupplier(supplier)">
                    {{ supplier.name }}
                </div>
            </div>
        </div>

        <!-- Managing POs -->
        <div class="columns">
            <div class="column">
                <button class="button is-small is-warning is-inline" :disabled="checkedRows.length===0">Create PO</button>
                <span style="margin-left:20px;font-size:14px;">Selected amount: <b>{{ Number(checkedRowsTotalAmount).toFixed(2) }}</b></span>
            </div>
        </div>

        <!-- Showing quotations -->
        <div class="columns" style="margin-bottom:0">
            <div class="column is-6" style="font-size:14px;">
                <b>Cost Centers</b>
            </div>
            <div class="column is-6 has-text-right" style="font-size:14px;">
                Credit: <b>0</b>
            </div>
        </div>

        <!-- Selecting quotations -->
        <div style="padding:1rem 0;background-color:#e6e6e6;" v-for="(quotations, cost_center) in quotations">
            <p style="margin-left:0.5rem;font-size:10px;margin-bottom:5px;"><b>{{ cost_center }}</b></p>
            <b-table
                    class="is-size-7"
                    :data="quotations"
                    :columns="quotationColumns"
                    :is-row-checkable="(row) => row.id !== 3"
                    :checked-rows.sync="checkedRows"
                    checkable
                    narrowed>
            </b-table>
        </div>

    </div>
</template>

<script>
  export default {
    data() {
      return {
        quotations: [],
        suppliersAvailable: [],
        selectedSupplier: null,
        checkedRows: [],
        quotationColumns: [
          {
            field: 'id',
            label: 'ID',
            width: '10',
          },
          {
            field: 'vendor_quotation_number',
            label: 'Quote #',
            width: '100',
          },
          {
            field: 'total_price_inc_vat',
            label: 'Total Cost',
            numeric: true,
          },
        ]
      }
    },
    mounted() {
      this.getSuppliers();
    },
    computed: {
      checkedRowsTotalAmount() {
        var formatter = new Intl.NumberFormat('en-SA', {
          style: 'currency',
          currency: 'SAR',
        });

        // total starts at 0
        return this.checkedRows.reduce((total, checkedRow) => {
          console.log(checkedRow);

          return total + Number(checkedRow.total_price_inc_vat);
        }, 0);
      }
    },
    methods: {
      getSuppliers() {
        this.$startLoading('GETTING_Q_SUPPLIERS');
        axios.get(this.apiUrl() + '/q-suppliers')
          .then(response => {
            this.suppliersAvailable = response.data;
            this.selectSupplier(this.suppliersAvailable[0]);
            this.$endLoading('GETTING_Q_SUPPLIERS');
          })
      },
      selectSupplier(supplier) {
        this.selectedSupplier = supplier;
        this.quotations = [];
        this.checkedRows = [];
        this.$startLoading('GETTING_Q_SUPPLIER_QUOTES');
        axios.get(this.apiUrl()+'/q-suppliers/'+supplier.id+'/quotations')
          .then(response => {
            this.quotations = response.data;
          })
          .catch(error => {
            throw error;
          })
          .finally(() => {
            this.$endLoading('GETTING_Q_SUPPLIER_QUOTES');
          })
      }
    }
  }
</script>

<style lang="sass">
    .q-supplier
        opacity: 0.5
    .q-supplier.selected
            opacity: 1
</style>
