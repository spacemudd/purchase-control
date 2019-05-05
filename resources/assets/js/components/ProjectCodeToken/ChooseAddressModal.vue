<template>
    <form @submit.prevent="chooseAddress">
        <div class="modal-card" style="width: auto; height:400px;">
            <header class="modal-card-head">
                <p class="modal-card-title">Choose address</p>
            </header>
            <section class="modal-card-body">
                <input v-if="selectedAddress"
                       class="input"
                       :value="selectedAddress.location"
                       @click="selectedAddress=null"
                       readonly>
                <select-address v-else
                                name="shipping_address_id"
                                :url="searchUrl"
                                @selected="addressIsSelected"
                >
                </select-address>
            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$parent.close()">Close</button>
                <button class="button is-primary">Save</button>
            </footer>
        </div>
    </form>
</template>

<script>
    export default {
      props: {
        searchUrl: {
          type: String,
          required: true,
        },
      },
        data() {
            return {
                selectedAddress: null,
            }
        },
        mounted() {
            //
        },
        methods: {
            chooseAddress() {
              this.$emit('save', this.selectedAddress)
            },
          addressIsSelected(address) {
              this.selectedAddress = address;
          },
        }
    }
</script>
