<template>
        <div class="dropdown-item result" @click="choseAssetTemplate" :class="{'cursor': hyperLinked}">
            <template v-if="hyperLinked">
                <a :href="this.baseUrl() + '/settings/asset-templates/' + assetTemplate.id">
                    <div class="information">
                        <table class="table is-bordered is-striped is-narrow is-fullwidth">
                            <tbody>
                                <tr>
                                    <td>{{ $t('words.code') }}</td>
                                    <td class="has-text-right">{{ assetTemplate.code }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $t('words.description') }}</td>
                                    <td class="has-text-right">{{ assetTemplate.description }}</td>
                                </tr>
                                <tr>
                                    <td>{{ $t('words.unit-price') }}</td>
                                    <td class="has-text-right">{{ assetTemplate.unit_price }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </a>
            </template>
            <template v-else>
                <div class="information">
                    <table class="table is-bordered is-striped is-narrow is-fullwidth">
                        <tbody>
                        <tr>
                            <td>{{ $t('words.code') }}</td>
                            <td class="has-text-right">{{ assetTemplate.code }}</td>
                        </tr>
                        <tr>
                            <td>{{ $t('words.description') }}</td>
                            <td class="has-text-right">{{ assetTemplate.description }}</td>
                        </tr>
                        <tr>
                            <td>{{ $t('words.unit-price') }}</td>
                            <td class="has-text-right">{{ assetTemplate.unit_price }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </div>
</template>

<script>
    export default {
        props: {
            assetTemplate: {
                required: true,
            },
            hyperLinked: {
                required: false,
                default: false,
            }
        },
        mounted() {

        },
        methods: {
            choseAssetTemplate() {
                this.$emit('choseAssetTemplate', this.assetTemplate);
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

    .cursor
        cursor: pointer
</style>
