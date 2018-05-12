<template>
    <form @submit.prevent="save">
        <div class="modal-card" style="width: auto;min-height:600px;">
            <header class="modal-card-head">
                <p class="modal-card-title">Choose Supplier</p>
            </header>
            <section class="modal-card-body">

                <!-- Form Errors -->
                <div class="notification is-danger" v-if="form.errors.length > 0">
                    <strong>An error occurred.</strong>
                    <br>
                    <ul>
                        <li v-for="error in form.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <div class="field">
                    <label class="label">Supplier <span class="has-text-danger">*</span></label>

                    <div class="control">
                        <b-input v-if="form.vendor" :value="form.vendor.id + ' - ' + form.vendor.name" @click="form.vendor=null">
                        </b-input>
                        <select-vendors v-else
                                              :url="this.apiUrl() + '/search/vendors'"
                                              @selected="selectedVendor"
                        >
                        </select-vendors>
                    </div>
                </div>
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
            url: {
                type: String,
                required: true,
            },
            /**
             * The name of the field to be PUT'd into the HTTP server.
             */
            name: {
                type: String,
                required: true,
            },
        },
        data() {
            return {

                form: {
                    vendor: null,
                    errors: [],
                },
            }
        },
        mounted() {
            //
        },
        methods: {
            selectedVendor(vendor) {
                this.form.vendor = vendor;
            },
            save() {
                this.form.errors = [];

                axios.put(this.url, {
                    'name': this.name,
                    'value': this.form.vendor.id,
                })
                    .then(response => {
                        this.form.name = '';
                        this.form.scopes = [];
                        this.form.errors = [];

                        this.$emit('saved', response.data);
                        // this.$store.dispatch('PurchaseOrder/getPo', response.data.id);

                        // this.$parent.close();

                        window.location.reload();
                    })
                    .catch(response => {
                        if (typeof response.data === 'object') {
                            this.form.errors = _.flatten(_.toArray(response.data));
                        } else {
                            this.form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },
        }
    }
</script>
