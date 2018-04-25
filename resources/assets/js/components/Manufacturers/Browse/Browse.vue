<template>
	<div class="level">
		<div class="level-left">
			<simple-search
					:hyper-linked-results="true"
					placeholder-text="Find manufacturers"
					search-endpoint="manufacturers">
			</simple-search>
		</div>
		<div class="level-right">

			<a class="button is-primary pull-right"
			   v-if="canCreate == 1"
			   @click="$store.commit('Manufacturer/setNewModal', true)">
				{{ $t('words.new-manufacturer') }}
			</a>

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
