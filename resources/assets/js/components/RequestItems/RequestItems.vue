<!--
  - Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
  -
  - Unauthorized copying of this file via any medium is strictly prohibited.
  - This file is a proprietary of Clarastars LLC and is confidential.
  -
  - https://clarastars.com - info@clarastars.com
  -
  -->

<template>
    <div v-if="request">
        <!-- Modals -->
        <b-modal :active.sync="showCreateModal">
            <request-item-new-modal></request-item-new-modal>
        </b-modal>

        <div class="columns">
            <div class="column has-text-right">
                <button v-if="request.status_name === 'draft'"
                        class="button is-small is-primary"
                        @click="showCreateModal=true">Add item</button>
            </div>
        </div>
        <b-table :data="requestItems" class="is-size-7">
            <template slot-scope="props">
                <b-table-column label="Name">
                    <span v-if="props.row.item">{{ props.row.item.name }}</span>
                </b-table-column>
                <b-table-column label="Unit price" numeric>
                    {{ props.row.unit_price }}
                </b-table-column>
                <b-table-column label="Quantity" numeric>
                    {{ props.row.qty }}
                </b-table-column>
                <b-table-column label="Total" numeric>
                    {{ props.row.total }}
                </b-table-column>
                <b-table-column label="Actions" numeric>
                    <button v-if="request.status_name === 'draft'"
                            class="button is-small is-danger has-icon"
                            @click="confirmDeletingItem(props.row.id)">
                        <span class="icon"><i class="fa fa-trash"></i></span>
                        <span>Delete</span>
                    </button>
                </b-table-column>
            </template>
            <template slot="empty">
                <div class="section">
                    <div class="content has-text-grey has-text-centered">
                        <p>No items.</p>
                        <button class="button is-small is-primary" @click="showCreateModal=true">Add item</button>
                    </div>
                </div>
            </template>
        </b-table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                showCreateModal: false,
                one: '',
                two: '',
            }
        },
        mounted() {
            //
        },
        computed: {
            request() {
                return this.$store.getters['PurchaseControlRequest/request'];
            },
            requestItems() {
                return this.$store.getters['PurchaseControlRequest/requestItems'];
            },
        },
        methods: {
            confirmDeletingItem(itemId) {
                this.$dialog.confirm({
                    title: 'Deleting item',
                    message: 'Are you sure?',
                    onConfirm: () => this.deleteItem(itemId),
                })
            },
            deleteItem(itemId) {
                axios.delete(this.apiUrl() + `/requests/${this.request.id}/items/${itemId}`)
                    .then(response => {
                        window.location.reload();
                    })
                    .catch(error => {
                        alert(error.response.data.message);
                    })
            },
        }
    }
</script>
