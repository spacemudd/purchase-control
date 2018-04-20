<template>
    <div>
        <div class="block">
            <div class="panel">
                <div class="panel-heading">{{ $t('words.stock-report') }}</div>
                <div class="panel-block">
                    <export-report table-name="assets"
                                   :button-name="'Build'"
                                   :columns="[
                                       {displayName: 'Code', columnName: 'asset_code'},
                                       {displayName: 'Description', columnName: 'description'},
                                       {displayName: 'Manufacturer', columnName: 'manufacturer'},
                                       {displayName: 'Manufacturer Serial Number', columnName: 'manufacturer_serial_number'},
                                       {displayName: 'System Tag Number', columnName: 'system_tag_number'},
                                       {displayName: 'Finance Tag Number', columnName: 'finance_tag_number'},
                                       {displayName: 'Unit Price', columnName: 'unit_price'},
                                       {displayName: 'Purchase Date', columnName: 'purchase_date'},
                                       {displayName: 'Category', columnName: 'category'},
                                       {displayName: 'Status', columnName: 'status'},
                                       {displayName: 'Warranty Expiry', columnName: 'warranty_expiry'},
                                       {displayName: 'Issued For', columnName: 'issued_for'},
                                       {displayName: 'Updated At', columnName: 'updated_at'},
                                    ]">
                    </export-report>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        mounted() {

        },
        methods: {

        }
    }
</script>
