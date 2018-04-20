<!--
  - Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
  -
  - Unauthorized copying of this file via any medium is strictly prohibited.
  - This file is a proprietary of Clarastars LLC and is confidential.
  -
  - https://clarastars.com - info@clarastars.com
  -
  -->

<template>
    <div class="box" v-if="request">
        <div class="columns">
            <div class="column is-12">
                <div class="columns">
                    <div class="column is-6">
                        <h1 class="title">
                            {{ request.number }}
                            <span v-if="!request.number" class="has-text-grey-lighter">Draft</span>
                        </h1>
                        <p class="subtitle is-6">Request Number</p>
                    </div>
                    <div class="column is-6 has-text-right">
                        <approve-confirmation v-if="request.status_name === 'draft'"
                                              :action="apiUrl() + `/requests/${request.id}/approve`"
                                              @success="reloadPage()">
                        </approve-confirmation>
                    </div>
                </div>
                <hr>
            </div>
        </div>


        <div class="columns">

            <div class="column is-6">
                <table class="table is-size-7 is-fullwidth">
                    <tbody>
                        <tr>
                            <td><b>Requested By</b></td>
                            <td><span v-if="request.requested_by">{{ request.requested_by.code }} - {{ request.requested_by.name }}</span></td>
                        </tr>
                        <tr>
                            <td><b>Requested For</b></td>
                            <td><span v-if="request.requested_for">{{ request.requested_for.code }} - {{ request.requested_for.name }}</span></td>
                        </tr>
                        <tr v-if="request.approved_by">
                            <td><b>Approved by</b></td>
                            <td>{{ request.approved_by.display_name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="column is-6">
                <table class="table is-size-7 is-fullwidth">
                    <tbody>
                    <tr>
                        <td><b>Cost Center By</b></td>
                        <td><span v-if="request.cost_center_by">{{ request.cost_center_by.code }} - {{ request.cost_center_by.description }}</span></td>
                    </tr>
                    <tr>
                        <td><b>Requested For</b></td>
                        <td><span v-if="request.cost_center_for">{{ request.cost_center_for.code }} - {{ request.cost_center_for.description }}</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                //
            }
        },
        computed: {
            request() {
                return this.$store.getters['PurchaseControlRequest/request'];
            }
        },
        mounted() {
            //
        },
        methods: {
            reloadPage() {
                window.location.reload();
            },
        }
    }
</script>
