<template>
    <div>
        <table class="table is-size-7 is-fullwidth">
        <thead>
        <tr>
        	<th>{{ $t('words.property') }}</th>
            <th>{{ $t('words.value') }}</th>
        </tr>
        </thead>
        	<tbody>
        			<tr v-for="configuration in configurations">
        				<td>{{ configuration.property }}</td>
                        <td>{{ configuration.value }}</td>
        			</tr>
        	</tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: {
            configurations: {
                type: Array,
                required: true,
            }
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

<style lang="sass">
    //
</style>
