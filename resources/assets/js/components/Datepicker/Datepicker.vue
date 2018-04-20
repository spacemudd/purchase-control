<template>
    <span>
        <b-field :label="label">
            <b-datepicker
                    placeholder="Type or select a date..."
                    icon-pack="fa"
                    icon="calendar"
                    v-model="datePicked"
                    v-bind:required="isRequired"
                    :readonly="false">
            </b-datepicker>
        </b-field>
        <p class="help" @click="datePicked=null" v-if="datePicked" style="cursor: pointer">Clear</p>
        <input v-if="datePicked" type="hidden" :name="name" :value="dateFormatted">
    </span>
</template>

<script>
    import moment from 'moment';
    export default {
        props: {
            name: {
                type: String,
                required: true,
            },
            type: {
                type: String,
                required: false,
            },
            required: {
                type: Number,
                required: false,
                default: 0,
            },
            label: {
                type: String,
                required: false,
            }
        },
        data() {
            return {
                datePicked: null,
                isRequired: false,
            }
        },
        computed: {
            dateFormatted() {
                return moment(this.datePicked).format('DD-MM-YYYY');
            }
        },
        mounted() {
            this.isRequired = Boolean(this.required === 1);
        },
        methods: {
            //
        }
    }
</script>
