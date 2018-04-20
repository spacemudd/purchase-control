<template>
    <form>
        <div class="modal-card" style="height:400px;">
            <header class="modal-card-head">
                <p class="modal-card-title">{{ $t('words.build') }}</p>
            </header>
            <section class="modal-card-body">
                <loading-screen v-if="$isLoading('DOWNLOADING')" size="is-small"></loading-screen>
                <div class="columns" v-else>
                    <div class="column is-12">
                        <div class="field">
                            <label class="label">+ {{ $t('words.stock-code') }} <span class="has-text-danger">*</span></label>
                            <div class="control">
                                <simple-search
                                        :hyper-linked-results="false"
                                        :search-endpoint="'asset-templates'"
                                        size="is-small"
                                        @selectedRecord="addToStockList">
                                </simple-search>
                            </div>

                            <p class="help">{{ $t('messages.you-can-add-multiple-stock-the-list') }}</p>
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column is-12">
                        <div class="content">
                            <ul>
                                <li v-for="stock in stockList">
                                    {{ stock.code }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$parent.close()">Close</button>
                <button type="button"
                        class="button is-success"
                        :class="{'disabled is-loading': isLoadingExcel }"
                        :disable="isLoadingExcel"
                        @click="initiateBuildJob()">
                    Build (Excel)
                </button>
            </footer>
        </div>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                stockList: [],
                isLoadingExcel: false,
            }
        },
        methods: {
            addToStockList(stock) {
                this.stockList.push(stock);
            },
            initiateBuildJob() {
                axios.post(this.apiUrl() + '/reports/asset-stock/by-item', {
                    stock_list: this.stockList,
                }).then(response => {
                    this.$dialog.alert({
                        title: 'In Progress',
                        message: 'This process might take time. You will receive an <b>inbox message</b> when it is finished.',
                    });
                    this.isLoadingExcel = false;
                }).catch(error => {
                    this.$dialog.alert({
                        type: 'is-danger',
                        hasIcon: true,
                        icon: 'times-circle',
                        iconPack: 'fa',
                        message: 'Error occurred while dispatching your job.',
                    });
                    this.isLoadingExcel = false;
                })
            }
        }
    }
</script>
