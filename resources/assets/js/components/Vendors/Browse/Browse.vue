<template>
	<div class="level">
		<div class="level-left">
			<simple-search
					:hyper-linked-results="true"
					placeholder-text="Find vendors"
					search-endpoint="vendors">
			</simple-search>
		</div>
		<div class="level-right">

			<button class="button is-primary pull-right" @click="$store.commit('Vendor/setNewModal', true)">
				{{ $t('words.new-vendor') }}
			</button>

		</div>
	</div>
</template>
