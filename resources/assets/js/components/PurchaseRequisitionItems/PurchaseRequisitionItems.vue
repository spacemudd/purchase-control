<template>
    <div>
        <!-- New requisition item modal -->
        <b-modal :active.sync="newItemModal">
            <new-item-requisition-modal :requisition-id="requisitionId" @saved="getItems"></new-item-requisition-modal>
        </b-modal>

        <div class="columns">
            <div class="column"><p class="title is-4">{{ $t('words.requisition-items') }}</p></div>
            <div class="column has-text-right">
                <button class="button has-icon" v-if="this.getPermissions()['create-purchase-requisition-item']" @click="newItemModal=true">
                    <span class="icon"><i class="fa fa-plus"></i></span>
                    <span>New Item</span>
                </button>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <table class="table is-fullwidth is-bordered is-size-7">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th class="has-text-right">Quantity</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, key) in items">
                        <td>{{ ++key }}</td>
                        <td>{{ item.code }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.qty }}</td>
                    </tr>
                    <tr class="has-text-centered" v-if="items.length === 0">
                        <td colspan="5"><p class="has-text-centered"><i>No items</i></p></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            requisitionId: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                newItemModal: false,

                items: [],
            }
        },
        mounted() {
            this.getItems();
        },
        methods: {
            getItems() {
                axios.get(this.apiUrl() + `/purchase-requisitions/${this.requisitionId}/items`)
                    .then(response => {
                        this.items = response.data;
                    })
            },
        }
    }
</script>
