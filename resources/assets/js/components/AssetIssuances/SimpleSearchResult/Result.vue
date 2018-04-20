<template>
    <div>
        <a v-for="issuance in assetIssuances"
           :href="issuance.hyperlink"
           class="dropdown-item result">

            <div class="level">
                <div class="right">
                    <span class="tag"><strong>{{ issuance.ref_id }}</strong></span>
                </div>
                <div class="left">
                    <span class="tag is-small is-uppercase" :class="tagClass(issuance.status)">
                    {{ issuance.status }}
                </span>
                </div>
            </div>

            <div class="information">
                <table class="table is-bordered is-striped is-narrow is-fullwidth">
                    <tbody>
                        <tr>
                            <td>{{ $t('words.date') }}</td>
                            <td class="has-text-right">{{ issuance.issuance_date_human }}</td>
                        </tr>
                        <tr>
                            <td>{{ $t('words.employee') }}</td>
                            <td class="has-text-right">{{ issuance.employee.name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </a>
    </div>
</template>

<script>
    export default {
        props: {
            assetIssuances: {
                required: false,
                default: () => []
            }
        },
        mounted() {

        },
        methods: {
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
