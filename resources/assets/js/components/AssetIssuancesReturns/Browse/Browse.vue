<template>
	<div class="level">
		<div class="level-left">
			<simple-search
					:placeholder-text="$t('words.find-issuance-returns')"
					:hyper-linked-results="true"
					search-endpoint="asset-issuances-returns">
			</simple-search>
		</div>
		<div class="level-right">

			<button class="button is-primary pull-right" @click="showNewIssuanceReturnModal()">
				{{ $t('words.new-issuance-return') }}
			</button>

		</div>
	</div>
</template>

<script>
	export default {
		methods: {
            showNewIssuanceReturnModal() {
                this.$store.commit('AssetIssuanceReturn/setNewIssuanceReturnModalActive', true);
            },
		}
	}
</script>
