<template>
    <section>
        <form class="form" method="post" style="margin-top:2rem" @submit.prevent="submitOrder">
                <div class="columns">
                    <div class="column">
                        <b-field label="Select a date">
                            <b-datepicker
                                v-model="date"
                                placeholder="Click to select...">
                            </b-datepicker>
                        </b-field>

                        <b-field label="Cost center">
                            <b-autocomplete
                                v-model="name"
                                :data="filteredDataArray"
                                placeholder="Cost center"
                                @select="option => selectedEmployee = option">
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                        </b-field>

                        <b-field label="Ext">
                            <b-input v-model="ext"></b-input>
                        </b-field>

                       <b-field label="Job description">
                            <b-input v-model="job_description" maxlength="200" type="textarea"></b-input>
                        </b-field>

                        <b-field label="Remark">
                            <b-input v-model="remark" maxlength="200" type="textarea"></b-input>
                        </b-field>

                        <b-field label="Requested Through">
                            <div class="block">
                                <b-radio v-model="status"
                                    native-value="completed">
                                    Completed
                                </b-radio>
                                <b-radio v-model="status"
                                    native-value="pending">
                                    Pending
                                </b-radio>    
                            </div>
                        </b-field>

                    </div>

                    <div class="column">
                        <b-field label="Employee">
                            <b-autocomplete
                                v-model="name"
                                :data="filteredDataArray"
                                placeholder="Employee"
                                @select="option => selectedEmployee = option">
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                        </b-field>

                        <b-field label="Location">
                            <b-autocomplete
                                v-model="name"
                                :data="filteredDataArray"
                                placeholder="Location"
                                @select="option => selectedEmployee = option">
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                        </b-field>


                        <b-field label="Requested Through">
                            <div class="block">
                                <b-radio v-model="requested_through"
                                    native-value="email">
                                    Email
                                </b-radio>
                                <b-radio v-model="requested_through"
                                    native-value="phone_call">
                                    Phone Call
                                </b-radio>
                                <b-radio v-model="requested_through"
                                    native-value="breakdown">
                                    Breakdown
                                </b-radio>
                                <b-radio v-model="requested_through"
                                    native-value="ppm">
                                    PPM
                                </b-radio>
                            </div>
                        </b-field>

                         <b-field label="Job duration">
                            <div class="columns">
                                <div class="column">
                                    <b-timepicker
                                        v-model="time_start"
                                        placeholder="Select start time"
                                        hour-format="24">
                                    </b-timepicker>
                                </div>
                                <div class="column">
                                    <b-timepicker
                                        v-model="time_end"
                                        placeholder="Select end time"
                                        hour-format="24">
                                    </b-timepicker>
                                </div>
                            </div>
                        </b-field>

                        <b-field label="Materials used">
                            <b-input v-model="material_used" maxlength="200" type="textarea"></b-input>
                        </b-field>

                        <b-field label="Technicians">
                            <b-table bordered :data="technicians" :columns="columns"></b-table>
                        </b-field>

                    </div>
                </div>

                <div class="columns is-centered">
                    <div class="column">
                        <button type="submit" class="button is-primary">Create job order</button>
                    </div>
                </div>
            </form>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                date:  new Date(),
                data: [
                    'Angular',
                    'Angular 2',
                    'Aurelia',
                    'Backbone',
                    'Ember',
                    'jQuery',
                    'Meteor',
                    'Node.js',
                    'Polymer',
                    'React',
                    'RxJS',
                    'Vue.js'
                ],
                technicians: [
                    { 'id': 1, 'name': 'Jesse'}
                ],
                columns: [
                    {
                        field: 'id',
                        label: 'ID',
                        width: '40',
                        numeric: true
                    },
                    {
                        field: 'name',
                        label: 'Name',
                    }
                ],
                name: '',
                ext: '',
                requested_through: 'email',
                job_description: '',
                time_start: new Date(),
                time_end: new Date(),
                material_used: '',
                remark: '',
                status: 'pending',
                selectedEmployee: null
            }
        },
         computed: {
            filteredDataArray() {
                return this.data.filter((option) => {
                    return option
                        .toString()
                        .toLowerCase()
                        .indexOf(this.name.toLowerCase()) >= 0
                })
            }
        },
        methods: {
             submitOrder() {
                console.log(this.$data)
            }
        }
    }
</script>