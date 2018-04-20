<template>
    <bar
            :data="graphData"
            :options="{responsive: true, maintainAspectRatio: false}"
    >
    </bar>
</template>

<script>
    import Bar from './Bar';

    export default {
        components: {
            Bar,
        },
        props: {
            graphData: {
                require: true,
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
