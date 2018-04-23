<template>
    <span>
        <b-modal :active.sync="showAttachManufacturerModal">
            <attach-manufacturer-to-vendor-modal v-if="vendor"
                                                 :vendor-id.number="vendorId"
                                                 :vendor="vendor"
                                                 @close="getVendor"
            ></attach-manufacturer-to-vendor-modal>
        </b-modal>
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    {{ $t('words.manufacturers') }}
                    <b-tooltip label="What the vendor can provide">
                        <span class="has-icon has-text-grey is-small title-text-icon"><i class="fa fa-question-circle-o"></i></span>
                    </b-tooltip>
                </p>
                <a href="#" class="card-header-icon" @click="showAttachManufacturerModal=true">
                    <span class="icon">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </span>
                </a>
            </header>
            <div class="card-content">
                <span v-if="vendor">
                    <b-table :data="vendor.manufacturers"
                             class="is-size-7"
                             detail-key="id">
                        <template scope="props">
                            <b-table-column field="name" label="Name">
                                {{ props.row.name }}
                            </b-table-column>
                        </template>
                        <template slot="empty">
                            <section class="section">
                                <div class="content has-text-grey has-text-centered">
                                    <p><i>No manufacturers are associated</i></p>
                                </div>
                            </section>
                        </template>
                    </b-table>
                </span>
            </div>
        </div>
    </span>
</template>

<script>
    import AttachManufacturerToVendorModal from './AttachManufacturerToVendorModal';
    export default {
        components: {
            AttachManufacturerToVendorModal,
        },
        props: {
            vendorId: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                showAttachManufacturerModal: false,
                vendor: null,
            }
        },
        mounted() {
            this.getVendor();
        },
        methods: {
            getVendor() {
                axios.get(this.apiUrl() + '/vendors/' + this.vendorId).then(response => {
                    this.vendor = response.data;
                }).catch(error => {
                    alert(error.response.data.message);
                });
            }
        }
    }
</script>
