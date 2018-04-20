<template>
	<div class="level">
		<div class="level-left">
			<simple-search
					:placeholder-text="$t('words.find-issuances')"
					:hyper-linked-results="true"
					search-endpoint="asset-issuances">
			</simple-search>
		</div>
		<div class="level-right">

			<button class="button is-primary pull-right" @click="showNewIssuanceModal()">
				{{ $t('words.new-issuance') }}
			</button>

		</div>
	</div>
</template>

<script>
	export default {
		methods: {
            showNewIssuanceModal() {
                this.$store.commit('AssetIssuance/setNewIssuanceModalActive', true);
            },
		}
	}
</script>
