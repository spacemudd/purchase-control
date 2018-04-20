<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <h1 class="title is-3 has-text-success has-text-centered">{{ $t('words.success') }}</h1>
            <table class="table is-fullwidth is-hoverable">
                <thead><tr><td style="width: 50%;"></td><td></td></tr></thead>
                <tbody>
                    <tr>
                        <td>{{ $t('words.issuance-number') }}</td>
                        <td>{{ issuance.ref_id }}</td>
                    </tr>
                    <tr>
                        <td>{{ $t('words.issuance-date') }}</td>
                        <td>{{ issuance.issuance_date_human }}</td>
                    </tr>
                    <tr>
                        <td>{{ $t('words.department') }}</td>
                        <td>{{ department }}</td>
                    </tr>
                    <tr>
                        <td>{{ $t('words.employee') }}</td>
                        <td>{{ employee }}</td>
                    </tr>
                    <tr>
                        <td>{{ $t('words.system-generated-date') }}</td>
                        <td>{{ issuance.created_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="column is-12 has-text-centered">
            <div class="field is-grouped is-grouped-centered">
                <p class="control">
                    <a :href="this.baseUrl() + '/procedures/asset-issuances/' + issuance.id" class="button is-primary">
                        <strong>{{ issuance.ref_id }}</strong>
                    </a>
                </p>
                <p class="control">
                    <a class="button is-light" @click.prevent="$emit('close')">
                        {{ $t('words.cancel') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            issuance: {
                required: true,
            }
        },
        computed: {
            department() {
                let department = this.$store.getters['AssetIssuance/department'];

                return department.code + ' - ' + department.description;
            },
            employee() {
                let employee = this.$store.getters['AssetIssuance/employee'];

                return employee.code + ' - ' + employee.name;
            }
        },
    }
</script>
