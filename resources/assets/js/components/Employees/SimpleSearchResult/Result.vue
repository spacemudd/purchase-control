<template>
        <a class="dropdown-item result" @click="choseEmployee">
            <div class="information">
                <template v-if="hyperLinkedResults">
                    <a :href="this.baseUrl() + '/employees/' + employee.id">
                        <table class="table is-bordered is-striped is-narrow is-fullwidth">
                            <tbody>
                            <tr>
                                <td>{{ $t('words.code') }}</td>
                                <td class="has-text-right">{{ employee.code }}</td>
                            </tr>
                            <tr>
                                <td>{{ $t('words.name') }}</td>
                                <td class="has-text-right">{{ employee.name }}</td>
                            </tr>
                            <tr>
                                <td>{{ $t('words.contact-number') }}</td>
                                <td class="has-text-right">{{ employee.contact_number }}</td>
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
                            <td class="has-text-right">{{ employee.code }}</td>
                        </tr>
                        <tr>
                            <td>{{ $t('words.name') }}</td>
                            <td class="has-text-right">{{ employee.name }}</td>
                        </tr>
                        <tr>
                            <td>{{ $t('words.contact-number') }}</td>
                            <td class="has-text-right">{{ employee.contact_number }}</td>
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
            employee: {
                required: true,
            },
            hyperLinkedResults: {
                type: Boolean,
                default: true
            }
        },
        methods: {
            choseEmployee() {
                this.$emit('choseEmployee', this.employee);
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
