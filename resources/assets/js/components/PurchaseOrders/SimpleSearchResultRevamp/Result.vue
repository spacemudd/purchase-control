<template>
        <a class="dropdown-item result" @click="chosePurchaseOrder">
            <div class="information">
                <div class="level">
                    <div class="right">
                        <span class="tag"><strong>{{ purchaseOrder.order_number }}</strong></span>
                    </div>
                    <div class="left">
                    <span class="tag is-small" :class="tagClass(purchaseOrder.status)">
                    {{ purchaseOrder.status_human }}
                </span>
                    </div>
                </div>

                <template v-if="hyperLinkedResults">
                    <a :href="this.baseUrl() + '/procedures/purchase-orders/' + purchaseOrder.id">
                        <table class="table is-bordered is-striped is-narrow is-fullwidth">
                            <tbody>
                            <tr>
                                <td>{{ $t('words.main-order-number') }}</td>
                                <td class="has-text-right">{{ purchaseOrder.main_order_number }}</td>
                            </tr>
                            <tr>
                                <td>{{ $t('words.date') }}</td>
                                <td class="has-text-right">{{ purchaseOrder.date_human }}</td>
                            </tr>
                            <tr>
                                <td>{{ $t('words.delivery-date') }}</td>
                                <td class="has-text-right">{{ purchaseOrder.delivery_date_human }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </a>
                </template>
                <template v-else>
                    <table class="table is-bordered is-striped is-narrow is-fullwidth">
                        <tbody>
                        <tr>
                            <td>{{ $t('words.main-order-number') }}</td>
                            <td class="has-text-right">{{ purchaseOrder.main_order_number }}</td>
                        </tr>
                        <tr>
                            <td>{{ $t('words.date') }}</td>
                            <td class="has-text-right">{{ purchaseOrder.date_human }}</td>
                        </tr>
                        <tr>
                            <td>{{ $t('words.delivery-date') }}</td>
                            <td class="has-text-right">{{ purchaseOrder.delivery_date_human }}</td>
                        </tr>
                        </tbody>
                    </table>
                </template>

            </div>
        </a>
</template>

<script>
    export default {
        props: {
            purchaseOrder: {
                required: true,
            },
            hyperLinkedResults: {
                type: Boolean,
                default: true
            }
        },
        mounted() {

        },
        methods: {
            chosePurchaseOrder() {
                this.$emit('chosePurchaseOrder', this.purchaseOrder);
            },
            tagClass(status) {
                if(status === 'draft') {
                    return 'is-warning';
                }

                if(status === 'committed') {
                    return 'is-success';
                }

                if(status === 'void') {
                    return 'is-danger';
                }
            }
        }
    }
</script>

<style lang="sass">
    .tag:not(body)
        font-size: 12px

        .dropdown-item
            border-bottom: 1px solid #eeeaea

        a.dropdown-item
            padding-right: 10px
</style>
