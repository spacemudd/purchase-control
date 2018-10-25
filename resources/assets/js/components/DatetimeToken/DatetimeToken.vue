<template>
    <div>
        <div :class="{'transparent-highlighter-bg': highlighted}" @click="editToken">
            <span v-if="!date"><i>[{{ placeholder }}]</i></span>
            {{ date }}
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
             * An HTTP PUT method URL to save/update the token.
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
            displayName: {
              type: String,
              default: '',
            },
          canEdit: {
            type: Boolean,
            default: false,
          }
        },
        data() {
            return {
                is_editing: false,

                date: null,
            }
        },
        mounted() {
            this.date = this.value;
        },
        methods: {
            editToken() {
              if(this.canEdit) {
                this.is_editing = true;
                this.$dialog.prompt({
                  message: `What's the purchase order date?`,
                  inputAttrs: {
                    type: 'date',
                    placeholder: 'Choose the purchase date',
                  },
                  onConfirm: (value) => this.saveToken(value),
                })
              } else {
                this.$toast.open({
                  type: 'is-warning',
                  message: 'PO cant be edited after saving',
                })
              }
            },
            saveToken(date) {
                axios.put(this.apiUrl() + '/purchase-orders/' + this.id + '/tokens', {
                    'name': this.name,
                    'value': date,
                })
                    .then(response => {
                        this.$toast.open({
                            type: 'is-success',
                            message: 'Saved',
                        });

                        this.date = response.data[this.displayName];
                    })
                    .catch(error => {
                        alert(error.response.data);
                    })
            },
        }
    }
</script>
