<template>
        <a class="dropdown-item result" @click="choseLocation">
            <div class="information">
                <template v-if="hyperLinkedResults">
                    <a :href="this.baseUrl() + '/locations/' + location.id">
                        <table class="table is-bordered is-striped is-narrow is-fullwidth">
                            <tbody>
                            <tr>
                                <td>{{ $t('words.code') }}</td>
                                <td class="has-text-right">{{ location.code }}</td>
                            </tr>
                            <tr>
                                <td>{{ $t('words.department') }}</td>
                                <td class="has-text-right">{{ location.department.code + ' - ' + location.department.description }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </a>
                </template>
                <template v-else>
                    <table class="table is-bordered is-striped is-narrow is-fullwidth">
                        <tbody>
                        <tr>
                            <td>{{ $t('words.code') }}</td>
                            <td class="has-text-right">{{ location.code }}</td>
                        </tr>
                        <tr>
                            <td>{{ $t('words.department') }}</td>
                            <td class="has-text-right">{{ location.department.code + ' - ' + location.department.description }}</td>
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
            location: {
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
            choseLocation() {
                this.$emit('recordSelected', this.location);
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
