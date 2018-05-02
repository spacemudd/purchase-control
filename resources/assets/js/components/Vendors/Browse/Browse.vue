<template>
	<div class="level">
		<div class="level-left">
			<simple-search
					:hyper-linked-results="true"
					placeholder-text="Find suppliers"
					search-endpoint="vendors">
			</simple-search>
		</div>
		<div class="level-right">

			<button class="button is-primary pull-right"
					v-if="canCreate == 1"
					@click="$store.commit('Vendor/setNewModal', true)">
				{{ $t('words.new-supplier') }}
			</button>

		</div>
	</div>
</template>

<script>
    export default {
        props: {
            canCreate: {
                type: Number,
                required: false,
                default: 0,
            },
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
