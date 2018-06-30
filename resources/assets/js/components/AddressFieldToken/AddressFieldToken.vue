<template>
    <div>
        <b-modal v-if="canEdit" :active.sync="showModal">
            <choose-address-modal :search-url="searchUrl" @save="saveToken"></choose-address-modal>
        </b-modal>
        <div :class="{'transparent-highlighter-bg': highlighted}" @click="editToken">
            <span v-if="!address_json"><i>[{{ placeholder }}]</i></span>
            <span v-if="address_json">{{ address_json.location }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            id: {
                type: Number,
                required: true,
            },
            highlighted: {
                type: Boolean,
                default: false,
            },
            /**
             * An HTTP PUT method URL to save/upaddress_json the token.
             */
            url: {
                type: String,
                required: true,
            },
            /**
             * The name of the token/field/column-name of the resource.
             */
            name: {
                type: String,
                required: true,
            },
            /**
             * The value.
             */
            value: {
                type: String,
                required: false,
            },
            placeholder: {
                type: String,
                default: 'TOKEN',
            },
          searchUrl: {
              type: String,
          },
          isBilling: {
            type: Boolean,
            default: false,
          },
          canEdit: {
            type: Boolean,
            default: false,
          },
        },
        data() {
            return {
                is_editing: false,
                showModal: false,

                address_json: null,
            }
        },
        mounted() {
            this.address = this.value;
        },
        methods: {
            editToken() {
              if(this.canEdit) {
                this.is_editing = true;
                this.showModal = true;
              }
            },
            saveToken(address) {
              if(this.canEdit) {
                axios.put(this.apiUrl() + '/purchase-orders/' + this.id + '/tokens', {
                  'name': this.name,
                  'value': address.id,
                })
                  .then(response => {
                    this.$toast.open({
                      type: 'is-success',
                      message: 'Saved',
                    });

                    this.showModal = false;

                    if (this.isBilling) {
                      this.address_json = response.data.billing_address_json;
                    } else {
                      this.address_json = response.data.shipping_address_json;
                    }

                  })
                  .catch(error => {
                    alert(error.response.data);
                  })
              }
            },
        }
    }
</script>
