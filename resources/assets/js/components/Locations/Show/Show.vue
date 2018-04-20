<template>
    <div v-if="isLoadingDetails">
        <loading-screen size='is-large'></loading-screen>
    </div>
    <div v-else>

        <div v-if="location">

            <div class="box">
                <div class="columns">

                    <div class="column is-6">
                        <h1 class="title">{{ location.code }}</h1>
                        <p class="subtitle is-6">{{ $t('words.location-code') }}</p>
                    </div>
                    <div class="column is-6 has-text-right">
                        <!--<button class="button is-small is-warning">-->
                            <!--Edit-->
                        <!--</button>-->
                    </div>
                </div>
                <hr>
                <div class="columns">
                    <div class="column is-6">
                        <table class="table is-size-7 is-fullwidth">
                            <tbody>
                            <tr>
                                <td><strong>{{ $t('words.department') }}</strong></td>
                                <td>
                                    {{ location.department.code + ' - ' + location.department.description  }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <!--
                    <div class="column is-6">
                        <table class="table is-size-7 is-fullwidth">
                            <tbody>
                            //
                            </tbody>
                        </table>
                    </div>
                    -->
                </div>
            </div>


            <location-details :location="location"></location-details>

         </div>

    </div>
</template>

<script>

    import LocationDetails from './Details.vue';

    export default {
        components: {
            LocationDetails,
        },
        props: {
            location: {
                required: true,
            }
        },
        computed: {

        },
        data() {
            return {
                activeTab: 0,
                isLoadingDetails: false,
            }
        },
        mounted() {

        },
        methods: {
            //
        }
    }
</script>

<style lang="sass">
    //
</style>
