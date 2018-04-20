<template>
    <table class="table is-fullwidth is-narrow is-size-7 has-text-small">
        <thead>
            <tr>
                <th style="width:150px">Issuance Number</th>
                <th>Department</th>
                <th>Employee</th>
                <th>Date</th>
                <th style="width:100px;">Issued For</th>
            </tr>
        </thead>
        <tbody>
        <tr v-for="issuanceItem in data">
            <td><a :href="issuanceItem.issuance.link">{{ issuanceItem.issuance.ref_id }}</a></td>
            <td>{{ issuanceItem.issuance.department.code }} - {{ issuanceItem.issuance.department.description }}</td>
            <td>
                <a :href="issuanceItem.issuance.employee.link">
                    {{ issuanceItem.issuance.employee.code }} - {{ issuanceItem.issuance.employee.name }}
                </a>
            </td>
            <td>{{ issuanceItem.issuance.issuance_date_human }}</td>
            <td>{{ issuanceItem.issued_for.name }}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        data() {
            return {
                data: [],
            }
        },
        computed: {
            asset() {
                return this.$store.getters['Asset/asset'];
            }
        },
        mounted() {
            this.loadIssuances();
        },
        methods: {
            loadIssuances() {
                axios.post(this.apiUrl() + '/assets/issuances', {
                    asset_id: this.asset.id,
                }).then(response => {
                    this.data = response.data;
                })
            },
        }
    }
</script>
