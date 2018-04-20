<template>
    <div>
        <b-table :data="audits" class="is-size-7" detailed>
            <template slot-scope="props">
                <b-table-column label="Event" class="is-capitalized">{{ props.row.event }}</b-table-column>
                <b-table-column label="Time">{{ props.row.created_at }}</b-table-column>
                <b-table-column label="Action by">
                    <span v-if="props.row.user">{{ props.row.user.username }} - {{ props.row.user.name }}</span>
                </b-table-column>
            </template>
            <template slot="detail" slot-scope="props">
                <div class="columns">
                    <div class="column is-6">
                        <p><b>Old values</b></p>
                        <table class="is-size-7" style="width:100%;">
                        	<tbody>
                                <tr v-for="(value, key) in props.row.old_values">
                                    <td>{{ key }}</td>
                                    <td>{{ value }}</td>
                                </tr>
                        	</tbody>
                        </table>
                    </div>
                    <div class="column is-6">
                        <p><b>New values</b></p>
                        <table class="is-size-7" style="width:100%;">
                            <tbody>
                            <tr v-for="(value, key) in props.row.new_values">
                                <td>{{ key }}</td>
                                <td>{{ value }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
        </b-table>
    </div>
</template>

<script>
    export default {
        props: {
            resourceType: {
                type: String,
                required: true,
            },
            resourceId: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                audits: [],
            }
        },
        mounted() {
            this.getLogs();
        },
        methods: {
            getLogs() {
                axios.post(this.apiUrl() + '/audit/show', {
                    resource_type: this.resourceType,
                    resource_id: this.resourceId,
                }).then(response => {
                    this.audits = response.data;
                }).catch(error => {
                    alert(error.response.data.message);
                })
            },
        }
    }
</script>
