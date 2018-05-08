<template>
    <form @submit.prevent="save">
        <div class="modal-card" style="width: auto;height:500px">
            <header class="modal-card-head">
                <p class="modal-card-title">Attach to PO</p>
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

                <b-field label="Purchase Order" message="Search by PO number">
                    <input v-if="selectedPurchaseOrder"
                           class="input"
                           :value="selectedPurchaseOrder.number"
                           @click="selectedPurchaseOrder=null"
                           readonly>
                    <select-purchase-orders v-else
                                     :url="apiUrl() + '/search/purchase-orders'"
                                     @selected="purchaseOrderIsSelected"
                    ></select-purchase-orders>
                </b-field>

            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$parent.close()">Close</button>
                <!--<button class="button is-success" :class="{'is-loading': $isLoading('APPROVING_REQUISITION')}">Approve</button>-->
            </footer>
        </div>
    </form>
</template>

<script>
    export default {
        props: {
            /**
             * Where the endpoint request is saved.
             */
            url: {
                type: String,
                required: true,
            },
            /**
             * The URL at which you can search for the results and returns json objects.
             */
            searchUrl: {
                type: String,
                required: true,
            },
        },
        data() {
            return {
                selectedPurchaseOrder: null,
                form: {
                    errors: [],
                }
            }
        },
        mounted() {

        },
        methods: {
            purchaseOrderIsSelected(purchaseOrder) {
                this.selectedPurchaseOrder = purchaseOrder;
            },
            save() {
                // this.$startLoading('APPROVING_REQUISITION');
                // this.form.errors = [];
                //
                // axios.post(this.url, this.form)
                //     .then(response => {
                //         this.form.errors = [];
                //         window.location.reload();
                //     })
                //     .catch(error => {
                //         this.$endLoading('APPROVING_REQUISITION')
                //
                //         if (typeof error.response.data === 'object') {
                //             this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                //         } else {
                //             this.form.errors = ['Something went wrong. Please try again.'];
                //         }
                //
                //         throw error;
                //     });
            }
        }
    }
</script>
