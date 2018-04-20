<template>
    <chart
            :data="{hello: 123}"
            :options="{responsive: true, maintainAspectRatio: false}"
    >
    </chart>
</template>

<script>
    import Chart from './Chart';

    export default {
        components: {
            Chart,
        },
        data() {
            return {
                //
            }
        },
        mounted() {
            //
        },
        methods: {
            //
        }
    }
</script>
