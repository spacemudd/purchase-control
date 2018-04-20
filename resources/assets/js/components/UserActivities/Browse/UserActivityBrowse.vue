<template>

    <div class="loading-mask" v-if="isLoading">
        <loading-screen size="is-small"></loading-screen>
    </div>
    <div v-else>
        <table class="table is-bordered is-striped is-narrow is-fullwidth is-size-7">
            <thead>
            <tr>
                <th>{{ $t('words.employee-code') }}</th>
                <th>{{ $t('words.user') }}</th>
                <th class="has-text-centered">{{ $t('words.target-id') }}</th>
                <th>{{ $t('words.target-entity') }}</th>
                <th>{{ $t('words.action-type') }}</th>
                <th class="has-text-right">{{ $t('words.time') }}</th>
                <th class="has-text-right">{{ $t('words.actions') }}</th>
            </tr>
            </thead>
            <tbody>
                <template v-for="(activity, index) in results.data">
                    <tr>
                        <td>{{ activity.user.code }}</td>
                        <td>{{ activity.user.full_name }}</td>
                        <td class="has-text-centered">{{ activity.auditable_id }}</td>
                        <td>{{ activity.auditable_type.slice(11) }}</td>
                        <td>{{ activity.event }}</td>
                        <td class="has-text-right">{{ activity.created_at }}</td>
                        <td class="has-text-right">
                            <button class="button is-small is-primary" @click="openActivity(activity, index)">
                                <span class="icon is-small">
                                    <i class="fa fa-eye"></i>
                                </span>
                                <span>View</span>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="activity.expanded">
                        <td colspan="7">
                            <div class="columns">
                                <div class="column is-6">
                                    <div class="box">
                                        <p>{{ $t('words.old-data') }}</p>
                                        <table class="table is-bordered is-striped is-narrow is-fullwidth is-size-7">
                                            <tbody>
                                            <tr v-for="(value, key) in activity.old_values">
                                                <td><strong>{{ key }}</strong></td>
                                                <td>{{ value }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="column is-6">
                                    <div class="box">
                                        <p>{{ $t('words.new-data') }}</p>
                                        <table class="table is-bordered is-striped is-narrow is-fullwidth is-size-7">
                                            <tbody>
                                            <tr v-for="(value, key) in activity.new_values">
                                                <td><strong>{{ key }}</strong></td>
                                                <td>{{ value }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <hr>

        <div class="columns is-vcentered">
            <div class="column is-6">
                <b-field grouped group-multiline>
                    <b-field >
                        <b-input placeholder="Items per page" size="is-small" type="number" v-model="perPage"></b-input>
                    </b-field>
                </b-field>
            </div>
            <div class="column is-6">
                <b-pagination
                        :total="results.total"
                        :current.sync="results.current_page"
                        :order="order"
                        size="is-small"
                        :simple="isSimple"
                        :per-page="results.per_page"
                        @change="changePage"
                >
                </b-pagination>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                isLoading: true,
                results: null,
                limit: 0,

                perPage: 10,
                order: '',
                size: '',
                isSimple: true,
            }
        },
        mounted() {
            this.getActivities()
        },
        methods: {
            getActivities() {
                this.isLoading = true;

                axios.post(this.apiUrl() + '/user-activities', {
                    'per_page': this.perPage,
                }).then(response => {
                    this.results = response.data;

                    this.results.data.forEach((audit) => {
                        audit.expanded = false;
                    });

                    this.isLoading = false;
                }).catch(error => {
                    alert('Error occurred getting activities')
                })
            },
            changePage(pageNumber) {

                axios.post(this.apiUrl() + '/user-activities?page=' + pageNumber, {
                    per_page: this.perPage,
                }).then(response => {
                    this.results = response.data;
                })

            },
            openActivity(activity, index) {
                activity.expanded = ! activity.expanded;
                this.results.data.splice(index, 1);
                this.results.data.splice(index, 0, activity);
            },
        }
    }
</script>
