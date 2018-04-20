<template>
    <div>
        <div class="block">
            <div class="panel">
                <div class="panel-heading">{{ $t('words.asset-issuances') }}</div>
                <div class="panel-block">
                    <export-report table-name="asset_issuances"
                                   :button-name="'Build'"
                                   :columns="[
                                       {displayName: 'Issuance Number', columnName: 'issuance_id'},
									   {displayName: 'Employee', columnName: 'employee'},
									   {displayName: 'Department', columnName: 'department'},
									   {displayName: 'Issuance Date', columnName: 'issuance_date'}
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
