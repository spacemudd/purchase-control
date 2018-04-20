<template>
    <div class="section">
        <table class="table">
            <thead>
            <tr>
                <th>..</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
            </tr>
            </tbody>
        </table>
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
            asset() {
                return this.$store.getters['Asset/asset'];
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
