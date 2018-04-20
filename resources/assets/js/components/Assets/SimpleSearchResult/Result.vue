<template>
        <a class="dropdown-item result" @click="choseAsset">
            <div class="information">
                <table class="table is-bordered is-striped is-narrow is-fullwidth">
                    <tbody>
                    <tr>
                        <td>{{ $t('words.code') }}</td>
                        <td class="has-text-right">{{ asset.asset_template.code }}</td>
                    </tr>
                    <tr>
                        <td>{{ $t('words.manufacturer-serial-number') }}</td>
                        <td class="has-text-right">{{ asset.manufacturer_serial_number }}</td>
                    </tr>
                    <tr>
                        <td>{{ $t('words.unit-price') }}</td>
                        <td class="has-text-right">{{ asset.unit_price }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </a>
</template>

<script>
    export default {
        props: {
            asset: {
                required: true,
            }
        },
        mounted() {

        },
        methods: {
            choseAsset() {
                this.$emit('choseAsset', this.asset);
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
