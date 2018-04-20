<template>
    <line-chart
            :data="fakerData"
            :options="{responsive: true, maintainAspectRatio: false, fill: false}"
    >
    </line-chart>
</template>

<script>
    import LineChart from './Linechart';

    export default {
        components: {
            LineChart,
        },
        data() {
            return {
                fakerData: {
                    labels: ["Jan 10'", "Jan 11'", "Jan 12'", "Jan 13'", "Jan 14'", "Jan 15'", "Jan 16'", "Jan 17'"],
                    datasets: [
                        {
                            fill: false,
                            label: 'Total Cost (SR)',
                            borderColor: '#f87979',
                            data: [25000, 17000, 11000, 16000, 14000, 19874, 20933]
                        },
                        {
                            fill: false,
                            label: 'Maintenance Costs (SR)',
                            borderColor: '#41d516',
                            data: [20000, 14000, 9000, 14000, 13000, 10874, 15933]
                        }
                    ]
                },
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
